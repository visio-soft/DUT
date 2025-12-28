@auth
    <div x-data="{ 
            open: false, 
            unreadCount: {{ auth()->user()->unreadNotifications()->where(function($query) {
                $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
            })->count() }},
            markAsRead() {
                fetch('{{ route('user.notifications.mark-read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    }
                }).then(() => {
                    this.unreadCount = 0;
                });
            }
        }" 
        class="relative flex items-center"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
    >
        <!-- Bell Icon -->
        <button @click="open = !open" class="relative p-2 text-[#053640] hover:text-[#1ABF6B] transition-colors duration-200 focus:outline-none">
            <svg class="w-6 h-6" :class="{ 'animate-wiggle': unreadCount > 0 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            
            <!-- Badge -->
            <span x-show="unreadCount > 0" 
                  x-transition.scale
                  class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white"
                  x-text="unreadCount"
                  style="display: none;">
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="absolute right-0 bottom-full md:bottom-auto md:top-full mb-2 md:mb-0 md:mt-2 z-50 w-80 max-w-[calc(100vw-2rem)] origin-bottom-right md:origin-top-right bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100"
             style="display: none;">
            
            <div class="px-3 py-2 border-b border-gray-100 flex justify-between items-center bg-[#f9fafb] rounded-t-xl">
                <div class="flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-[#053640]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="text-[10px] font-medium text-[#053640] uppercase tracking-wider">{{ __('common.notifications') }}</h3>
                </div>
                <button @click="markAsRead" x-show="unreadCount > 0" title="{{ __('common.mark_all_read') ?? 'Tümünü Okundu İşaretle' }}" class="flex items-center justify-center p-1.5 text-white bg-[#1ABF6B] hover:bg-[#16a559] rounded-full transition-all shadow-sm hover:shadow-md active:scale-95 focus:outline-none">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L7 17l-5-5"></path>
                        <path d="M22 10l-7.5 7.5L13 16"></path>
                    </svg>
                </button>
            </div>

            <div class="max-h-96 overflow-y-auto custom-scrollbar">
                @forelse(auth()->user()->unreadNotifications()->where(function($query) {
                    $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
                })->take(5)->get() as $notification)
                    <div class="p-4 border-b border-gray-50 hover:bg-[#f0f9f4] transition duration-150 ease-in-out relative group">
                        <a href="{{ $notification->data['action_url'] ?? '#' }}" class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#dcedc1] text-[#1ABF6B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-semibold text-[#053640]">
                                    {{ $notification->data['title'] ?? 'Bildirim' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit($notification->data['body'] ?? '', 100) }}
                                </p>
                                @if(isset($notification->data['action_label']) && isset($notification->data['action_url']))
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-[#053640] text-white hover:bg-[#074b59] transition-colors">
                                            {{ $notification->data['action_label'] }}
                                        </span>
                                    </div>
                                @endif
                                <p class="mt-1.5 text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">{{ __('common.no_notifications') ?? 'Yeni bildiriminiz yok' }}</p>
                    </div>
                @endforelse
            </div>
            
            @if(auth()->user()->unreadNotifications()->where(function($query) {
                $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
            })->count() > 5)
                <div class="p-3 border-t border-gray-100 text-center bg-[#f9fafb] rounded-b-xl">
                    <a href="#" class="text-xs font-bold text-[#1ABF6B] hover:text-[#16a559] transition-colors">
                        {{ __('common.view_all_notifications') ?? 'Tüm Bildirimleri Gör' }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        .animate-wiggle {
            animation: wiggle 1s ease-in-out infinite;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@endauth
