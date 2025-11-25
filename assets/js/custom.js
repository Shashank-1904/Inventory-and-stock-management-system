window.onload = function () {
    // Get the toast element
    var toast = document.getElementById('toast');
    var closeBtn = document.getElementById('closeToast');

    // Check if there's a toast to show
    if (toast) {
        toast.classList.add('show');

        // Hide toast after 3 seconds if not closed manually
        setTimeout(function () {
            if (toast.classList.contains('show')) {
                toast.classList.remove('show');
            }
        }, 8000);
    }

    // Close the toast manually when the close button is clicked
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            toast.classList.remove('show');
        });
    }
};
