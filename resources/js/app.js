/**
 * ============================================================
 * BUYPROPERTY KENYA - MASTER JAVASCRIPT
 * ============================================================
 * 
 * TABLE OF CONTENTS:
 * 1.  Alpine.js Components
 * 2.  Image Slider
 * 3.  Navigation Components
 * 4.  Filter Components
 * 5.  Utility Functions
 * 6.  Event Handlers
 * ============================================================
 */

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

// Register Alpine plugins
Alpine.plugin(focus);
Alpine.plugin(collapse);

/**
 * ============================================================
 * 1. IMAGE SLIDER COMPONENT
 * ============================================================
 * 
 * A full-featured image slider with:
 * - Auto-play with configurable interval
 * - Manual navigation (previous/next)
 * - Dot indicators
 * - Progress bar
 * - Ken Burns zoom effect
 * 
 * Usage: x-data="imageSlider(propertiesArray)"
 * ============================================================
 */
Alpine.data('imageSlider', (properties) => ({
    /** @type {Array} Array of property objects for the slider */
    properties: properties || [],
    
    /** @type {number} Current active slide index */
    currentSlide: 0,
    
    /** @type {number|null} Auto-play interval reference */
    autoplayInterval: null,
    
    /** @type {string} Progress bar width percentage */
    progressWidth: '0%',
    
    /** @type {number} Time between slides in milliseconds */
    intervalMs: 5000,

    /**
     * Initialize the slider
     * - Starts autoplay if more than 1 slide
     * - Shows single slide if only 1 exists
     */
    init() {
        console.log('Slider initialized with', this.properties.length, 'properties');
        if (this.properties.length > 1) {
            this.startAutoplay();
        } else if (this.properties.length === 1) {
            this.currentSlide = 0;
            this.progressWidth = '100%';
        }
    },

    /**
     * Navigate to a specific slide index
     * @param {number} index - The slide index to navigate to
     */
    goToSlide(index) {
        if (index < 0 || index >= this.properties.length) return;
        this.currentSlide = index;
        this.resetAutoplay();
    },

    /**
     * Navigate to the next slide (loops back to start)
     */
    nextSlide() {
        if (this.properties.length === 0) return;
        this.currentSlide = (this.currentSlide + 1) % this.properties.length;
        this.resetAutoplay();
    },

    /**
     * Navigate to the previous slide (loops back to end)
     */
    prevSlide() {
        if (this.properties.length === 0) return;
        this.currentSlide = (this.currentSlide - 1 + this.properties.length) % this.properties.length;
        this.resetAutoplay();
    },

    /**
     * Start autoplay with progress bar animation
     */
    startAutoplay() {
        this.resetProgressBar();
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
        }
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.intervalMs);
    },

    /**
     * Reset autoplay timer and restart
     */
    resetAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
        if (this.properties.length > 1) {
            this.resetProgressBar();
            this.startAutoplay();
        }
    },

    /**
     * Reset progress bar and animate to full width
     */
    resetProgressBar() {
        this.progressWidth = '0%';
        setTimeout(() => {
            this.progressWidth = '100%';
        }, 50);
    }
}));

/**
 * ============================================================
 * 2. NAVIGATION COMPONENT
 * ============================================================
 * 
 * Handles mobile menu toggle, search overlay, and user dropdown
 * 
 * Usage: x-data="navigation()"
 * ============================================================
 */
Alpine.data('navigation', () => ({
    /** @type {boolean} Mobile menu open state */
    mobileMenuOpen: false,
    
    /** @type {boolean} Search overlay open state */
    searchOpen: false,
    
    /** @type {boolean} User dropdown open state */
    userOpen: false,

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu() {
        this.mobileMenuOpen = !this.mobileMenuOpen;
    },

    /**
     * Open search overlay
     */
    openSearch() {
        this.searchOpen = true;
    },

    /**
     * Close search overlay
     */
    closeSearch() {
        this.searchOpen = false;
    },

    /**
     * Toggle user dropdown
     */
    toggleUserDropdown() {
        this.userOpen = !this.userOpen;
    },

    /**
     * Close all menus
     */
    closeAll() {
        this.mobileMenuOpen = false;
        this.searchOpen = false;
        this.userOpen = false;
    }
}));

