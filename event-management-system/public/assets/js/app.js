/**
 * Main Application JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Enable Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Sidebar toggle functionality
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-toggled');
            document.querySelector('.sidebar').classList.toggle('toggled');
            
            // Save preference to localStorage
            if (document.querySelector('.sidebar').classList.contains('toggled')) {
                localStorage.setItem('sidebarToggled', 'true');
            } else {
                localStorage.setItem('sidebarToggled', 'false');
            }
        });
    }

    // Close any open menu accordions when window is resized below 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            document.querySelectorAll('.sidebar .collapse').forEach(element => {
                const bsCollapse = bootstrap.Collapse.getInstance(element);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            });
        }
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Initialize DataTables if present
    if (typeof $.fn.DataTable === 'function') {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });
    }

    // Initialize datepickers
    if (typeof $.fn.datepicker === 'function') {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    // Initialize select2 if present
    if (typeof $.fn.select2 === 'function') {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }

    // Handle file upload preview
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose file';
            const label = this.nextElementSibling;
            label.textContent = fileName;
        });
    });

    // Initialize summernote if present
    if (typeof $.fn.summernote === 'function') {
        $('.summernote').summernote({
            height: 300,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    }
});

// AJAX request helper
function ajaxRequest(url, method = 'GET', data = null) {
    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    };

    const options = {
        method: method,
        headers: headers,
        credentials: 'same-origin'
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    return fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Show loading spinner
function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.innerHTML = `
        <div class="position-fixed w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center" style="z-index: 1090; top: 0; left: 0;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    document.body.appendChild(loading);
}

// Hide loading spinner
function hideLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;
    
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
    
    bsToast.show();
}

// Confirm dialog
function confirmDialog(message, callback) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.tabIndex = '-1';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>${message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    return new Promise((resolve) => {
        modal.querySelector('#confirmButton').addEventListener('click', function() {
            modalInstance.hide();
            modal.addEventListener('hidden.bs.modal', function() {
                modal.remove();
                resolve(true);
            }, { once: true });
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            modal.remove();
            resolve(false);
        }, { once: true });
    });
}

// Export functions to global scope
window.app = {
    ajax: ajaxRequest,
    showLoading,
    hideLoading,
    showToast,
    confirm: confirmDialog
};
