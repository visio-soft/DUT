@extends('user.layout')

@section('title', $project->name . ' Önerileri - DUT Vote')

@section('content')
<!-- Header Section -->
<section class="section-padding" style="padding: 3rem 0; background: #f8fafc;">
    <div class="user-container">
        <!-- Page Header -->
        <div class="text-center" style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <a href="{{ route('user.projects') }}"
                   style="background: var(--green-100); border: 1px solid var(--green-300); color: var(--green-700); padding: 0.75rem; border-radius: 50%; margin-right: 1rem; text-decoration: none; transition: all 0.2s; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <svg style="width: 3rem; height: 3rem; margin-right: 1rem; color: var(--green-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                </svg>
                <div style="text-align: left;">
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--gray-900); margin: 0;">{{ $project->name }}</h1>
                    <p style="font-size: 1.125rem; color: var(--gray-600); margin: 0.5rem 0 0;">Proje Önerileri</p>
                </div>
            </div>

            @php
                $totalSuggestions = $project->oneriler->count();
                $totalLikes = $project->oneriler->sum(function($suggestion) {
                    return $suggestion->likes->count();
                });
                $totalComments = $project->oneriler->sum(function($suggestion) {
                    return $suggestion->comments->count();
                });
            @endphp

            @if($totalSuggestions > 0)
            <!-- Statistics Cards -->
            <div style="display: flex; justify-content: center; gap: 2rem; max-width: 800px; margin: 0 auto;">
                <!-- Total Suggestions Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--blue-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--blue-700); margin: 0 0 0.5rem 0;">{{ $totalSuggestions }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Öneri</p>
                </div>

                <!-- Total Likes Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--red-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--red-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--red-600); margin: 0 0 0.5rem 0;">{{ $totalLikes }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Beğeni</p>
                </div>

                <!-- Total Comments Card -->
                <div style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <div style="background: var(--yellow-100); border-radius: 50%; padding: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 2rem; height: 2rem; color: var(--yellow-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                            </svg>
                        </div>
                    </div>
                    <h3 style="font-size: 2.5rem; font-weight: 700; color: var(--yellow-600); margin: 0 0 0.5rem 0;">{{ $totalComments }}</h3>
                    <p style="font-size: 1rem; color: var(--gray-600); margin: 0; font-weight: 500;">Toplam Yorum</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<div class="section-padding">
    <div class="user-container">

        @if($project->oneriler->count() > 0)
        <div class="d-grid" style="grid-template-columns: 1fr 3fr; gap: 2rem;">
            <!-- Sol Taraf: Suggestion List -->
            <div>
                <div style="position: sticky; top: 2rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                        </svg>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Öneri Listesi</h3>
                    </div>

                    <!-- Filter Options -->
                    <!-- Info about voting system -->
                    <div style="background: var(--green-50); border: 1px solid var(--green-200); border-radius: var(--radius-md); padding: 0.75rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: start; gap: 0.5rem;">
                            <svg style="width: 1rem; height: 1rem; color: var(--green-600); margin-top: 0.125rem; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            <div>
                                <p style="font-size: 0.75rem; color: var(--green-700); margin: 0; line-height: 1.4; font-weight: 500;">
                                    <strong>Oylama:</strong> Bu proje kategorisinde sadece bir öneri için oy kullanabilirsiniz.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tablo Başlığı -->
                    <div style="display: grid; grid-template-columns: 2fr 80px 80px; gap: 0.5rem; padding: 0.75rem; background: var(--gray-100); border-radius: var(--radius-md); margin-bottom: 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--gray-700);">
                        <div>Öneri Adı</div>
                        <div style="text-align: center;">❤️ Beğeni</div>
                        <div style="text-align: center;">💬 Yorum</div>
                    </div>
                    
                    <div id="suggestionsList" style="max-height: 60vh; overflow-y: auto; border: 1px solid var(--gray-200); border-radius: var(--radius-md);">
                        @foreach($project->oneriler->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $suggestion)
                        <div class="suggestion-item" 
                             data-id="{{ $suggestion->id }}"
                             data-likes="{{ $suggestion->likes->count() }}"
                             data-comments="{{ $suggestion->comments->count() }}"
                             data-created="{{ $suggestion->created_at->timestamp }}"
                             style="display: grid; grid-template-columns: 2fr 80px 80px; gap: 0.5rem; padding: 0.75rem; border-bottom: 1px solid var(--gray-200); background: white; transition: all 0.2s; cursor: pointer;"
                             onclick="scrollToSuggestion({{ $suggestion->id }})"
                             onmouseenter="this.style.background='var(--blue-50)'"
                             onmouseleave="this.style.background='white'">
                            
                            <!-- Öneri Adı -->
                            <div style="min-width: 0;">
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.25rem; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $suggestion->title }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--gray-500); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $suggestion->createdBy->name ?? 'Anonim' }}
                                </div>
                            </div>

                            <!-- Beğeni Sayısı -->
                            <div style="text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                @if(Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0)
                                <span style="color: var(--red-500); font-size: 0.875rem; font-weight: 600;">{{ $suggestion->likes->count() }}</span>
                                @else
                                <span style="color: var(--gray-600); font-size: 0.875rem; font-weight: 500;">{{ $suggestion->likes->count() }}</span>
                                @endif
                            </div>

                            <!-- Yorum Sayısı -->
                            <div style="text-align: center; display: flex; align-items: center; justify-content: center;">
                                <span style="color: var(--blue-600); font-size: 0.875rem; font-weight: 500;">{{ $suggestion->comments->count() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf: Suggestion Cards -->
            <div>
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    @foreach($project->oneriler->sortByDesc(function($suggestion) { return $suggestion->likes->count(); }) as $index => $suggestion)
                    <div id="suggestion-{{ $suggestion->id }}" class="user-card" style="
                        overflow: hidden; 
                        position: relative; 
                        min-height: 400px;
                        border-radius: var(--radius-lg);
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                        background: var(--gray-100);
                    ">
                        <!-- Suggestion Background Image -->
                        @php
                            $suggestionImage = null;
                            $imageSource = 'none';
                            $debugInfo = [];
                            
                            // Debug: Suggestion model bilgileri
                            $debugInfo[] = "Suggestion ID: " . $suggestion->id;
                            $debugInfo[] = "Suggestion Title: " . $suggestion->title;
                            
                            // 1. Önce media library'den dene
                            $mediaUrl = $suggestion->getFirstMediaUrl('images');
                            $debugInfo[] = "Media Library URL: " . ($mediaUrl ?: 'empty');
                            
                            if($mediaUrl) {
                                $suggestionImage = $mediaUrl;
                                $imageSource = 'media_library';
                                $debugInfo[] = "✓ Using Media Library image";
                            }
                            
                            // 2. Media library'den tüm resimleri kontrol et
                            if(!$suggestionImage) {
                                $allMedia = $suggestion->getMedia('images');
                                $debugInfo[] = "All media count: " . $allMedia->count();
                                
                                if($allMedia->count() > 0) {
                                    $firstMedia = $allMedia->first();
                                    $suggestionImage = $firstMedia->getUrl();
                                    $imageSource = 'media_library_all';
                                    $debugInfo[] = "✓ Using Media Library (all): " . $firstMedia->file_name;
                                    $debugInfo[] = "Media exists on disk: " . (file_exists($firstMedia->getPath()) ? 'YES' : 'NO');
                                }
                            }
                            
                            // 3. Proje resmini kullan
                            if(!$suggestionImage && $project->image) {
                                $suggestionImage = asset('storage/' . $project->image);
                                $imageSource = 'project_image';
                                $debugInfo[] = "✓ Using Project image: " . $project->image;
                                
                                // Proje dosyası var mı kontrol et
                                $projectFilePath = storage_path('app/public/' . $project->image);
                                $debugInfo[] = "Project file exists: " . (file_exists($projectFilePath) ? 'YES' : 'NO');
                            }
                            
                            // 4. Son çare: default resim
                            if(!$suggestionImage) {
                                $defaultImages = [
                                    'images/default-suggestion.jpg',
                                    'images/default-project.jpg',
                                    'images/placeholder.jpg'
                                ];
                                
                                foreach($defaultImages as $defaultImg) {
                                    $defaultPath = public_path($defaultImg);
                                    if(file_exists($defaultPath)) {
                                        $suggestionImage = asset($defaultImg);
                                        $imageSource = 'default';
                                        $debugInfo[] = "✓ Using default image: " . $defaultImg;
                                        break;
                                    }
                                }
                            }
                            
                            $debugInfo[] = "Final image source: " . $imageSource;
                            $debugInfo[] = "Final image URL: " . ($suggestionImage ?: 'null');
                            
                            // Log to Laravel log
                            \Log::info('Suggestion Image Debug', [
                                'suggestion_id' => $suggestion->id,
                                'image_source' => $imageSource,
                                'final_url' => $suggestionImage,
                                'debug_info' => $debugInfo
                            ]);
                        @endphp
                        
                        <!-- Debug info (sadece development'ta göster) -->
                        @if(config('app.debug'))
                        <div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 0.5rem; border-radius: 4px; font-size: 10px; z-index: 10; max-width: 200px;">
                            <strong>Debug Info:</strong><br>
                            @foreach($debugInfo as $info)
                                {{ $info }}<br>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Background Layer -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
                            @if($suggestionImage)
                                <img src="{{ $suggestionImage }}"
                                     alt="{{ $suggestion->title }}"
                                     style="
                                        width: 100%; 
                                        height: 100%; 
                                        object-fit: cover; 
                                        object-position: center;
                                        filter: brightness(0.4) saturate(1.2);
                                        transition: filter 0.3s ease;
                                     "
                                     onload="console.log('✅ Image loaded:', '{{ $suggestionImage }}'); this.style.filter='brightness(0.4) saturate(1.2)'"
                                     onerror="console.error('❌ Image load failed:', '{{ $suggestionImage }}'); this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%)';">
                            @else
                                <!-- Gradient Background when no image -->
                                <div style="
                                    width: 100%; 
                                    height: 100%; 
                                    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%);
                                    position: relative;
                                ">
                                    <!-- Pattern overlay for texture -->
                                    <div style="
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        right: 0;
                                        bottom: 0;
                                        background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 1px, transparent 1px);
                                        background-size: 20px 20px;
                                        opacity: 0.3;
                                    "></div>
                                </div>
                            @endif
                        </div>

                        <!-- Gradient Overlay for better text readability -->
                        <div style="
                            position: absolute; 
                            top: 0; 
                            left: 0; 
                            right: 0; 
                            bottom: 0; 
                            background: linear-gradient(
                                135deg,
                                rgba(0,0,0,0.3) 0%,
                                rgba(0,0,0,0.1) 40%,
                                rgba(0,0,0,0.4) 100%
                            );
                            z-index: 2;
                        "></div>

                        <!-- Suggestion Content -->
                        <div class="suggestion-card" 
                             data-id="{{ $suggestion->id }}"
                             data-likes="{{ $suggestion->likes->count() }}"
                             data-comments="{{ $suggestion->comments->count() }}"
                             data-created="{{ $suggestion->created_at->timestamp }}"
                             style="
                                position: absolute; 
                                top: 0; 
                                left: 0; 
                                right: 0; 
                                bottom: 0; 
                                z-index: 3; 
                                padding: 2rem; 
                                color: white;
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                             ">

                            <!-- Top Content -->
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                    <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem; color: rgba(255,255,255,0.9);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    <h2 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.7); line-height: 1.2;">{{ $suggestion->title }}</h2>
                                </div>

                                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                                    <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: rgba(255,255,255,0.8);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <span style="font-size: 0.875rem; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                        {{ $suggestion->createdBy->name ?? 'Anonim' }}
                                    </span>
                                </div>

                                @if($suggestion->description)
                                <div style="
                                    background: rgba(255,255,255,0.1); 
                                    backdrop-filter: blur(10px);
                                    border-radius: 0.75rem; 
                                    padding: 1.25rem; 
                                    margin-bottom: 1.5rem;
                                    border: 1px solid rgba(255,255,255,0.2);
                                ">
                                    <p style="font-size: 1rem; color: rgba(255,255,255,0.95); margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.3); line-height: 1.6;">
                                        {{ Str::limit($suggestion->description, 200) }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            <!-- Bottom Content - Stats & Actions -->
                            <div style="
                                background: rgba(0,0,0,0.3); 
                                backdrop-filter: blur(15px);
                                border-radius: 0.75rem; 
                                padding: 1.25rem;
                                border: 1px solid rgba(255,255,255,0.1);
                            ">
                                <!-- Stats Row -->
                                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem; font-weight: 500;">
                                        <svg style="width: 1rem; height: 1rem; color: #ef4444;" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                        </svg>
                                        {{ $suggestion->likes->count() }} Beğeni
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.875rem; font-weight: 500;">
                                        <svg style="width: 1rem; height: 1rem; color: #3b82f6;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.20-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                                        </svg>
                                        {{ $suggestion->comments->count() }} Yorum
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.75rem;">
                                        <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                        </svg>
                                        {{ $suggestion->created_at->format('d.m.Y') }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                                    <!-- Like Button -->
                                    <button onclick="toggleLike({{ $suggestion->id }})"
                                            class="btn-like {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}"
                                            data-suggestion-id="{{ $suggestion->id }}"
                                            data-project-id="{{ $project->id }}"
                                            title="Bu proje kategorisinde sadece bir öneri beğenilebilir"
                                            style="
                                                background: {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 'rgba(255,255,255,0.15)' }}; 
                                                border: 1px solid {{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? '#dc2626' : 'rgba(255,255,255,0.3)' }}; 
                                                color: white; 
                                                padding: 0.75rem 1.25rem; 
                                                border-radius: 0.5rem; 
                                                font-size: 0.875rem; 
                                                font-weight: 500;
                                                display: flex; 
                                                align-items: center; 
                                                gap: 0.5rem; 
                                                transition: all 0.3s ease; 
                                                backdrop-filter: blur(10px); 
                                                cursor: pointer;
                                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                            "
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.2)';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">

                                        <svg style="width: 1rem; height: 1rem;" fill="{{ Auth::check() && $suggestion->likes->where('user_id', Auth::id())->count() > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                        </svg>
                                        <span class="like-count">{{ $suggestion->likes->count() }}</span>
                                    </button>

                                    <!-- Details Button -->
                                    <a href="{{ route('user.suggestion.detail', $suggestion->id) }}"
                                       style="
                                           background: rgba(255,255,255,0.95); 
                                           color: #1e40af; 
                                           padding: 0.75rem 1.25rem; 
                                           border-radius: 0.5rem; 
                                           font-size: 0.875rem; 
                                           font-weight: 600; 
                                           text-decoration: none; 
                                           transition: all 0.3s ease; 
                                           backdrop-filter: blur(10px); 
                                           border: 1px solid rgba(255,255,255,0.5); 
                                           display: flex; 
                                           align-items: center; 
                                           gap: 0.5rem;
                                           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                       "
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.2)'; this.style.background='white';"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'; this.style.background='rgba(255,255,255,0.95)';">
                                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                        Detay
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for interactions -->
        @else
        <!-- Empty State -->
        <div class="text-center section-padding-lg">
            <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--blue-400);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                </svg>
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--blue-600);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin: 0;">Bu proje için öneri bulunmuyor</h3>
                </div>
                <p style="color: var(--gray-500);">
                    <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem; color: var(--blue-500);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                    </svg>
                    İlk önerilerin eklenmesi bekleniyor.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for interactions -->