/**
 * ============================================================
 * 3. FILTER COMPONENT
 * ============================================================
 * 
 * Manages active filters for property search
 * 
 * Usage: x-data="propertyFilter()"
 * ============================================================
 */
Alpine.data('propertyFilter', () => ({
    /** @type {Array} Active filter objects */
    activeFilters: [],

    /**
     * Initialize and update active filters
     */
    init() {
        this.updateActiveFilters();
    },

    /**
     * Extract active filters from URL query parameters
     */
    updateActiveFilters() {
        this.activeFilters = [];
        const params = new URLSearchParams(window.location.search);
        
        const filters = [
            { key: 'location', label: 'Location: ' },
            { key: 'property_type', label: 'Type: ' },
            { key: 'listing_type', label: 'Listing: ' },
            { key: 'max_price', label: 'Max Price: ' }
        ];
        
        filters.forEach(filter => {
            const value = params.get(filter.key);
            if (value) {
                // Format price values
                let displayValue = value;
                if (filter.key === 'max_price') {
                    displayValue = 'KSh ' + Number(value).toLocaleString();
                }
                this.activeFilters.push({
                    key: filter.key,
                    label: filter.label + displayValue
                });
            }
        });
    },

    /**
     * Check if there are any active filters
     * @returns {boolean}
     */
    hasActiveFilters() {
        return this.activeFilters.length > 0;
    },

    /**
     * Remove a specific filter and reload page
     * @param {string} key - The filter key to remove
     */
    removeFilter(key) {
        const params = new URLSearchParams(window.location.search);
        params.delete(key);
        window.location.href = window.location.pathname + '?' + params.toString();
    },

    /**
     * Reset all filters and reload page
     */
    resetFilters() {
        window.location.href = window.location.pathname;
    }
}));

/**
 * ============================================================
 * 4. UTILITY FUNCTIONS
 * ============================================================
 */

/**
 * Format a number as currency (KES)
 * @param {number} amount - The amount to format
 * @returns {string} Formatted currency string
 */
window.formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString();
};

/**
 * Format a date to a readable string
 * @param {string|Date} date - The date to format
 * @returns {string} Formatted date string
 */
window.formatDate = (date) => {
    const d = new Date(date);
    return d.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    });
};

/**
 * Truncate text to a specified length
 * @param {string} text - The text to truncate
 * @param {number} length - Maximum length
 * @returns {string} Truncated text
 */
window.truncateText = (text, length = 100) => {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};

/**
 * Smooth scroll to an element
 * @param {string} selector - CSS selector of the target element
 */
window.smoothScroll = (selector) => {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

/**
 * ============================================================
 * 5. EVENT HANDLERS
 * ============================================================
 */

/**
 * Handle escape key to close overlays
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        // Close search overlay if open
        const searchOverlay = document.querySelector('[x-data]');
        if (searchOverlay && searchOverlay.__x) {
            const data = searchOverlay.__x.$data;
            if (data.searchOpen) {
                data.searchOpen = false;
            }
        }
    }
});

/**
 * Handle click outside to close dropdowns
 */
document.addEventListener('click', (e) => {
    // Dropdowns are handled by Alpine.js @click.away
    // This is just a fallback
});

/**
 * ============================================================
 * 6. DOM READY HANDLER
 * ============================================================
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log(' BuyProperty Kenya - Application initialized');
    console.log('Environment:', import.meta.env.MODE || 'development');
});

/**
 * ============================================================
 * END OF JAVASCRIPT
 * ============================================================
 */

// Make Alpine globally available
window.Alpine = Alpine;

// Start Alpine
Alpine.start();