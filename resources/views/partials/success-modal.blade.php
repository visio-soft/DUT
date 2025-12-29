<!-- Simple Success Modal -->
<div id="success-modal" style="display: none; position: fixed; inset: 0; z-index: 100000; align-items: center; justify-content: center;">
    <!-- Backdrop -->
    <div id="success-modal-backdrop" style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;"></div>

    <!-- Modal Content -->
    <div id="success-modal-content" style="position: relative; background: white; border-radius: 1.5rem; padding: 2.5rem; width: 100%; max-width: 400px; margin: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: scale(0.95); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <!-- Animated Icon -->
        <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; background: var(--green-50, #ecfdf5); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg class="checkmark-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 3rem; height: 3rem; stroke: var(--green-600, #059669); stroke-width: 4; fill: none; stroke-linecap: round; stroke-linejoin: round; display: block; margin: 0 auto;">
                <circle class="checkmark__circle-success" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check-success" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <!-- Text -->
        <h3 id="success-modal-title" style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900, #111827); text-align: center; margin-bottom: 0.5rem; font-family: inherit;">{{ __('common.thank_you') }}</h3>
        <p id="success-modal-message" style="font-size: 1rem; color: var(--gray-500, #6b7280); text-align: center; line-height: 1.5; margin-bottom: 2rem; font-family: inherit;">{{ __('common.feedback_received') }}</p>

        <!-- Button -->
        <button onclick="closeSuccessModal()" style="width: 100%; padding: 0.875rem; background: var(--green-600, #059669); color: white; border: none; border-radius: 0.75rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='var(--green-700, #047857)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.background='var(--green-600, #059669)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
            {{ __('common.ok') }}
        </button>
    </div>
</div>

<style>
    /* SVG Animation Styles */
    .checkmark__circle-success {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: var(--green-600, #059669);
        fill: none;
    }

    .checkmark__check-success {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
    }

    .animate-circle {
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .animate-check {
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
</style>

<script>
    let modalTimer;

    function showSuccessModal(title, message) {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('success-modal-backdrop');
        const content = document.getElementById('success-modal-content');
        const circle = document.querySelector('.checkmark__circle-success');
        const check = document.querySelector('.checkmark__check-success');
        
        if (title) document.getElementById('success-modal-title').textContent = title;
        if (message) document.getElementById('success-modal-message').textContent = message;

        modal.style.display = 'flex';
        
        // Trigger entrance animation
        setTimeout(() => {
            backdrop.style.opacity = '1';
            content.style.opacity = '1';
            content.style.transform = 'scale(1)';
            
            // Trigger SVG animation
            if (circle && check) {
                circle.classList.remove('animate-circle');
                check.classList.remove('animate-check');
                void circle.offsetWidth; // trigger reflow
                circle.classList.add('animate-circle');
                check.classList.add('animate-check');
            }
        }, 10);

        // Auto close
        if (modalTimer) clearTimeout(modalTimer);
        modalTimer = setTimeout(closeSuccessModal, 3000);
    }

    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        const backdrop = document.getElementById('success-modal-backdrop');
        const content = document.getElementById('success-modal-content');

        if (!modal) return;

        backdrop.style.opacity = '0';
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Listen for survey completion
    window.addEventListener('survey-completed', event => {
        showSuccessModal(event.detail.title, event.detail.message);
    });
</script>