<script>
// Set up CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Scroll to suggestion
function scrollToSuggestion(suggestionId) {
    const element = document.getElementById('suggestion-' + suggestionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        element.style.border = '2px solid var(--blue-500)';
        element.style.borderOpacity = '0.5';
        setTimeout(() => {
            element.style.border = '1px solid var(--blue-100)';
        }, 2000);
    }
}



// Toggle like with AJAX (Radio button logic: one per project category)
function toggleLike(suggestionId) {
    @guest
        showMessage('Beğeni yapmak için giriş yapmanız gerekiyor.', 'error');
        setTimeout(() => {
            window.location.href = '{{ route('user.login') }}';
        }, 2000);
        return;
    @endguest

    const clickedButton = document.querySelector(`[data-suggestion-id="${suggestionId}"]`);
    const likeCount = clickedButton.querySelector('.like-count');

    // Find the project container to get category context
    const projectContainer = document.querySelector('.user-container');
    const projectId = {{ $project->id }};

    // Disable all buttons in this project during request
    const allButtonsInProject = document.querySelectorAll('.btn-like');
    allButtonsInProject.forEach(btn => {
        btn.disabled = true;
        btn.classList.add('loading');
    });

    $.ajax({
        url: `/suggestions/${suggestionId}/toggle-like`,
        method: 'POST',
        success: function(response) {
            // Update like counts for all suggestions in this project
            allButtonsInProject.forEach(btn => {
                btn.classList.remove('liked');
                const btnSuggestionId = btn.getAttribute('data-suggestion-id');

                // Reset button appearance to default (not liked)
                btn.style.background = 'rgba(255,255,255,0.15)';
                btn.style.borderColor = 'rgba(255,255,255,0.3)';

                // Reset heart icon to outline
                const heartIcon = btn.querySelector('svg');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                // Update like count from server response if available
                if (response.all_likes && response.all_likes[btnSuggestionId] !== undefined) {
                    const btnLikeCount = btn.querySelector('.like-count');
                    if (btnLikeCount) {
                        btnLikeCount.textContent = response.all_likes[btnSuggestionId];
                    }
                }
            });

            // Update clicked button's like count
            likeCount.textContent = response.likes_count;

            // Update clicked button appearance
            if (response.liked) {
                clickedButton.classList.add('liked');

                // Update button to red (liked state)
                clickedButton.style.background = '#ef4444';
                clickedButton.style.borderColor = '#dc2626';

                // Fill heart icon
                const heartIcon = clickedButton.querySelector('svg');
                if (heartIcon) {
                    heartIcon.style.fill = 'currentColor';
                }

                showMessage('✓ Bu proje kategorisindeki seçiminiz güncellendi!', 'success');
            } else {
                clickedButton.classList.remove('liked');

                // Reset button to default (not liked state)
                clickedButton.style.background = 'rgba(255,255,255,0.15)';
                clickedButton.style.borderColor = 'rgba(255,255,255,0.3)';

                // Reset heart icon to outline
                const heartIcon = clickedButton.querySelector('svg');
                if (heartIcon) {
                    heartIcon.style.fill = 'none';
                }

                showMessage('Beğeni kaldırıldı.', 'info');
            }

            // Add visual feedback for radio button behavior
            if (response.liked && response.switched_from) {
                showMessage(`Seçiminiz "${response.switched_from}" önerisinden bu öneriye değiştirildi.`, 'info');
            }

            // Update sidebar like counts and indicators
            updateSidebarStats();
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            showMessage(message, 'error');
        },
        complete: function() {
            // Re-enable all buttons
            allButtonsInProject.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
        }
    });
}

