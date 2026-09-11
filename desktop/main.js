const { app, BrowserWindow, dialog, ipcMain, } = require("electron");
// const { configureUpdater, checkForUpdates,} = require("./updater");
const { configureUpdater, checkForUpdates, downloadUpdate, installUpdate, isUpdateDownloaded, getUpdateState,} = require("./updater");
const { spawn, execFile } = require("child_process");
const net = require("net");
const path = require("path");
const fs = require("fs");

const isPackaged = app.isPackaged;
app.setPath("userData", path.join(app.getPath("appData"), "PharmaDesk"));
/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

const APP_HOST = "127.0.0.1";
const APP_PORT = 17845;

const APP_URL = `http://${APP_HOST}:${APP_PORT}`;

const MYSQL_HOST = "127.0.0.1";
const MYSQL_PORT = 3307;

/*
|--------------------------------------------------------------------------
| Application State
|--------------------------------------------------------------------------
*/

let updateWindow = null;
let updateInstallInProgress = false;
let mainWindow = null;
let laravelProcess = null;
let mysqlProcess = null;
let shutdownInProgress = false;

/*
|--------------------------------------------------------------------------
| Single Instance
|--------------------------------------------------------------------------
*/

const gotTheLock = app.requestSingleInstanceLock();

if (!gotTheLock) {
    app.quit();
} else {
    app.on("second-instance", () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) {
                mainWindow.restore();
            }

            mainWindow.focus();
        }
    });
}

/*
|--------------------------------------------------------------------------
| Paths
|--------------------------------------------------------------------------
*/

function getProjectPath() {
    if (isPackaged) {
        return path.join(process.resourcesPath, "laravel");
    }

    return path.resolve(__dirname, "..");
}

function getPhpPath() {
    if (isPackaged) {
        return path.join(
            process.resourcesPath,

            "runtime",

            "php",

            "php.exe",
        );
    }

    return path.join(
        getProjectPath(),

        "runtime",

        "php",

        "php.exe",
    );
}

function getMySqlPath() {
    if (isPackaged) {
        return path.join(
            process.resourcesPath,

            "runtime",

            "mysql",

            "bin",

            "mysqld.exe",
        );
    }

    return path.join(
        getProjectPath(),

        "runtime",

        "mysql",

        "bin",

        "mysqld.exe",
    );
}


function getUserDataPath() {
    return app.getPath("userData");
}

function getMySqlDataPath() {
    return path.join(getUserDataPath(), "mysql-data");
}

function ensureDirectory(directory) {
    if (!fs.existsSync(directory)) {
        fs.mkdirSync(directory, {
            recursive: true,
        });
    }
}

function initializeMySqlData() {
    const targetPath = getMySqlDataPath();

    if (fs.existsSync(targetPath) && fs.readdirSync(targetPath).length > 0) {
        console.log("Existing PharmaDesk MySQL data found.");

        return;
    }

    const sourcePath = isPackaged
        ? path.join(process.resourcesPath, "initial-data", "mysql-data")
        : path.join(getProjectPath(), "storage", "mysql-data");

    if (!fs.existsSync(sourcePath)) {
        throw new Error(`Initial MySQL data not found:\n${sourcePath}`);
    }

    ensureDirectory(targetPath);

    console.log("Copying initial PharmaDesk MySQL data...");

    fs.cpSync(sourcePath, targetPath, {
        recursive: true,
    });

    console.log("Initial MySQL data copied successfully.");
}

