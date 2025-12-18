import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';

// Initialize Alpine.js
window.Alpine = Alpine;

// Global Alpine Store
Alpine.store('app', {
    darkMode: localStorage.getItem('darkMode') === 'true',
    sidebarOpen: window.innerWidth >= 1024,

    init() {
        this.applyDarkMode();

        // Listen for resize
        window.addEventListener('resize', () => {
            this.sidebarOpen = window.innerWidth >= 1024;
        });
    },

    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.applyDarkMode();
    },

    applyDarkMode() {
        document.documentElement.classList.toggle('dark', this.darkMode);
    },

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    closeSidebar() {
        if (window.innerWidth < 1024) {
            this.sidebarOpen = false;
        }
    }
});

// Alpine Components
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

Alpine.data('tabs', (defaultTab = 0) => ({
    activeTab: defaultTab,
    setTab(index) {
        this.activeTab = index;
    }
}));

Alpine.data('accordion', () => ({
    active: null,
    toggle(index) {
        this.active = this.active === index ? null : index;
    }
}));

// Start Alpine
Alpine.start();

// Make Chart.js and Swal globally available
window.Chart = Chart;
window.Swal = Swal;

// Configure Chart.js defaults
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748b';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.padding = 16;
Chart.defaults.elements.bar.borderRadius = 6;
Chart.defaults.elements.line.tension = 0.4;

// Global Helper Functions
window.helpers = {
    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    },

    formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    },

    formatDate(date, format = 'short') {
        const options = format === 'long'
            ? { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
            : { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Date(date).toLocaleDateString('id-ID', options);
    },

    formatTime(date) {
        return new Date(date).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
};

// SweetAlert2 Helpers
window.toast = {
    success(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'text-sm rounded-lg'
            }
        });
    },

    error(message) {
        Swal.fire({
            icon: 'error',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'text-sm rounded-lg'
            }
        });
    },

    info(message) {
        Swal.fire({
            icon: 'info',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'text-sm rounded-lg'
            }
        });
    }
};

window.confirm = async (options = {}) => {
    const result = await Swal.fire({
        title: options.title || 'Konfirmasi',
        text: options.text || 'Apakah Anda yakin?',
        icon: options.icon || 'question',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Ya',
        cancelButtonText: options.cancelText || 'Batal',
        confirmButtonColor: '#3b9dd4',
        cancelButtonColor: '#64748b',
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'text-sm px-4 py-2',
            cancelButton: 'text-sm px-4 py-2'
        }
    });
    return result.isConfirmed;
};

// Delete confirmation
window.confirmDelete = async (form) => {
    const confirmed = await window.confirm({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        confirmText: 'Ya, Hapus!'
    });

    if (confirmed) {
        form.submit();
    }
};

// Print function
window.printElement = (elementId) => {
    const element = document.getElementById(elementId);
    if (!element) return;

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Inter', sans-serif; padding: 20px; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background: #f5f5f5; font-weight: 600; }
                h1, h2, h3 { margin-bottom: 10px; }
            </style>
        </head>
        <body>${element.innerHTML}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
};

// Auto-close flash messages
document.addEventListener('DOMContentLoaded', () => {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('[data-auto-dismiss]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('animate-fade-out');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
