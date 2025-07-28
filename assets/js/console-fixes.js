// Enhanced modal handling for console error fixes
// This script fixes accessibility and focus issues with Bootstrap modals

(function() {
    'use strict';
    
    // Fix for modal focus issues
    document.addEventListener('DOMContentLoaded', function() {
        // Handle modal events to fix focus and accessibility issues
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(function(modal) {
            // Fix aria-hidden attribute when modal is shown
            modal.addEventListener('shown.bs.modal', function() {
                this.removeAttribute('aria-hidden');
                // Ensure proper focus management
                const firstFocusable = this.querySelector('input, select, textarea, button:not([data-bs-dismiss])');
                if (firstFocusable) {
                    setTimeout(() => firstFocusable.focus(), 100);
                }
            });
            
            // Restore aria-hidden when modal is hidden
            modal.addEventListener('hidden.bs.modal', function() {
                this.setAttribute('aria-hidden', 'true');
                // Clear any retained focus
                if (document.activeElement === this) {
                    document.activeElement.blur();
                }
            });
            
            // Fix for backdrop click issues
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    const modalInstance = bootstrap.Modal.getInstance(this);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        });
    });
    
    // Enhanced error handling for AJAX requests
    window.handleAjaxError = function(xhr, status, error, context = '') {
        console.error('AJAX Error in ' + context + ':', {
            status: status,
            error: error,
            responseText: xhr.responseText ? xhr.responseText.substring(0, 200) : 'No response',
            statusCode: xhr.status,
            url: xhr.responseURL || 'Unknown URL'
        });
        
        // Try to parse error response
        let errorMessage = 'An error occurred';
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.message) {
                errorMessage = response.message;
            }
        } catch (e) {
            // If not JSON, extract meaningful error from HTML
            if (xhr.responseText.includes('Fatal error')) {
                errorMessage = 'Server configuration error';
            } else if (xhr.responseText.includes('Parse error')) {
                errorMessage = 'Server script error';
            } else if (xhr.status === 500) {
                errorMessage = 'Internal server error';
            } else if (xhr.status === 404) {
                errorMessage = 'Resource not found';
            }
        }
        
        return errorMessage;
    };
    
    // Enhanced fetch wrapper with better error handling
    window.safeFetch = function(url, options = {}) {
        console.log('Fetching:', url, options);
        
        return fetch(url, options)
            .then(response => {
                console.log('Response status:', response.status, 'for', url);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.text().then(text => {
                    console.log('Raw response length:', text.length, 'for', url);
                    
                    // Check if response looks like JSON
                    if (text.trim().startsWith('{') || text.trim().startsWith('[')) {
                        try {
                            const data = JSON.parse(text);
                            console.log('Parsed JSON successfully for', url);
                            return data;
                        } catch (e) {
                            console.error('JSON parse error for', url, ':', e);
                            console.log('Response text:', text.substring(0, 500));
                            throw new Error('Invalid JSON response');
                        }
                    } else {
                        console.error('Response does not look like JSON for', url);
                        console.log('Response preview:', text.substring(0, 200));
                        throw new Error('Non-JSON response received');
                    }
                });
            })
            .catch(error => {
                console.error('Fetch error for', url, ':', error);
                throw error;
            });
    };
    
})();
