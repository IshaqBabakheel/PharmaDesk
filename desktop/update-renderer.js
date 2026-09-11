let mode = "available";

const currentVersion =
    document.getElementById(
        "currentVersion"
    );

const newVersion =
    document.getElementById(
        "newVersion"
    );

const message =
    document.getElementById(
        "message"
    );

const status =
    document.getElementById(
        "status"
    );

const action =
    document.getElementById(
        "action"
    );

const later =
    document.getElementById(
        "later"
    );

const progress =
    document.getElementById(
        "progress"
    );

const bar =
    document.getElementById(
        "bar"
    );

const percent =
    document.getElementById(
        "percent"
    );

const speed =
    document.getElementById(
        "speed"
    );

function setBusy(value) {

    later.disabled = value;

    action.disabled = value;
}

function showAvailable(info) {

    mode = "available";

    newVersion.textContent =
        info.version;

    message.textContent =
        "A newer version of PharmaDesk is available to download. " +
        "You can continue using the current version until the update is ready.";

    status.textContent = "";

    status.className =
        "status";

    progress.style.display =
        "none";

    later.disabled = false;

    action.disabled = false;

    action.textContent =
        "Download";
}

function showDownloading() {

    mode = "downloading";

    progress.style.display =
        "block";

    message.textContent =
        "PharmaDesk is downloading the update. Please keep the application open.";

    status.textContent =
        "Downloading update...";

    status.className =
        "status";

    later.disabled = true;

    action.disabled = true;

    action.textContent =
        "Downloading...";
}

function showDownloaded(info) {

    mode = "downloaded";

    newVersion.textContent =
        info.version;

    progress.style.display =
        "block";

    bar.style.width =
        "100%";

    percent.textContent =
        "100%";

    speed.textContent =
        "Ready";

    message.textContent =
        "The update has been downloaded successfully. Restart PharmaDesk to install it.";

    status.textContent =
        "Your current application data will remain in place.";

    status.className =
        "status";

    later.disabled = false;

    action.disabled = false;

    action.textContent =
        "Restart & Update";
}

function showError(messageText) {

    mode = "error";

    status.textContent =
        messageText ||
        "The update could not be completed. Please try again later.";

    status.className =
        "status error";

    later.disabled = false;

    action.disabled = false;

    action.textContent =
        "Retry";
}

action.addEventListener(
    "click",
    async () => {

        if (
            mode === "available" ||
            mode === "error"
        ) {

            showDownloading();

            const result =
                await window
                    .pharmaDeskUpdater
                    .download();

            if (!result) {

                showError(
                    "The update download failed. Please try again."
                );
            }

            return;
        }

        if (
            mode === "downloaded"
        ) {

            const confirmed =
                window.confirm(
                    "PharmaDesk will close, install the update, and restart. Continue?"
                );

            if (!confirmed) {
                return;
            }

            setBusy(true);

            message.textContent =
                "Preparing the update. PharmaDesk will close shortly...";

            status.textContent =
                "Closing application services...";

            action.textContent =
                "Restarting...";

            await window
                .pharmaDeskUpdater
                .install();
        }

    }
);

later.addEventListener(
    "click",
    () => {

        window
            .pharmaDeskUpdater
            .later();

    }
);

window
    .pharmaDeskUpdater
    .onProgress(
        (value) => {

            const p =
                Math.max(
                    0,
                    Math.min(
                        100,
                        Number(
                            value?.percent
                        ) || 0
                    )
                );

            progress.style.display =
                "block";

            bar.style.width =
                `${p}%`;

            percent.textContent =
                `${p.toFixed(1)}%`;

            const bytesPerSecond =
                Number(
                    value?.bytesPerSecond
                ) || 0;

            speed.textContent =
                bytesPerSecond
                    ? `${(
                        bytesPerSecond /
                        1024 /
                        1024
                    ).toFixed(1)} MB/s`
                    : "Downloading...";
        }
    );

window
    .pharmaDeskUpdater
    .onDownloaded(
        (info) => {

            showDownloaded(info);

        }
    );

window
    .pharmaDeskUpdater
    .onError(
        (messageText) => {

            if (
                mode === "downloading" ||
                mode === "available"
            ) {

                showError(
                    messageText
                );
            }

        }
    );

(async () => {

    try {

        const state =
            await window
                .pharmaDeskUpdater
                .getState();

        currentVersion.textContent =
            state.currentVersion;

        newVersion.textContent =
            state.availableVersion ||
            "—";

        if (
            state.updateDownloaded
        ) {

            showDownloaded({
                version:
                    state.availableVersion ||
                    "—",
            });

        } else if (
            state.availableVersion
        ) {

            showAvailable({
                version:
                    state.availableVersion,
            });
        }

    } catch (error) {

        showError(
            error?.message ||
            "Unable to initialize the update window."
        );

    }

})();