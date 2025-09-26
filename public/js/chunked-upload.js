/**
 * Chunked File Upload for Large Files
 * Bu script büyük dosyaları küçük parçalara bölerek yükler
 */

class ChunkedUploader {
    constructor(options = {}) {
        this.chunkSize = options.chunkSize || 2 * 1024 * 1024; // 2MB chunks
        this.uploadUrl = options.uploadUrl || '/api/chunked-upload';
        this.maxRetries = options.maxRetries || 3;
        this.onProgress = options.onProgress || (() => {});
        this.onSuccess = options.onSuccess || (() => {});
        this.onError = options.onError || (() => {});

        // Get CSRF token
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        || document.querySelector('input[name="_token"]')?.value;
    }

    async uploadFile(file, additionalData = {}) {
        if (!file) {
            this.onError('No file provided');
            return;
        }

        const totalChunks = Math.ceil(file.size / this.chunkSize);
        const uploadId = this.generateUploadId();

        console.log(`Starting chunked upload: ${file.name} (${file.size} bytes, ${totalChunks} chunks)`);

        try {
            // Initialize upload session
            const sessionResponse = await this.initializeUpload(file, uploadId, additionalData);

            if (!sessionResponse.success) {
                throw new Error(sessionResponse.message || 'Failed to initialize upload');
            }

            // Upload chunks sequentially
            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                await this.uploadChunk(file, chunkIndex, totalChunks, uploadId);

                const progress = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                this.onProgress(progress, chunkIndex + 1, totalChunks);
            }

            // Finalize upload
            const finalResponse = await this.finalizeUpload(uploadId);

            if (finalResponse.success) {
                this.onSuccess(finalResponse);
            } else {
                throw new Error(finalResponse.message || 'Failed to finalize upload');
            }

        } catch (error) {
            console.error('Chunked upload failed:', error);
            this.onError(error.message || 'Upload failed');
        }
    }

    async initializeUpload(file, uploadId, additionalData) {
        const formData = new FormData();
        formData.append('action', 'init');
        formData.append('upload_id', uploadId);
        formData.append('filename', file.name);
        formData.append('filesize', file.size);
        formData.append('filetype', file.type);
        formData.append('total_chunks', Math.ceil(file.size / this.chunkSize));

        // Add CSRF token
        if (this.csrfToken) {
            formData.append('_token', this.csrfToken);
        }

        // Add any additional metadata
        if (additionalData) {
            Object.keys(additionalData).forEach(key => {
                formData.append(key, additionalData[key]);
            });
        }

        const response = await fetch(this.uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        return await response.json();
    }

    async uploadChunk(file, chunkIndex, totalChunks, uploadId) {
        const start = chunkIndex * this.chunkSize;
        const end = Math.min(start + this.chunkSize, file.size);
        const chunk = file.slice(start, end);

        const formData = new FormData();
        formData.append('action', 'chunk');
        formData.append('upload_id', uploadId);
        formData.append('chunk_index', chunkIndex);
        formData.append('total_chunks', totalChunks);
        formData.append('chunk', chunk);

        // Add CSRF token
        if (this.csrfToken) {
            formData.append('_token', this.csrfToken);
        }

        let retries = 0;
        while (retries < this.maxRetries) {
            try {
                const response = await fetch(this.uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || `Chunk ${chunkIndex} upload failed`);
                }

                return result;
            } catch (error) {
                retries++;
                if (retries >= this.maxRetries) {
                    throw error;
                }

                console.log(`Retrying chunk ${chunkIndex}, attempt ${retries + 1}`);
                await this.delay(1000 * retries); // Progressive delay
            }
        }
    }

    async finalizeUpload(uploadId) {
        const formData = new FormData();
        formData.append('action', 'finalize');
        formData.append('upload_id', uploadId);

        // Add CSRF token
        if (this.csrfToken) {
            formData.append('_token', this.csrfToken);
        }

        const response = await fetch(this.uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        return await response.json();
    }

    generateUploadId() {
        return 'upload_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

// Filament integration helper
window.FilamentChunkedUpload = {
    initializeForField: function(fieldId, options = {}) {
        const fileInput = document.querySelector(`input[id*="${fieldId}"]`);

        if (!fileInput) {
            console.error(`File input not found for field: ${fieldId}`);
            return;
        }

        const uploader = new ChunkedUploader({
            ...options,
            onProgress: (progress, current, total) => {
                console.log(`Upload progress: ${progress}% (${current}/${total})`);

                // Update Filament progress indicator if available
                const progressBar = document.querySelector(`[data-field="${fieldId}"] .progress-bar`);
                if (progressBar) {
                    progressBar.style.width = `${progress}%`;
                }
            },
            onSuccess: (response) => {
                console.log('Upload successful:', response);

                // Trigger Filament to update the field
                if (window.Livewire) {
                    window.Livewire.emit('chunked-upload-complete', {
                        field: fieldId,
                        file_id: response.file_id,
                        file_url: response.file_url
                    });
                }
            },
            onError: (error) => {
                console.error('Upload failed:', error);
                alert(`Upload failed: ${error}`);
            }
        });

        // Handle file selection
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (!file) return;

            // Check file size - if larger than 1.5MB, use chunked upload (safety margin under 2MB PHP limit)
            if (file.size > 1.5 * 1024 * 1024) { // 1.5MB
                console.log('Large file detected, using chunked upload');

                uploader.uploadFile(file, {
                    model_type: options.modelType || 'App\\Models\\Oneri',
                    collection: options.collection || 'images',
                    field_name: fieldId
                });

                // Clear the file input to prevent normal upload
                event.target.value = '';
            }
        });

        return uploader;
    }
};

// Auto-initialize for Filament
document.addEventListener('DOMContentLoaded', function() {
    // Auto-detect Filament file upload fields and enable chunked upload for large files
    const fileUploads = document.querySelectorAll('input[type="file"]');

    fileUploads.forEach(input => {
        const fieldId = input.getAttribute('id') || input.getAttribute('name');
        if (fieldId && fieldId.includes('images')) {
            window.FilamentChunkedUpload.initializeForField(fieldId, {
                modelType: 'App\\Models\\Oneri',
                collection: 'images'
            });
        }
    });
});
