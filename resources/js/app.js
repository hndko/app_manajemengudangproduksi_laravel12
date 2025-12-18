import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally
window.Chart = Chart;

// Configure SweetAlert2 with custom theme
window.Swal = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary mx-1',
        cancelButton: 'btn btn-secondary mx-1',
        denyButton: 'btn btn-danger mx-1',
    },
    buttonsStyling: false,
});

// Toast notification helper
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Dark mode toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or default to system preference
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add('dark');
    }

    // Theme toggle button handler
    window.toggleDarkMode = function() {
        const html = document.documentElement;
        html.classList.toggle('dark');

        const isDark = html.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        // Dispatch custom event for components that need to react to theme change
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
    };
});

// Sidebar toggle for mobile
window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
    }
    if (overlay) {
        overlay.classList.toggle('hidden');
    }
};

// Confirmation dialog helper
window.confirmAction = function(options = {}) {
    const defaults = {
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dibatalkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal',
    };

    return Swal.fire({ ...defaults, ...options });
};

// Delete confirmation
window.confirmDelete = function(formId) {
    confirmAction({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        confirmButtonText: 'Ya, hapus!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Number formatting helper
window.formatNumber = function(number, decimals = 0) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(number);
};

// Currency formatting helper
window.formatCurrency = function(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number);
};

// Date formatting helper
window.formatDate = function(date, format = 'long') {
    const d = new Date(date);

    if (format === 'short') {
        return d.toLocaleDateString('id-ID');
    } else if (format === 'long') {
        return d.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    } else if (format === 'datetime') {
        return d.toLocaleString('id-ID');
    }

    return d.toLocaleDateString('id-ID');
};

// Print functionality
window.printElement = function(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const printWindow = window.open('', '_blank');
    const styles = Array.from(document.styleSheets)
        .map(styleSheet => {
            try {
                return Array.from(styleSheet.cssRules)
                    .map(rule => rule.cssText)
                    .join('\n');
            } catch (e) {
                return '';
            }
        })
        .join('\n');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print - Mari Partner</title>
            <style>${styles}</style>
            <style>
                @media print {
                    body {
                        padding: 20px;
                        background: white !important;
                        color: black !important;
                    }
                    .no-print { display: none !important; }
                }
            </style>
        </head>
        <body>
            ${element.innerHTML}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
};

// Chart.js default configuration
Chart.defaults.color = '#64748b';
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.tooltip.backgroundColor = '#1e293b';
Chart.defaults.plugins.tooltip.titleColor = '#f1f5f9';
Chart.defaults.plugins.tooltip.bodyColor = '#f1f5f9';
Chart.defaults.plugins.tooltip.borderColor = '#334155';
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.padding = 12;

// Update chart colors when theme changes
window.addEventListener('theme-changed', function(e) {
    const isDark = e.detail.dark;
    Chart.defaults.color = isDark ? '#94a3b8' : '#64748b';

    // Re-render all charts
    Chart.helpers.each(Chart.instances, (chart) => {
        chart.update();
    });
});

// Console log for development
console.log('🏭 Gudang Produksi - Mari Partner');
console.log('✨ Application initialized successfully');