// Update sidebar statistics and indicators
function updateSidebarStats() {
    // Update like counts in sidebar
    const allCards = document.querySelectorAll('[id^="suggestion-"]');
    allCards.forEach(card => {
        const suggestionId = card.id.replace('suggestion-', '');
        const mainLikeCount = card.querySelector('.like-count');
        const sidebarItem = document.querySelector(`[onclick="scrollToSuggestion(${suggestionId})"]`);

        if (mainLikeCount && sidebarItem) {
            const sidebarLikeCount = sidebarItem.querySelector('span:last-child');
            if (sidebarLikeCount) {
                sidebarLikeCount.textContent = mainLikeCount.textContent + ' beğeni';
            }
        }
    });
}

// Show message function with enhanced styling for like system
function showMessage(message, type = 'info') {
    // Remove any existing messages first
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());

    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;

    // Add appropriate icon based on message type
    const icons = {
        success: '✓',
        error: '✗',
        info: 'ℹ'
    };

    const icon = icons[type] || 'ℹ';
    messageDiv.innerHTML = `<span style="margin-right: 0.5rem; font-weight: bold;">${icon}</span>${message}`;

    // Position it better for mobile
    messageDiv.style.cssText = `
        position: fixed;
        top: 1rem;
        right: 1rem;
        left: 1rem;
        max-width: 400px;
        margin: 0 auto;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        backdrop-filter: blur(8px);
    `;

    // Apply type-specific styling
    switch(type) {
        case 'success':
            messageDiv.style.background = 'linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%)';
            break;
        case 'error':
            messageDiv.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            break;
        case 'info':
            messageDiv.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
            break;
    }

    document.body.appendChild(messageDiv);

    // Auto remove after delay based on message length
    const delay = Math.max(3000, message.length * 50);
    setTimeout(() => {
        messageDiv.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => messageDiv.remove(), 300);
    }, delay);
}

