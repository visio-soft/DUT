// JavaScript for handling image upload button state
document.addEventListener('DOMContentLoaded', function() {
    // Find the "Add Design" button
    const designButton = document.querySelector('[wire\\:click="mountAction(\'createAndDesign\')"]');

    if (designButton) {
        // Initial check
        checkImageAndUpdateButton();

        // Listen for form changes
        document.addEventListener('livewire:updated', function() {
            checkImageAndUpdateButton();
        });

        function checkImageAndUpdateButton() {
            // Find image input
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let hasImage = false;

            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    hasImage = true;
                }
            });

            // Update button state
            if (hasImage) {
                designButton.disabled = false;
                designButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                designButton.disabled = true;
                designButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }
});
