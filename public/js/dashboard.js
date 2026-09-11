document.addEventListener('DOMContentLoaded', function () {
    const refreshButton = document.getElementById('dashboardRefresh');

    if (!refreshButton) {
        return;
    }

    refreshButton.addEventListener('click', function () {
        const icon = refreshButton.querySelector('i');

        refreshButton.disabled = true;

        if (icon) {
            icon.classList.add('fa-spin');
        }

        window.location.reload();
    });
});
