/**
 * RunwayHub - Main JavaScript
 */

// Debounce function for input handling
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Fetch API helper
async function fetchData(url, options = {}) {
    const response = await fetch(url, {
        headers: {
            'Accept': 'application/json',
            ...options.headers,
        },
        ...options,
    });

    if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status}`);
    }

    return response.json();
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Format currency
function formatCurrency(amount, currency = 'EUR') {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: currency,
    }).format(amount);
}

// Format date
function formatDate(date, format = 'YYYY-MM-DD') {
    const d = new Date(date);
    
    if (format === 'YYYY-MM-DD') {
        return d.toISOString().split('T')[0];
    }
    
    return d.toLocaleDateString('de-DE');
}

// Format datetime
function formatDatetime(date, timezone = 'Europe/Berlin') {
    const d = new Date(date);
    return d.toLocaleString('de-DE', {
        timeZone: timezone,
        dateStyle: 'short',
        timeStyle: 'short',
    });
}

// Validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email.toLowerCase());
}

// Validate password strength
function validatePassword(password) {
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password),
    };

    return requirements;
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span class="toast-message">${message}</span>
        <button class="toast-close" aria-label="Close">&times;</button>
    `;
    
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#17a2b8'};
        color: white;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: fadeIn 0.3s ease;
    `;
    
    // Add close button
    const close = toast.querySelector('.toast-close');
    close.addEventListener('click', () => toast.remove());
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => toast.remove(), 5000);
}

// Modal helper
function showModal(title, content, width = '500px') {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-header">
                <h2>${title}</h2>
                <button class="modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">${content}</div>
        </div>
    `;
    
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;
    
    const modalContent = modal.querySelector('.modal');
    modalContent.style.cssText = `
        background: white;
        border-radius: 8px;
        max-width: ${width};
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    `;
    
    // Close on overlay click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    // Close button
    const close = modal.querySelector('.modal-close');
    close.addEventListener('click', () => modal.remove());
    
    document.body.appendChild(modal);
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Confirm dialog
function confirm(message, onConfirm) {
    return new Promise((resolve) => {
        const modal = showModal('Bestätigung', message, '300px');
        
        const confirmBtn = modal.querySelector('.modal-body')?.insertAdjacentHTML(
            'beforeend',
            '<button class="btn btn-primary mt-2 mb-2 confirm-btn">Ja</button>'
        );
        
        const cancelBtn = modal.querySelector('.modal-body')?.insertAdjacentHTML(
            'beforeend',
            '<button class="btn btn-secondary mt-2 mb-2 cancel-btn">Nein</button>'
        );
        
        confirmBtn?.addEventListener('click', () => {
            modal.remove();
            resolve(true);
        });
        
        cancelBtn?.addEventListener('click', () => {
            modal.remove();
            resolve(false);
        });
    });
}

// Search debounce
function createDebounceSearch(input, action, delay = 300) {
    const debouncedAction = debounce(action, delay);
    
    input.addEventListener('input', (e) => {
        const query = e.target.value;
        if (query.length >= 2) {
            debouncedAction(query);
        }
    });
}

// Table actions
function createTableActions(columnName = 'actions') {
    return `
        <th>${columnName}</th>
    `.trim();
}

function createTableActionButtons(row) {
    return `
        <td>
            <a href="/${row.path}/edit" class="btn btn-sm btn-secondary">Bearbeiten</a>
            <a href="/${row.path}" class="btn btn-sm btn-primary" target="_blank">Details</a>
            <button class="btn btn-sm btn-danger delete-btn" data-path="${row.path}">Löschen</button>
        </td>
    `;
}

// Initialize tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', (e) => {
            const text = tooltip.dataset.tooltip;
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'tooltip';
            tooltipEl.textContent = text;
            tooltipEl.style.cssText = `
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translate(-10%, -50%);
                background: #333;
                color: white;
                padding: 0.25rem 0.5rem;
                border-radius: 4px;
                font-size: 0.8rem;
                white-space: nowrap;
                z-index: 1000;
            `;
            
            tooltip.parentElement.appendChild(tooltipEl);
            
            tooltip.addEventListener('mouseleave', () => {
                tooltipEl.remove();
            });
        });
    });
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        
        cols.forEach(col => {
            let text = col.textContent.trim();
            text = text.replace(/"/g, '""'); // Escape quotes
            rowData.push(`"${text}"`);
        });
        
        csv.push(rowData.join(','));
    });
    
    // Download CSV
    const csvContent = "\uFEFF" + csv.join('\n'); // UTF-8 BOM
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', filename || 'export.csv');
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Print page
function printPage() {
    window.print();
}

// Format table rows
function formatTableRows(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.querySelectorAll('tr');
    rows.forEach((row, index) => {
        if (index % 2 === 0) {
            row.style.backgroundColor = '#ffffff';
        } else {
            row.style.backgroundColor = '#f8f9fa';
        }
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    formatTableRows('dataTable');
});
