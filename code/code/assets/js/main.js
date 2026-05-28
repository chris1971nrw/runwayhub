/**
 * RunwayHub - JavaScript
 * Virtual Airline Management System
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initLazyImages();
        initSmoothScroll();
        initMobileMenu();
        initSearch();
        initAnalytics();
    });

    /**
     * Initialize lazy loading for images
     */
    function initLazyImages() {
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(function(img) {
            const oldSrc = img.getAttribute('data-src');
            img.onload = function() {
                img.src = oldSrc;
                img.removeAttribute('data-src');
            };
            img.addEventListener('load', function() {
                img.src = oldSrc;
                img.removeAttribute('data-src');
            });
        });
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    /**
     * Mobile menu toggle
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.mobile-menu');

        if (menuToggle && menu) {
            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('active');
                this.textContent = menu.classList.contains('active') ? '✕' : '☰';
            });
        }
    }

    /**
     * Initialize search functionality
     */
    function initSearch() {
        const searchInput = document.querySelector('.search-input');
        const searchResults = document.querySelector('.search-results');

        if (searchInput && searchResults) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value.trim();

                if (query.length > 2) {
                    timeout = setTimeout(function() {
                        performSearch(query);
                    }, 300);
                } else {
                    searchResults.classList.remove('active');
                }
            });
        }
    }

    /**
     * Perform search (placeholder - replace with actual search implementation)
     */
    function performSearch(query) {
        // Placeholder implementation
        console.log('Searching:', query);
        // Add your search API integration here
    }

    /**
     * Initialize analytics tracking
     */
    function initAnalytics() {
        // GA4 or other analytics
        if (window.gtag) {
            window.gtag('config', 'GA_MEASUREMENT_ID', {
                send_page_view: true
            });
        }
    }

    /**
     * Track page view
     */
    function trackPageView(page, category, action, label) {
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label,
                page: page
            });
        }
    }

    /**
     * Format currency
     */
    function formatCurrency(amount, currency = 'EUR') {
        return new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    /**
     * Format date
     */
    function formatDate(date, options = {}) {
        return new Intl.DateTimeFormat('de-DE', options).format(new Date(date));
    }

    /**
     * Debounce function
     */
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

    /**
     * Throttle function
     */
    function throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Copy to clipboard
     */
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Success
        }).catch(function(err) {
            console.error('Copy failed:', err);
        });
    }

    /**
     * Confirm action
     */
    function confirmAction(message, callback) {
        if (window.confirm(message)) {
            callback();
        }
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(function() {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Make functions globally available
    window.RW = {
        formatCurrency,
        formatDate,
        debounce,
        throttle,
        copyToClipboard,
        confirmAction,
        showToast,
        trackPageView
    };

})();