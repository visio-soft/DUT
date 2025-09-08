// JavaScript for handling image upload button state
document.addEventListener('DOMContentLoaded', function() {
    // Tasarım Ekle butonunu bul
    const designButton = document.querySelector('[wire\\:click="mountAction(\'createAndDesign\')"]');

    if (designButton) {
        // İlk kontrol
        checkImageAndUpdateButton();

        // Form değişikliklerini dinle
        document.addEventListener('livewire:updated', function() {
            checkImageAndUpdateButton();
        });

        function checkImageAndUpdateButton() {
            // Resim input'unu bul
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let hasImage = false;

            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    hasImage = true;
                }
            });

            // Button durumunu güncelle
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