function createMySqlConfig() {
    const mysqlBasePath = isPackaged
        ? path.join(process.resourcesPath, "runtime", "mysql")
        : path.join(getProjectPath(), "runtime", "mysql");

    const mysqlDataPath = getMySqlDataPath();

    const mysqlTempPath = path.join(mysqlDataPath, "tmp");

    ensureDirectory(mysqlDataPath);
    ensureDirectory(mysqlTempPath);

    const configPath = path.join(
        getUserDataPath(),

        "my.ini",
    );

    const mysqlDataPathWindows = mysqlDataPath.replace(/\\/g, "/");
    const mysqlTempPathWindows = mysqlTempPath.replace(/\\/g, "/");
    const mysqlBasePathWindows = mysqlBasePath.replace(/\\/g, "/");

    const config = `[mysqld]

        basedir=${mysqlBasePathWindows}

        datadir=${mysqlDataPathWindows}

        port=${MYSQL_PORT}

        bind-address=127.0.0.1

        pid-file=${mysqlDataPathWindows}/mysql.pid

        log-error=${mysqlDataPathWindows}/mysql-error.log

        tmpdir=${mysqlTempPathWindows}

        skip-name-resolve


        [client]

        host=127.0.0.1

        port=${MYSQL_PORT}`;
    fs.writeFileSync(
        configPath,

        config,

        "utf8",
    );

    return configPath;
}

function getLaravelStoragePath() {
    return path.join(
        getUserDataPath(),

        "laravel-storage",
    );
}

/*
|--------------------------------------------------------------------------
| Utility: Wait
|--------------------------------------------------------------------------
*/

function sleep(milliseconds) {
    return new Promise((resolve) => {
        setTimeout(resolve, milliseconds);
    });
}

/*
|--------------------------------------------------------------------------
| Utility: Check TCP Port
|--------------------------------------------------------------------------
*/

