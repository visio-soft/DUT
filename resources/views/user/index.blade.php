@extends('user.layout')

@section('title', 'Ana Sayfa - DUT Vote')

@section('content')
<!-- Hero Section -->
<section class="user-hero dynamic-background {{ ($hasBackgroundImages ?? false) ? '' : 'no-background-images' }}">
    @if($hasBackgroundImages ?? false)
        @if($randomBackgroundImage)
            <!-- Single Random Background Image -->
            <div class="background-image-container">
                <img src="{{ $randomBackgroundImage }}"
                     alt="Şehri Birlikte Dönüştürelim"
                     class="background-image-main"
                     loading="eager">
            </div>
        @endif
        <div class="background-image-overlay"></div>
    @endif

    <div class="user-container">
        <div class="user-hero-content">
            <h1>Şehri Birlikte <span style="color: var(--green-700);">Dönüştürelim</span></h1>
            <p>
                Şehrimizin daha güzel, daha yaşanabilir olması için projeler oluşturun,
                öneriler paylaşın ve en iyi fikirleri birlikte belirleyin.
            </p>
            <div class="user-hero-actions">
                <a href="{{ route('user.projects') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                    </svg>
                    Projeleri Keşfet
                </a>
                <a href="#projects" class="btn btn-secondary">
                    Daha fazla bilgi
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Section -->
<section id="projects" class="section-padding-lg">
    <div class="user-container">
        <div class="text-center content-spacing-xl">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Öne Çıkan Projeler</h2>
            <p style="font-size: 1.125rem; color: var(--gray-600); max-width: 600px; margin: 0 auto;">
                Şehrimizi dönüştürecek en popüler projeler ve yaratıcı öneriler
            </p>
        </div>

        @if($randomProjects->count() > 0)
            <div class="featured-projects-grid count-{{ min($randomProjects->count(), 3) }} content-spacing-xl">
                @foreach($randomProjects->take(3) as $project)
                    <div class="project-card">
                        <!-- Project Image -->
                        <div class="project-image-container">
                            @if($project->getFirstMediaUrl('project_files'))
                                <img src="{{ $project->getFirstMediaUrl('project_files') }}"
                                     alt="{{ $project->name }}"
                                     class="project-image"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=&quot;image-placeholder&quot;><svg class=&quot;placeholder-icon&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p>Proje Görseli</p></div>';">
                            @else
                                <div class="image-placeholder">
                                    <svg class="placeholder-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                    </svg>
                                    <p>Proje Görseli</p>
                                </div>
                            @endif
                        </div>

                        <!-- Project Content -->
                        <div class="project-content">
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <p class="project-description">{{ Str::limit($project->description, 120) }}</p>

                            <!-- Project Stats -->
                            <div class="project-stats">
                                <div class="project-stat">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.25A2.25 2.25 0 0 0 18.75 6H16.5a2.25 2.25 0 0 1-2.25-2.25V3.75a2.25 2.25 0 0 0-2.25-2.25Z"/>
                                    </svg>
                                    {{ $project->oneriler->count() }} Öneri
                                </div>
                                @if($project->start_datetime)
                                <div class="project-stat">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                    {{ $project->start_datetime->format('d.m.Y') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Actions -->
                        <div class="project-actions">
                            <a href="{{ route('user.projects') }}#project-{{ $project->id }}" class="btn btn-primary">
                                Projeyi İncele
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center section-padding">
                <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                    <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem;">Henüz proje bulunmuyor</h3>
                    <p style="color: var(--gray-500);">İlk projelerin eklenmesi bekleniyor.</p>
                </div>
            </div>
        @endif

        @if($randomProjects->count() > 0)
        <div class="text-center">
            <a href="{{ route('user.projects') }}" class="btn btn-secondary">
                Tüm Projeleri Görüntüle
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
