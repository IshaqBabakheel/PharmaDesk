document.addEventListener('DOMContentLoaded', function () {

    const fullscreenBtn = document.getElementById('fullscreenBtn');

    if (!fullscreenBtn) {
        return;
    }


    /*
     * Enter fullscreen
     */
    function enterFullscreen() {

        const element = document.documentElement;

        if (element.requestFullscreen) {
            element.requestFullscreen();
        }
    }


    /*
     * Exit fullscreen
     */
    function exitFullscreen() {

        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }


    /*
     * Update button according to fullscreen state
     */
    function updateFullscreenButton() {

        const isFullscreen = !!document.fullscreenElement;

        const icon = fullscreenBtn.querySelector('i');
        const text = fullscreenBtn.querySelector('span');

        if (isFullscreen) {

            icon.className = 'bi bi-fullscreen-exit';

            text.textContent = 'Exit Fullscreen';

            fullscreenBtn.title = 'Exit Fullscreen (Esc / Ctrl + Shift + F)';

        } else {

            icon.className = 'bi bi-fullscreen';

            text.textContent = 'Fullscreen';

            fullscreenBtn.title = 'Enter Fullscreen (Ctrl + Shift + F)';
        }
    }


    /*
     * Button click
     */
    fullscreenBtn.addEventListener('click', function () {

        if (document.fullscreenElement) {

            exitFullscreen();

        } else {

            enterFullscreen();
        }
    });


    /*
     * Detect browser fullscreen changes.
     *
     * This is important because pressing ESC
     * also triggers this event.
     */
    document.addEventListener(
        'fullscreenchange',
        updateFullscreenButton
    );


    /*
     * Keyboard shortcut
     *
     * Ctrl + Shift + F
     */
    document.addEventListener('keydown', function (event) {

        if (
            event.ctrlKey &&
            event.shiftKey &&
            event.key.toLowerCase() === 'f'
        ) {

            event.preventDefault();

            if (document.fullscreenElement) {

                exitFullscreen();

            } else {

                enterFullscreen();
            }
        }
    });


    /*
     * Initial state
     */
    updateFullscreenButton();

});
