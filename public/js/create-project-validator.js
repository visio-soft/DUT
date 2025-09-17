// Create Project Form Validator - Global Function Approach
window.checkProjectForm = function(buttonElement, wireInstance) {
    const requiredFields = [
        'data.category_id', 
        'data.title', 
        'data.description', 
        'data.start_date', 
        'data.end_date', 
        'data.budget', 
        'data.district', 
        'data.image'
    ];
    
    const checkFields = () => {
        let allFilled = true;
        
        // Main field check
        requiredFields.forEach(field => {
            try {
                const value = wireInstance.get(field);
                if (!value || (Array.isArray(value) && value.length === 0)) {
                    allFilled = false;
                }
            } catch (e) {
                allFilled = false;
            }
        });
        
        // Neighborhood check
        try {
            const neighborhood = wireInstance.get('data.neighborhood');
            const neighborhoodCustom = wireInstance.get('data.neighborhood_custom');
            if (!neighborhood || (neighborhood === '__other' && !neighborhoodCustom)) {
                allFilled = false;
            }
        } catch (e) {
            allFilled = false;
        }
        
        // Enable/disable button
        buttonElement.disabled = !allFilled;
        
        console.log('Form validation result:', allFilled);
    };
    
    // Initial check
    checkFields();
    
    // Listen for Livewire form changes
    try {
        wireInstance.on('refresh', checkFields);
    } catch (e) {
        console.log('Livewire event listener error:', e);
    }
    
    // Periodic check
    setInterval(checkFields, 1000);
    
    console.log('Project form validator initialized');
};
