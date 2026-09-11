const {
    contextBridge,
    ipcRenderer,
} = require("electron");

contextBridge.exposeInMainWorld(
    "pharmaDeskUpdater",
    {
        getState: () =>
            ipcRenderer.invoke(
                "updater:get-state"
            ),

        download: () =>
            ipcRenderer.invoke(
                "updater:download"
            ),

        install: () =>
            ipcRenderer.invoke(
                "updater:install"
            ),

        later: () =>
            ipcRenderer.send(
                "updater:later"
            ),

        onProgress: (callback) =>
            ipcRenderer.on(
                "updater:progress",
                (_event, progress) => {
                    callback(progress);
                }
            ),

        onDownloaded: (callback) =>
            ipcRenderer.on(
                "updater:downloaded",
                (_event, info) => {
                    callback(info);
                }
            ),

        onError: (callback) =>
            ipcRenderer.on(
                "updater:error",
                (_event, message) => {
                    callback(message);
                }
            ),
    }
);