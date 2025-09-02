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
        
        // Ana alanları kontrol et
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
        
        // Mahalle kontrolü
        try {
            const neighborhood = wireInstance.get('data.neighborhood');
            const neighborhoodCustom = wireInstance.get('data.neighborhood_custom');
            if (!neighborhood || (neighborhood === '__other' && !neighborhoodCustom)) {
                allFilled = false;
            }
        } catch (e) {
            allFilled = false;
        }
        
        // Button'u disable/enable et
        buttonElement.disabled = !allFilled;
        
        console.log('Form validation result:', allFilled);
    };
    
    // İlk kontrolü yap
    checkFields();
    
    // Livewire form değişikliklerini dinle
    try {
        wireInstance.on('refresh', checkFields);
    } catch (e) {
        console.log('Livewire event listener error:', e);
    }
    
    // Periyodik kontrol
    setInterval(checkFields, 1000);
    
    console.log('Project form validator initialized');
};
