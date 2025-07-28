// Test Input Focus in Add Policy Modal
// Copy and paste this into browser console after opening the modal

function testInputFocus() {
    console.log('=== TESTING INPUT FOCUS ===');
    
    // Check if modal is open
    const modal = document.getElementById('addPolicyModal');
    if (!modal) {
        console.error('Modal not found');
        return;
    }
    
    const isVisible = modal.classList.contains('show') || modal.style.display === 'block';
    console.log('Modal is visible:', isVisible);
    
    if (!isVisible) {
        console.log('Opening modal first...');
        $('#addPolicyModal').modal('show');
        setTimeout(testInputFocus, 1000);
        return;
    }
    
    // Find all inputs
    const inputs = modal.querySelectorAll('input, select, textarea');
    console.log('Found inputs:', inputs.length);
    
    inputs.forEach((input, index) => {
        console.log(`Input ${index + 1}:`, {
            name: input.name,
            id: input.id,
            type: input.type,
            disabled: input.disabled,
            readonly: input.readOnly,
            pointerEvents: getComputedStyle(input).pointerEvents,
            userSelect: getComputedStyle(input).userSelect,
            zIndex: getComputedStyle(input).zIndex,
            backgroundColor: getComputedStyle(input).backgroundColor
        });
        
        // Fix the input
        input.removeAttribute('readonly');
        input.removeAttribute('disabled');
        input.style.pointerEvents = 'auto';
        input.style.userSelect = 'text';
        input.style.backgroundColor = '#ffffff';
        input.style.zIndex = '1060';
        input.style.position = 'relative';
    });
    
    // Test focus on first input
    const firstInput = modal.querySelector('#modal_vehicle_number');
    if (firstInput) {
        console.log('Testing focus on vehicle number input...');
        firstInput.focus();
        
        setTimeout(() => {
            const isFocused = document.activeElement === firstInput;
            console.log('Input focused successfully:', isFocused);
            
            if (isFocused) {
                console.log('SUCCESS: Input is focusable and ready for typing');
            } else {
                console.log('FAILED: Input could not be focused');
                console.log('Active element:', document.activeElement);
            }
        }, 100);
    }
}

// Auto-run test
testInputFocus();
