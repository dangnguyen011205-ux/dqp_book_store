// Public/js/main.js

document.addEventListener('DOMContentLoaded', function () {
    // Tự động đóng toast notification sau 4 giây
    const toasts = document.querySelectorAll('#toast-container > div');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    });
});

/**
 * Hiển thị Toast thông báo nhanh bằng JavaScript
 */
function showToast(type, message) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-20 right-5 z-[9999] flex flex-col gap-3 pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgClass = type === 'success' ? 'bg-emerald-700' : (type === 'error' ? 'bg-rose-700' : 'bg-sky-700');
    const icon = type === 'success' ? 'check_circle' : (type === 'error' ? 'error' : 'info');

    toast.className = `pointer-events-auto flex items-center gap-3 ${bgClass} text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300 animate-slide-in`;
    toast.innerHTML = `
        <span class="material-symbols-outlined text-[22px]">${icon}</span>
        <span class="text-sm font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/**
 * Mở modal popup
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Đóng modal popup
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}
