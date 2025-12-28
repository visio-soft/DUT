@props(['show' => false, 'title' => 'Teşekkür Ederiz!', 'message' => 'İşleminiz başarıyla tamamlandı.'])

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75"
        @click="show = false"
    ></div>

    <!-- Modal -->
    <div
        x-show="show"
        class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-8 text-center"
    >
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-gray-900 mb-2">
            {{ $title }}
        </h3>

        <!-- Message -->
        <p class="text-gray-600 mb-6">
            {{ $message }}
        </p>

        <!-- Close Button -->
        <button
            @click="show = false"
            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-6 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out"
        >
            Tamam
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Auto-close after 5 seconds
    document.addEventListener('alpine:init', () => {
        if (@js($show)) {
            setTimeout(() => {
                Alpine.store('thankyou', { show: false });
            }, 5000);
        }
    });
</script>
@endpush
