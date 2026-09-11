// const { app, dialog } = require("electron");
// const { autoUpdater } = require("electron-updater");

// let updateCheckInProgress = false;
// let updateDownloaded = false;

// function configureUpdater() {
//     autoUpdater.autoDownload = false;
//     autoUpdater.autoInstallOnAppQuit = false;
    
//     autoUpdater.allowPrerelease = false;
//     autoUpdater.allowDowngrade = false;
//     autoUpdater.channel = "latest";

//     autoUpdater.logger = {
//         info: (message) => console.log(`[Updater] ${message}`),
//         warn: (message) => console.warn(`[Updater] ${message}`),
//         error: (message) => console.error(`[Updater] ${message}`),
//         debug: (message) => console.debug(`[Updater] ${message}`),
//     };

//     autoUpdater.on("checking-for-update", () => {
//         console.log("[Updater] Checking for updates...");
//     });

//     autoUpdater.on("update-available", (info) => {
//         console.log(
//             `[Updater] Update available: ${info.version}`
//         );
//     });

//     autoUpdater.on("update-not-available", (info) => {
//         console.log(
//             `[Updater] No update available. Current version: ${app.getVersion()}`
//         );
//     });

//     autoUpdater.on("download-progress", (progress) => {
//         console.log(
//             `[Updater] Download progress: ${progress.percent.toFixed(1)}%`
//         );
//     });

//     autoUpdater.on("update-downloaded", (info) => {
//         updateDownloaded = true;

//         console.log(
//             `[Updater] Update downloaded: ${info.version}`
//         );
//     });

//     autoUpdater.on("error", (error) => {
//         updateCheckInProgress = false;

//         console.error(
//             "[Updater] Update error:",
//             error
//         );
//     });
// }

// async function checkForUpdates() {
//     if (!app.isPackaged) {
//         console.log(
//             "[Updater] Skipping update check in development mode."
//         );

//         return null;
//     }

//     if (updateCheckInProgress) {
//         return null;
//     }

//     updateCheckInProgress = true;

//     try {
//         const result =
//             await autoUpdater.checkForUpdates();

//         return result;
//     } catch (error) {

//         console.error(
//             "[Updater] Failed to check for updates:",
//             error
//         );

//         return null;

//     } finally {

//         updateCheckInProgress = false;

//     }
// }

// async function downloadUpdate() {
//     try {

//         await autoUpdater.downloadUpdate();

//         return true;

//     } catch (error) {

//         console.error(
//             "[Updater] Failed to download update:",
//             error
//         );

//         return false;
//     }
// }

// function installUpdate() {
//     if (!updateDownloaded) {
//         console.warn(
//             "[Updater] installUpdate() called before update was downloaded."
//         );

//         return;
//     }

//     autoUpdater.quitAndInstall(
//         false,
//         true
//     );
// }

// function isUpdateDownloaded() {
//     return updateDownloaded;
// }

// module.exports = {
//     configureUpdater,
//     checkForUpdates,
//     downloadUpdate,
//     installUpdate,
//     isUpdateDownloaded,
// };


const { app } = require("electron");
const { autoUpdater } = require("electron-updater");

let updateCheckInProgress = false;
let updateDownloaded = false;
let availableUpdate = null;

let callbacks = {
    onChecking: null,
    onAvailable: null,
    onNotAvailable: null,
    onProgress: null,
    onDownloaded: null,
    onError: null,
};

function safeCallback(name, ...args) {
    const callback = callbacks[name];

    if (typeof callback !== "function") {
        return;
    }

    try {
        callback(...args);
    } catch (error) {
        console.error(
            `[Updater] UI callback "${name}" failed:`,
            error
        );
    }
}

function configureUpdater(uiCallbacks = {}) {
    callbacks = {
        ...callbacks,
        ...uiCallbacks,
    };

    autoUpdater.autoDownload = false;
    autoUpdater.autoInstallOnAppQuit = false;

    autoUpdater.allowPrerelease = false;
    autoUpdater.allowDowngrade = false;
    autoUpdater.channel = "latest";

    autoUpdater.logger = {
        info: (message) =>
            console.log(`[Updater] ${message}`),

        warn: (message) =>
            console.warn(`[Updater] ${message}`),

        error: (message) =>
            console.error(`[Updater] ${message}`),

        debug: (message) =>
            console.debug(`[Updater] ${message}`),
    };

    autoUpdater.on(
        "checking-for-update",
        () => {
            console.log(
                "[Updater] Checking for updates..."
            );

            safeCallback("onChecking");
        }
    );

    autoUpdater.on(
        "update-available",
        (info) => {
            availableUpdate = info;

            console.log(
                `[Updater] Update available: ${info.version}`
            );

            safeCallback(
                "onAvailable",
                info
            );
        }
    );

    autoUpdater.on(
        "update-not-available",
        () => {
            availableUpdate = null;

            console.log(
                `[Updater] No update available. Current version: ${app.getVersion()}`
            );

            safeCallback("onNotAvailable");
        }
    );

    autoUpdater.on(
        "download-progress",
        (progress) => {
            safeCallback(
                "onProgress",
                progress
            );
        }
    );

    autoUpdater.on(
        "update-downloaded",
        (info) => {
            updateDownloaded = true;

            availableUpdate = info;

            console.log(
                `[Updater] Update downloaded: ${info.version}`
            );

            safeCallback(
                "onDownloaded",
                info
            );
        }
    );

    autoUpdater.on(
        "error",
        (error) => {
            updateCheckInProgress = false;

            console.error(
                "[Updater] Update error:",
                error
            );

            safeCallback(
                "onError",
                error
            );
        }
    );
}

async function checkForUpdates() {
    if (!app.isPackaged) {
        console.log(
            "[Updater] Skipping update check in development mode."
        );

        return null;
    }

    if (updateCheckInProgress) {
        console.log(
            "[Updater] Update check already in progress."
        );

        return null;
    }

    updateCheckInProgress = true;

    try {
        return await autoUpdater.checkForUpdates();
    } catch (error) {
        console.error(
            "[Updater] Failed to check for updates:",
            error
        );

        safeCallback(
            "onError",
            error
        );

        return null;
    } finally {
        updateCheckInProgress = false;
    }
}

async function downloadUpdate() {
    if (!availableUpdate) {
        console.warn(
            "[Updater] No available update to download."
        );

        return false;
    }

    if (updateDownloaded) {
        return true;
    }

    try {
        await autoUpdater.downloadUpdate();

        return true;
    } catch (error) {
        console.error(
            "[Updater] Failed to download update:",
            error
        );

        safeCallback(
            "onError",
            error
        );

        return false;
    }
}

function installUpdate() {
    if (!updateDownloaded) {
        console.warn(
            "[Updater] installUpdate() called before update was downloaded."
        );

        return false;
    }

    autoUpdater.quitAndInstall(
        false,
        true
    );

    return true;
}

function isUpdateDownloaded() {
    return updateDownloaded;
}

function getUpdateState() {
    return {
        currentVersion: app.getVersion(),

        availableVersion:
            availableUpdate?.version ?? null,

        updateDownloaded,

        checking:
            updateCheckInProgress,
    };
}

module.exports = {
    configureUpdater,
    checkForUpdates,
    downloadUpdate,
    installUpdate,
    isUpdateDownloaded,
    getUpdateState,
};