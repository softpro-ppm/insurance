// Universal Search Functionality for Softpro Insurance
// Enhanced with performance optimization and loading states

let searchTimeout;
let currentSearchRequest;

function performGlobalSearch() {
    const searchInput = document.getElementById('global-search');
    const query = searchInput.value.trim();
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Cancel previous request if still pending
    if (currentSearchRequest) {
        currentSearchRequest.abort();
    }
    
    // Clear results if query is too short
    if (query.length < 2) {
        hideSearchResults();
        return;
    }
    
    // Show loading state immediately
    showSearchLoading();
    
    // Debounce search to avoid too many requests
    searchTimeout = setTimeout(() => {
        executeSearch(query);
    }, 300);
}

function executeSearch(query) {
    // Create AbortController for request cancellation
    const controller = new AbortController();
    currentSearchRequest = controller;
    
    const formData = new FormData();
    formData.append('action', 'global_search');
    formData.append('query', query);
    
    fetch('include/global-search.php', {
        method: 'POST',
        body: formData,
        signal: controller.signal
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        hideSearchLoading();
        displaySearchResults(data);
    })
    .catch(error => {
        if (error.name !== 'AbortError') {
            console.error('Search error:', error);
            hideSearchLoading();
            showSearchError();
        }
    })
    .finally(() => {
        currentSearchRequest = null;
    });
}

function showSearchLoading() {
    const searchContainer = document.querySelector('.app-search .position-relative');
    
    // Remove existing results or error
    hideSearchResults();
    
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'search-loading';
    loadingDiv.className = 'search-results';
    loadingDiv.innerHTML = `
        <div class="search-result-item text-center py-3">
            <div class="loading-spinner me-2"></div>
            <span class="text-muted">Searching...</span>
        </div>
    `;
    
    searchContainer.appendChild(loadingDiv);
}