async function isPortOpen(host, port) {
    try {
        const response = await fetch(`http://${host}:${port}`);

        return response.ok;
    } catch (error) {
        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Utility: Kill Process On Port
|--------------------------------------------------------------------------
*/

function killProcessOnPort(port) {
    return new Promise((resolve) => {
        if (process.platform !== "win32") {
            resolve();

            return;
        }

        execFile(
            "cmd",

            [
                "/c",

                `for /f "tokens=5" %a in ('netstat -ano ^| findstr :${port} ^| findstr LISTENING') do taskkill /PID %a /T /F`,
            ],

            () => {
                resolve();
            },
        );
    });
}

/*
|--------------------------------------------------------------------------
| MySQL Process
|--------------------------------------------------------------------------
*/

function startMySql() {
    const mysqlPath = getMySqlPath();

    // const mysqlConfigPath = getMySqlConfigPath();
    const mysqlConfigPath = createMySqlConfig();

    console.log("MySQL Path:", mysqlPath);

    console.log("MySQL Config:", mysqlConfigPath);

    mysqlProcess = spawn(
        mysqlPath,

        [`--defaults-file=${mysqlConfigPath}`],

        {
            cwd: getProjectPath(),

            windowsHide: true,
        },
    );

    mysqlProcess.stdout.on("data", (data) => {
        console.log(`[MySQL] ${data}`);
    });

    mysqlProcess.stderr.on("data", (data) => {
        console.error(`[MySQL Error] ${data}`);
    });

    mysqlProcess.on("error", (error) => {
        console.error("Failed to start MySQL:", error);
    });

    mysqlProcess.on("exit", (code, signal) => {
        console.log(`MySQL exited. Code: ${code}, Signal: ${signal}`);
    });
}

/*
|--------------------------------------------------------------------------
| Wait For MySQL
|--------------------------------------------------------------------------
*/

async function waitForMySql() {
    const maxAttempts = 30;

    for (let attempt = 1; attempt <= maxAttempts; attempt++) {
        /*
        |--------------------------------------------------------------------------
        | Check MySQL Process
        |--------------------------------------------------------------------------
        */

        if (mysqlProcess && mysqlProcess.exitCode !== null) {
            console.error("MySQL process exited unexpectedly.");

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Check Port
        |--------------------------------------------------------------------------
        */

        try {
            const result = await new Promise((resolve) => {
                execFile(
                    "cmd",

                    ["/c", `netstat -ano | findstr :${MYSQL_PORT}`],

                    (error, stdout) => {
                        resolve(stdout.includes("LISTENING"));
                    },
                );
            });

            if (result) {
                console.log("MySQL is ready.");

                return true;
            }
        } catch (error) {
            // Continue waiting.
        }

        console.log(`Waiting for MySQL... (${attempt}/${maxAttempts})`);

        await sleep(1000);
    }

    return false;
}

/*
|--------------------------------------------------------------------------
| Stop MySQL
|--------------------------------------------------------------------------
*/

async function stopMySql() {
    console.log("Stopping PharmaDesk MySQL server...");

    if (mysqlProcess && mysqlProcess.pid) {
        const pid = mysqlProcess.pid;

        console.log(`Stopping MySQL process tree: ${pid}`);

        if (process.platform === "win32") {
            await new Promise((resolve) => {
                execFile(
                    "taskkill",

                    ["/PID", String(pid), "/T", "/F"],

                    () => {
                        resolve();
                    },
                );
            });
        } else {
            mysqlProcess.kill("SIGTERM");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Safety Cleanup
    |--------------------------------------------------------------------------
    */

    await killProcessOnPort(MYSQL_PORT);

    mysqlProcess = null;
}


/*
|--------------------------------------------------------------------------
| Laravel Process
|--------------------------------------------------------------------------
*/

function startLaravel() {
    const projectPath = getProjectPath();
    const phpPath = getPhpPath();

    const laravelStoragePath = getLaravelStoragePath();
    ensureDirectory(laravelStoragePath);

    laravelProcess = spawn(
        phpPath,
        // ["artisan", "serve", `--host=${//APP_HOST}`, `--port=${APP_PORT}`],
        [
            "-d",
            "opcache.enable=0",
            "-d",
            "opcache.enable_cli=0",
            "artisan",
            "serve",
            `--host=${APP_HOST}`,
            `--port=${APP_PORT}`,
        ],
        {
            cwd: projectPath,
            windowsHide: true,
            env: {
                ...process.env,

                LARAVEL_STORAGE_PATH: laravelStoragePath,

                DB_DUMP_BINARY_PATH: path.join(
                    isPackaged
                        ? process.resourcesPath
                        : getProjectPath(),
                    "runtime",
                    "mysql",
                    "bin",
                ),
            },
        },
    );

    laravelProcess.stdout.on("data", (data) => {
        console.log(`[Laravel] ${data}`);
    });

    laravelProcess.stderr.on("data", (data) => {
        console.error(`[Laravel Error] ${data}`);
    });

    laravelProcess.on("error", (error) => {
        console.error("Failed to start Laravel:", error);
    });

    laravelProcess.on("exit", (code, signal) => {
        console.log(`Laravel exited. Code: ${code}, Signal: ${signal}`);
    });
}

/*
|--------------------------------------------------------------------------
| Stop Laravel
|--------------------------------------------------------------------------
*/

async function stopLaravel() {
    console.log("Stopping PharmaDesk Laravel server...");

    if (laravelProcess && laravelProcess.pid) {
        const pid = laravelProcess.pid;

        console.log(`Stopping Laravel process tree: ${pid}`);

        if (process.platform === "win32") {
            await new Promise((resolve) => {
                execFile(
                    "taskkill",

                    ["/PID", String(pid), "/T", "/F"],

                    () => {
                        resolve();
                    },
                );
            });
        } else {
            laravelProcess.kill("SIGTERM");
        }
    }

    await killProcessOnPort(APP_PORT);

    laravelProcess = null;
}

/*
|--------------------------------------------------------------------------
| Wait For Laravel
|--------------------------------------------------------------------------
*/

async function waitForLaravel() {
    const maxAttempts = 30;

    for (let attempt = 1; attempt <= maxAttempts; attempt++) {
        if (laravelProcess && laravelProcess.exitCode !== null) {
            console.error("Laravel process exited unexpectedly.");
            return false;
        }

        const serverReady = await new Promise((resolve) => {
            const socket = net.createConnection({
                host: APP_HOST,
                port: APP_PORT,
            });

            const timeout = setTimeout(() => {
                socket.destroy();
                resolve(false);
            }, 1000);

            socket.once("connect", () => {
                clearTimeout(timeout);
                socket.destroy();
                resolve(true);
            });

            socket.once("error", () => {
                clearTimeout(timeout);
                socket.destroy();
                resolve(false);
            });
        });

        if (serverReady) {
            console.log(
                `Laravel HTTP server is ready on ${APP_HOST}:${APP_PORT}.`,
            );

            return true;
        }

        console.log(`Waiting for Laravel... (${attempt}/${maxAttempts})`);

        await sleep(1000);
    }

    return false;
}

function sendUpdateWindow(channel, payload = null) {
    if (!updateWindow || updateWindow.isDestroyed()) {
        return;
    }

    if (payload === null) {
        updateWindow.webContents.send(channel);
    } else {
        updateWindow.webContents.send(channel, payload);
    }
}

function createUpdateWindow(info) {
    if (updateWindow && !updateWindow.isDestroyed()) {
        updateWindow.show();
        updateWindow.focus();

        return;
    }

    updateWindow = new BrowserWindow({
        width: 540,
        height: 410,
        minWidth: 540,
        maxWidth: 540,
        minHeight: 410,
        maxHeight: 410,
        resizable: false,
        minimizable: false,
        maximizable: false,
        fullscreenable: false,
        title: "PharmaDesk Update",
        parent: mainWindow || undefined,
        modal: Boolean(mainWindow),
        show: false,
        backgroundColor: "#f5f7fa",

        webPreferences: {
            preload: path.join(__dirname, "update-preload.js"),
            contextIsolation: true,
            nodeIntegration: false,
            sandbox: true,
        },
    });

    updateWindow.loadFile(
        path.join(__dirname, "update.html")
    );

    updateWindow.once("ready-to-show", () => {
        if (updateWindow && !updateWindow.isDestroyed()) {
            updateWindow.show();
            updateWindow.focus();
        }
    });

    updateWindow.on("closed", () => {
        updateWindow = null;
    });
}

async function prepareForUpdateInstall() {
    if (updateInstallInProgress) {
        return false;
    }

    updateInstallInProgress = true;
    shutdownInProgress = true;

    console.log(
        "Preparing PharmaDesk for update installation..."
    );

    await stopLaravel();
    await stopMySql();

    console.log(
        "PharmaDesk services stopped. Starting update installation."
    );

    return true;
}
/*
|--------------------------------------------------------------------------
| Create Window
|--------------------------------------------------------------------------
*/

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1400,

        height: 900,

        minWidth: 1100,

        minHeight: 700,

        title: `PharmaDesk v${app.getVersion()}`,

        show: false,

        webPreferences: {
            contextIsolation: true,

            nodeIntegration: false,
        },
    });

    mainWindow.loadURL(APP_URL);

    mainWindow.webContents.on("did-start-loading", () => {
        console.log("[Renderer] Started loading:", mainWindow.webContents.getURL());
    });

    mainWindow.webContents.on("did-finish-load", () => {
        console.log("[Renderer] Finished loading:", mainWindow.webContents.getURL());
    });

    mainWindow.webContents.on("did-fail-load", (event, errorCode, errorDescription, validatedURL) => {
        console.error("[Renderer] Failed to load:", {
            errorCode,
            errorDescription,
            validatedURL,
        });
    });

    mainWindow.webContents.on("render-process-gone", (event, details) => {
        console.error("[Renderer] Render process gone:", details);
    });

    mainWindow.webContents.on("console-message", (event, level, message, line, sourceId) => {
        console.log("[Renderer Console]", {
            level,
            message,
            line,
            sourceId,
        });
    });

    mainWindow.once("ready-to-show", () => {
        mainWindow.show();
    });

    mainWindow.on("closed", () => {
        mainWindow = null;
    });
}


/*
|--------------------------------------------------------------------------
| Application Startup
|--------------------------------------------------------------------------
*/
ipcMain.handle("updater:get-state", () => {
    return getUpdateState();
});

ipcMain.handle("updater:download", async () => {
    return await downloadUpdate();
});

ipcMain.handle("updater:install", async () => {
    if (!isUpdateDownloaded()) {
        return false;
    }

    const prepared = await prepareForUpdateInstall();

    if (!prepared) {
        return false;
    }

    return installUpdate();
});

ipcMain.on("updater:later", () => {
    if (updateWindow && !updateWindow.isDestroyed()) {
        updateWindow.hide();
    }
});


app.whenReady().then(async () => {
    /*
        |--------------------------------------------------------------------------
        | Start MySQL
        |--------------------------------------------------------------------------
        */
      

    console.log("Starting PharmaDesk MySQL...");

    try {
        initializeMySqlData();
    } catch (error) {
        await dialog.showMessageBox({
            type: "error",

            title: `PharmaDesk v${app.getVersion()}`,

            message: "PharmaDesk could not initialize its database.",

            detail: error.message,
        });

        app.quit();

        return;
    }

    startMySql();

    const mysqlReady = await waitForMySql();

    if (!mysqlReady) {
        await dialog.showMessageBox({
            type: "error",

            title: `PharmaDesk v${app.getVersion()}`,

            message: "PharmaDesk could not start MySQL.",

            detail: "The portable MySQL server failed to start.",
        });

        await stopMySql();

        app.quit();

        return;
    }

    /*
        |--------------------------------------------------------------------------
        | Start Laravel
        |--------------------------------------------------------------------------
        */

    console.log("Starting PharmaDesk Laravel...");

    startLaravel();

    const laravelReady = await waitForLaravel();

    if (!laravelReady) {
        await dialog.showMessageBox({
            type: "error",

            title: `PharmaDesk v${app.getVersion()}`,

            message: "PharmaDesk could not start.",

            detail: "The Laravel application failed to start.",
        });

        await stopLaravel();

        await stopMySql();

        app.quit();

        return;
    }

    /*
        |--------------------------------------------------------------------------
        | Create Desktop Window
        |--------------------------------------------------------------------------
        */

    createWindow();

    // configureUpdater();

    // if (isPackaged) {
    //     setTimeout(() => {
    //         checkForUpdates();
    //     }, 5000);
    // }

    configureUpdater({
        onChecking: () => {
            sendUpdateWindow("updater:checking");
        },

        onAvailable: (info) => {
            createUpdateWindow(info);
        },

        onNotAvailable: () => {
            // No UI required.
        },

        onProgress: (progress) => {
            sendUpdateWindow(
                "updater:progress",
                progress
            );
        },

        onDownloaded: (info) => {
            createUpdateWindow(info);

            sendUpdateWindow(
                "updater:downloaded",
                info
            );
        },

        onError: (error) => {
            sendUpdateWindow(
                "updater:error",
                error?.message ||
                    "The update service reported an unexpected error."
            );
        },
    });

    if (isPackaged) {
        setTimeout(() => {
            checkForUpdates();
        }, 5000);
    }
});

/*
|--------------------------------------------------------------------------
| Application Shutdown
|--------------------------------------------------------------------------
*/

app.on(
    "before-quit",

    async (event) => {
        if (shutdownInProgress) {
            return;
        }

        event.preventDefault();

        shutdownInProgress = true;

        console.log("Shutting down PharmaDesk...");

        /*
        |--------------------------------------------------------------------------
        | Stop Laravel First
        |--------------------------------------------------------------------------
        */

        await stopLaravel();

        /*
        |--------------------------------------------------------------------------
        | Stop MySQL
        |--------------------------------------------------------------------------
        */

        await stopMySql();

        console.log("PharmaDesk shutdown complete.");

        app.quit();
    },
);

/*
|--------------------------------------------------------------------------
| Close Application
|--------------------------------------------------------------------------
*/

app.on(
    "window-all-closed",

    () => {
        if (process.platform !== "darwin") {
            app.quit();
        }
    },
);
