// SweetAlert2 helper functions
window.showSuccessAlert = function(message, title = 'Berhasil!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#28a745'
    });
};

window.showErrorAlert = function(message, title = 'Error!') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });
};

window.showWarningAlert = function(message, title = 'Peringatan!') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#ffc107'
    });
};

window.showInfoAlert = function(message, title = 'Informasi') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#17a2b8'
    });
};

window.showConfirmDialog = function(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Batal') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText
    });
};

// Auto-show alerts based on session flash data
document.addEventListener('DOMContentLoaded', function() {
    // Check for success message
    const successMessage = document.querySelector('meta[name="success-message"]');
    if (successMessage && successMessage.content) {
        showSuccessAlert(successMessage.content);
    }

    // Check for error message
    const errorMessage = document.querySelector('meta[name="error-message"]');
    if (errorMessage && errorMessage.content) {
        showErrorAlert(errorMessage.content);
    }

    // Check for warning message
    const warningMessage = document.querySelector('meta[name="warning-message"]');
    if (warningMessage && warningMessage.content) {
        showWarningAlert(warningMessage.content);
    }

    // Check for info message
    const infoMessage = document.querySelector('meta[name="info-message"]');
    if (infoMessage && infoMessage.content) {
        showInfoAlert(infoMessage.content);
    }
});
