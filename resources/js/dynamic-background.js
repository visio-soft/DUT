/**
 * Dynamic Background Image Handler
 * Handles simple single random background images for user panels
 */
class DynamicBackgroundManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupBackground();
        window.addEventListener('resize', () => this.handleResize());
    }

    setupBackground() {
        // Check if we have simple random background image
        if (window.backgroundImageData && window.backgroundImageData.hasImages && window.backgroundImageData.randomImage) {
            this.setupSimpleBackground();
        } else if (window.backgroundImageData && window.backgroundImageData.carouselSets) {
            // Fallback to carousel system if it exists
            this.setupCarouselBackground();
        } else {
            // Fallback to legacy single image system
            this.setupLegacyBackground();
        }
    }

    setupSimpleBackground() {
        const containers = document.querySelectorAll('.background-image-container');

        containers.forEach(container => {
            container.innerHTML = ''; // Clear existing content

            const img = document.createElement('img');
            img.src = window.backgroundImageData.randomImage;
            img.alt = 'Şehri Birlikte Dönüştürelim';
            img.className = 'background-image-main';
            img.loading = 'eager';

            container.appendChild(img);
            this.handleImageLoad(img, container);
        });
    }

    setupCarouselBackground() {
        // Check if carousel data is available
        if (window.backgroundImageData && window.backgroundImageData.carouselSets) {
            this.imageSets = window.backgroundImageData.carouselSets;
            this.createCarouselContainers();
            this.startImageRotation();
        } else {
            // Fallback to legacy single image system
            this.setupLegacyBackground();
        }
    }

    createCarouselContainers() {
        const containers = document.querySelectorAll('.carousel-background-container');

        containers.forEach(container => {
            container.innerHTML = ''; // Clear existing content

            const wrapper = document.createElement('div');
            wrapper.className = 'carousel-images-wrapper';

            // Create image sets
            this.imageSets.forEach((imageSet, setIndex) => {
                const setElement = document.createElement('div');
                setElement.className = `carousel-image-set ${setIndex === 0 ? 'active' : ''}`;

                imageSet.forEach((image, imageIndex) => {
                    const imageItem = document.createElement('div');
                    imageItem.className = 'carousel-image-item';

                    const img = document.createElement('img');
                    img.src = image.url;
                    img.alt = image.title || 'Öneri Resmi';
                    img.loading = 'lazy';

                    // Start flowing immediately, but keep a small stagger for visual variety
                    setTimeout(() => {
                        imageItem.classList.add('flowing');
                    }, imageIndex * 300);

                    imageItem.appendChild(img);
                    setElement.appendChild(imageItem);
                });

                wrapper.appendChild(setElement);
            });

            container.appendChild(wrapper);
        });
    }

    startImageRotation() {
        if (!this.imageSets || this.imageSets.length <= 1) return;

        // Keep rotation available but make it very infrequent so images appear to slide continuously
        // Default to 5 minutes to avoid frequent opacity flips (300000 ms)
        const rotationMs = 300000; // 5 minutes
        this.rotationInterval = setInterval(() => {
            this.rotateToNextSet();
        }, rotationMs);
    }

    rotateToNextSet() {
        const currentSets = document.querySelectorAll('.carousel-image-set');
        if (currentSets.length === 0) return;

        // Hide current set
        currentSets[this.currentSetIndex].classList.remove('active');

        // Move to next set
        this.currentSetIndex = (this.currentSetIndex + 1) % this.imageSets.length;

        // Show next set
        currentSets[this.currentSetIndex].classList.add('active');

        // Restart flowing animations for the new set
        const newItems = currentSets[this.currentSetIndex].querySelectorAll('.carousel-image-item');
        newItems.forEach((item, index) => {
            item.classList.remove('flowing');
            setTimeout(() => {
                item.classList.add('flowing');
            }, index * 2000);
        });
    }

    // Legacy background support
    setupLegacyBackground() {
        const backgroundContainers = document.querySelectorAll('.background-image-container');

        backgroundContainers.forEach(container => {
            const img = container.querySelector('.background-image-main');
            if (img) {
                this.handleImageLoad(img, container);
            }
        });
    }

    handleImageLoad(img, container) {
        if (img.complete && img.naturalHeight !== 0) {
            this.checkImageScale(img, container);
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', () => {
                this.checkImageScale(img, container);
                img.classList.add('loaded');
            });

            // Handle error case
            img.addEventListener('error', () => {
                console.warn('Background image failed to load:', img.src);
                container.style.display = 'none';
            });
        }
    }

    checkImageScale(img, container) {
        const containerWidth = container.offsetWidth;
        const containerHeight = container.offsetHeight;
        const imageAspectRatio = img.naturalWidth / img.naturalHeight;
        const containerAspectRatio = containerWidth / containerHeight;

        // If image is too narrow for the container, add blur effects
        if (imageAspectRatio < containerAspectRatio * 0.8) {
            this.enableBlurEffect(img, container);
        } else {
            this.disableBlurEffect(img, container);
        }
    }

    enableBlurEffect(img, container) {
        container.classList.add('needs-blur-effect');

        // Remove any existing blur elements
        this.removeBlurElements(container);

        // Create blur effect wrapper
        const blurWrapper = document.createElement('div');
        blurWrapper.className = 'background-with-blur';

        // Create blurred left side
        const blurLeft = document.createElement('div');
        blurLeft.className = 'background-blur-left';
        blurLeft.style.backgroundImage = `url("${img.src}")`;

        // Create blurred right side
        const blurRight = document.createElement('div');
        blurRight.className = 'background-blur-right';
        blurRight.style.backgroundImage = `url("${img.src}")`;

        // Create center image
        const centerImg = img.cloneNode(true);
        centerImg.className = 'background-center-image loaded';

        // Append elements
        blurWrapper.appendChild(blurLeft);
        blurWrapper.appendChild(blurRight);
        blurWrapper.appendChild(centerImg);

        // Replace original image
        img.style.display = 'none';
        container.appendChild(blurWrapper);
    }

    disableBlurEffect(img, container) {
        container.classList.remove('needs-blur-effect');
        this.removeBlurElements(container);
        img.style.display = 'block';
        img.className = 'background-image-main';
    }

    removeBlurElements(container) {
        const existingBlur = container.querySelector('.background-with-blur');
        if (existingBlur) {
            existingBlur.remove();
        }
    }

    handleResize() {
        // Debounce resize events
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            this.setupBackground();
        }, 250);
    }

    // Method to update with new images (for future AJAX updates)
    updateCarouselImages(newImageSets) {
        this.imageSets = newImageSets;
        this.currentSetIndex = 0;
        this.createCarouselContainers();
    }

    // Cleanup method
    destroy() {
        if (this.rotationInterval) {
            clearInterval(this.rotationInterval);
        }
        window.removeEventListener('resize', this.handleResize);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.backgroundManager = new DynamicBackgroundManager();
});

// Export for use in other scripts if needed
window.DynamicBackgroundManager = DynamicBackgroundManager;
