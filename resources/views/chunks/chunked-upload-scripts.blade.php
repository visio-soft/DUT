<!-- Chunked Upload Scripts -->
<script>
// Initialize chunked upload for all Filament forms when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof FilamentChunkedUpload !== 'undefined') {
        FilamentChunkedUpload.initializeForAllForms();
    }
});

// Re-initialize when Livewire updates the page
document.addEventListener('livewire:navigated', function() {
    if (typeof FilamentChunkedUpload !== 'undefined') {
        FilamentChunkedUpload.initializeForAllForms();
    }
});

// Also handle Livewire component updates
if (typeof Livewire !== 'undefined') {
    Livewire.hook('component.initialized', (component) => {
        setTimeout(() => {
            if (typeof FilamentChunkedUpload !== 'undefined') {
                FilamentChunkedUpload.initializeForAllForms();
            }
        }, 100);
    });
}
</script>

<script src="{{ asset('js/chunked-upload.js') }}" defer></script>
