// Specific fixes for console errors in manage-renewal.php

// Fix for TypeError: Cannot read properties of undefined (reading 'id')
(function() {
    'use strict';
    
    // Override the global edit policy function to add better error handling
    window.editPolicyWithValidation = function(policyId) {
        console.log('Edit policy function called with ID:', policyId);
        
        // Validate policy ID
        if (!policyId || isNaN(policyId) || policyId <= 0) {
            console.error('Invalid policy ID:', policyId);
            alert('Error: Invalid policy ID');
            return false;
        }
        
        // Check if modal exists
        const modal = document.getElementById('editPolicyModal');
        if (!modal) {
            console.error('Edit policy modal not found');
            alert('Error: Modal not available');
            return false;
        }
        
        // Check if form exists
        const form = document.getElementById('editPolicyForm');
        if (!form) {
            console.error('Edit policy form not found');
            alert('Error: Form not available');
            return false;
        }
        
        // Show modal first
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
        
        // Show loading state
        form.style.opacity = '0.5';
        
        // Use the safe fetch method
        const url = `include/get-policy-data-ultra-clean.php?id=${policyId}`;
        console.log('Fetching policy data from:', url);
        
        fetch(url)
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.text();
            })
            .then(text => {
                console.log('Raw response received, length:', text.length);
                console.log('Response preview:', text.substring(0, 200));
                
                // Check if it's JSON
                if (!text.trim() || (!text.trim().startsWith('{') && !text.trim().startsWith('['))) {
                    throw new Error('Response is not JSON: ' + text.substring(0, 100));
                }
                
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.log('Problematic text:', text);
                    throw new Error('Invalid JSON in response');
                }
            })
            .then(data => {
                console.log('Data parsed successfully:', data);
                
                // Validate response structure
                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid response format');
                }
                
                if (data.success === false) {
                    throw new Error(data.message || 'API returned error');
                }
                
                if (!data.data) {
                    throw new Error('No policy data in response');
                }
                
                const policy = data.data;
                console.log('Policy data extracted:', policy);
                
                // Validate policy has required fields
                if (!policy.id) {
                    throw new Error('Policy data missing ID field');
                }
                
                // Populate form safely
                populateEditFormSafely(policy);
                
                // Remove loading state
                form.style.opacity = '1';
                
                console.log('Policy edit form loaded successfully');
                
            })
            .catch(error => {
                console.error('Error in editPolicyWithValidation:', error);
                
                // Remove loading state
                form.style.opacity = '1';
                
                // Show user-friendly error
                let errorMessage = 'Failed to load policy data. ';
                if (error.message.includes('JSON')) {
                    errorMessage += 'Server response format error.';
                } else if (error.message.includes('HTTP')) {
                    errorMessage += 'Server connection error.';
                } else {
                    errorMessage += error.message;
                }
                
                alert(errorMessage + ' Please try again.');
                
                // Hide modal on error
                modalInstance.hide();
            });
        
        return true;
    };
    
    // Safe form population function
    function populateEditFormSafely(policy) {
        console.log('Populating form with policy:', policy);
        
        const fieldMappings = {
            'edit_policy_id': 'id',
            'edit_vehicle_number': 'vehicle_number',
            'edit_phone': 'phone',
            'edit_name': 'name',
            'edit_vehicle_type': 'vehicle_type',
            'edit_insurance_company': 'insurance_company',
            'edit_policy_type': 'policy_type',
            'edit_policy_start_date': 'policy_start_date',
            'edit_policy_end_date': 'policy_end_date',
            'edit_premium': 'premium',
            'edit_payout': 'payout',
            'edit_customer_paid': 'customer_paid',
            'edit_comments': 'comments'
        };
        
        let errors = [];
        
        for (const [fieldId, policyProperty] of Object.entries(fieldMappings)) {
            try {
                const element = document.getElementById(fieldId);
                if (element) {
                    const value = policy[policyProperty] || '';
                    element.value = value;
                    console.log(`Set ${fieldId} = ${value}`);
                } else {
                    errors.push(`Field ${fieldId} not found`);
                }
            } catch (error) {
                errors.push(`Error setting ${fieldId}: ${error.message}`);
            }
        }
        
        if (errors.length > 0) {
            console.warn('Form population warnings:', errors);
        }
        
        // Call financial calculation if available
        if (typeof calculateEditFinancials === 'function') {
            try {
                calculateEditFinancials();
            } catch (e) {
                console.warn('Error in calculateEditFinancials:', e);
            }
        }
    }
    
    // Override global editPolicy function if it exists
    if (typeof window.editPolicy === 'function') {
        console.log('Overriding existing editPolicy function');
        window.editPolicy = window.editPolicyWithValidation;
    }
    
    console.log('Enhanced edit policy validation loaded');
    
})();