// Add CSS for message animations
if (!document.getElementById('message-styles')) {
    const style = document.createElement('style');
    style.id = 'message-styles';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateY(-100%) translateX(-50%);
                opacity: 0;
            }
            to {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
            to {
                transform: translateY(-100%) translateX(-50%);
                opacity: 0;
            }
        }
        .message {
            transform: translateX(-50%);
        }

        /* Mobile responsiveness */
        @media (max-width: 1024px) {
            .d-grid[style*="grid-template-columns: 1fr 3fr"] {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }

        @media (max-width: 768px) {
            .section-padding {
                padding: 2rem 0 !important;
            }

            .user-container {
                padding: 0 1rem !important;
            }
        }

        @media (min-width: 640px) {
            .message {
                right: 1rem !important;
                left: auto !important;
                max-width: 400px !important;
                transform: none !important;
            }
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        }
    `;
    document.head.appendChild(style);
}

// Add hover effects for like buttons
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.btn-like');
    likeButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('liked')) {
                this.style.background = 'rgba(255,255,255,0.25)';
                this.style.borderColor = 'rgba(255,255,255,0.5)';
            } else {
                this.style.background = '#dc2626';
                this.style.borderColor = '#b91c1c';
            }
        });

        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('liked')) {
                this.style.background = 'rgba(255,255,255,0.15)';
                this.style.borderColor = 'rgba(255,255,255,0.3)';
            } else {
                this.style.background = '#ef4444';
                this.style.borderColor = '#dc2626';
            }
        });
    });

    // Add hover effects for back button
    const backButton = document.querySelector('a[href="{{ route('user.projects') }}"]');
    if (backButton) {
        backButton.addEventListener('mouseenter', function() {
            this.style.background = 'var(--green-200)';
            this.style.borderColor = 'var(--green-400)';
            this.style.color = 'var(--green-800)';
        });

        backButton.addEventListener('mouseleave', function() {
            this.style.background = 'var(--green-100)';
            this.style.borderColor = 'var(--green-300)';
            this.style.color = 'var(--green-700)';
        });
    }

    // Responsive grid adjustment
    function adjustLayout() {
        const container = document.querySelector('[style*="grid-template-columns: 1fr 3fr"]');
        if (container && window.innerWidth < 1024) {
            container.style.gridTemplateColumns = '1fr';
            container.style.gap = '1rem';
        } else if (container) {
            container.style.gridTemplateColumns = '1fr 3fr';
            container.style.gap = '2rem';
        }

        // Adjust stats cards for mobile
        const statsContainer = document.querySelector('[style*="display: flex; justify-content: center; gap: 2rem"]');
        if (statsContainer && window.innerWidth < 768) {
            statsContainer.style.flexDirection = 'column';
            statsContainer.style.gap = '1rem';
            statsContainer.style.maxWidth = '400px';
        } else if (statsContainer) {
            statsContainer.style.flexDirection = 'row';
            statsContainer.style.gap = '2rem';
            statsContainer.style.maxWidth = '800px';
        }
    }

    // Call on load and resize
    adjustLayout();
    window.addEventListener('resize', adjustLayout);
});
</script>

@endsection