function hideSearchLoading() {
    const loadingDiv = document.getElementById('search-loading');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

function showSearchError() {
    const searchContainer = document.querySelector('.app-search .position-relative');
    
    const errorDiv = document.createElement('div');
    errorDiv.id = 'search-error';
    errorDiv.className = 'search-results';
    errorDiv.innerHTML = `
        <div class="search-result-item text-center py-3 text-danger">
            <i class="bx bx-error-circle me-2"></i>
            Search failed. Please try again.
        </div>
    `;
    
    searchContainer.appendChild(errorDiv);
    
    // Auto-hide error after 3 seconds
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

function displaySearchResults(data) {
    const searchContainer = document.querySelector('.app-search .position-relative');
    
    // Remove existing results
    hideSearchResults();
    
    if (!data.success) {
        showSearchError();
        return;
    }
    
    const results = data.results;
    const totalResults = results.policies.length + results.renewals.length + results.clients.length;
    
    if (totalResults === 0) {
        const noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'search-results';
        noResultsDiv.className = 'search-results';
        noResultsDiv.innerHTML = `
            <div class="search-result-item text-center py-3 text-muted">
                <i class="bx bx-search me-2"></i>
                No results found
            </div>
        `;
        searchContainer.appendChild(noResultsDiv);
        return;
    }
    
    const resultsDiv = document.createElement('div');
    resultsDiv.id = 'search-results';
    resultsDiv.className = 'search-results';
    
    let html = `
        <div class="search-result-item bg-light">
            <small class="text-muted fw-bold">Found ${totalResults} result(s)</small>
        </div>
    `;
    
    // Add Policies
    if (results.policies.length > 0) {
        html += `<div class="search-result-item bg-primary text-white fw-bold">
            <i class="bx bx-file-blank me-2"></i>Policies (${results.policies.length})
        </div>`;
        
        results.policies.forEach(policy => {
            html += `
                <div class="search-result-item" onclick="viewPolicy(${policy.id})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${policy.vehicle_number}</strong>
                            <br>
                            <small class="text-muted">${policy.name} - ${policy.insurance_company}</small>
                        </div>
                        <div class="text-end">
                            <small class="badge badge-primary">₹${formatNumber(policy.premium)}</small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    // Add Renewals
    if (results.renewals.length > 0) {
        html += `<div class="search-result-item bg-warning text-dark fw-bold">
            <i class="bx bx-refresh me-2"></i>Renewals (${results.renewals.length})
        </div>`;
        
        results.renewals.forEach(renewal => {
            const daysLeft = calculateDaysLeft(renewal.policy_end_date);
            const urgencyClass = daysLeft <= 7 ? 'badge-danger' : daysLeft <= 30 ? 'badge-warning' : 'badge-success';
            
            html += `
                <div class="search-result-item" onclick="viewRenewal(${renewal.id})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${renewal.vehicle_number}</strong>
                            <br>
                            <small class="text-muted">${renewal.name}</small>
                        </div>
                        <div class="text-end">
                            <small class="badge ${urgencyClass}">${daysLeft} days</small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    // Add Clients
    if (results.clients.length > 0) {
        html += `<div class="search-result-item bg-info text-white fw-bold">
            <i class="bx bx-user me-2"></i>Clients (${results.clients.length})
        </div>`;
        
        results.clients.forEach(client => {
            html += `
                <div class="search-result-item" onclick="viewClient('${client.phone}')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${client.name}</strong>
                            <br>
                            <small class="text-muted">${client.phone}</small>
                        </div>
                        <div class="text-end">
                            <small class="badge badge-info">${client.policy_count} policies</small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    resultsDiv.innerHTML = html;
    searchContainer.appendChild(resultsDiv);
    
    // Auto-hide after 10 seconds of no interaction
    setTimeout(() => {
        if (document.getElementById('search-results')) {
            hideSearchResults();
        }
    }, 10000);
}

function hideSearchResults() {
    const existingResults = document.getElementById('search-results');
    const existingLoading = document.getElementById('search-loading');
    const existingError = document.getElementById('search-error');
    
    if (existingResults) existingResults.remove();
    if (existingLoading) existingLoading.remove();
    if (existingError) existingError.remove();
}

// Utility Functions
function formatNumber(num) {
    return new Intl.NumberFormat('en-IN').format(num);
}

function calculateDaysLeft(endDate) {
    const today = new Date();
    const end = new Date(endDate);
    const diffTime = end - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

// Navigation Functions
function viewPolicy(policyId) {
    hideSearchResults();
    // Open policy modal or navigate to policy page
    if (typeof showPolicyModal === 'function') {
        showPolicyModal(policyId);
    } else {
        window.location.href = `policies.php?view=${policyId}`;
    }
}

function viewRenewal(policyId) {
    hideSearchResults();
    window.location.href = `manage-renewal.php?policy=${policyId}`;
}

function viewClient(phone) {
    hideSearchResults();
    window.location.href = `policies.php?client=${phone}`;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    
    if (searchInput) {
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.app-search')) {
                hideSearchResults();
            }
        });
        
        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const results = document.getElementById('search-results');
            if (!results) return;
            
            const items = results.querySelectorAll('.search-result-item[onclick]');
            const current = results.querySelector('.search-result-item.active');
            let index = Array.from(items).indexOf(current);
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (current) current.classList.remove('active');
                    index = (index + 1) % items.length;
                    items[index].classList.add('active');
                    items[index].scrollIntoView({ block: 'nearest' });
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if (current) current.classList.remove('active');
                    index = index <= 0 ? items.length - 1 : index - 1;
                    items[index].classList.add('active');
                    items[index].scrollIntoView({ block: 'nearest' });
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (current) {
                        current.click();
                    }
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    hideSearchResults();
                    searchInput.blur();
                    break;
            }
        });
        
        // Clear search
        searchInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                performGlobalSearch();
            }
        });
    }
});

// CSS for search result highlighting
const searchStyles = `
.search-result-item.active {
    background-color: var(--primary-color) !important;
    color: white !important;
}

.search-result-item.active .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.search-result-item.active .badge {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = searchStyles;
document.head.appendChild(styleSheet);
