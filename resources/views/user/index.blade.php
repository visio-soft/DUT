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
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    Projeleri Keşfet
                </a>
                <a href="#projects" class="btn btn-secondary">
                    Daha fazla bilgi
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
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
            <div class="featured-projects-grid count-{{ $randomProjects->count() }} content-spacing-xl">
                @foreach($randomProjects as $project)
                    <div class="project-card">
                        <!-- Project Image -->
                        <div style="height: 200px; overflow: hidden; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                            @if($project->getFirstMediaUrl('project_files'))
                                <img src="{{ $project->getFirstMediaUrl('project_files') }}"
                                     alt="{{ $project->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: var(--transition-normal);"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.parentElement.innerHTML='<div style=&quot;width: 100%; height: 100%; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center;&quot;><div style=&quot;text-align: center;&quot;><svg style=&quot;width: 3rem; height: 3rem; color: var(--green-600); margin-bottom: 0.5rem;&quot; fill=&quot;currentColor&quot; viewBox=&quot;0 0 20 20&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z&quot; clip-rule=&quot;evenodd&quot;/></svg><p style=&quot;color: var(--green-700); font-size: 0.875rem;&quot;>Proje Görseli</p></div></div>';">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--green-100) 0%, var(--green-200) 100%); display: flex; align-items: center; justify-content: center;">
                                    <div style="text-align: center;">
                                        <svg style="width: 3rem; height: 3rem; color: var(--green-600); margin-bottom: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        <p style="color: var(--green-700); font-size: 0.875rem;">Proje Görseli</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Project Content -->
                        <div class="project-header">
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <p class="project-description">{{ Str::limit($project->description, 150) }}</p>

                            <!-- Project Stats -->
                            <div class="project-stats">
                                <div class="project-stat">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $project->oneriler->count() }} Öneri
                                </div>
                                @if($project->start_datetime)
                                <div class="project-stat">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $project->start_datetime->format('d.m.Y') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Actions -->
                        <div style="padding: 1.5rem; border-top: 1px solid var(--green-100);">
                            <a href="{{ route('user.projects') }}#project-{{ $project->id }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                                Projeyi İncele
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center section-padding">
                <div class="user-card" style="max-width: 400px; margin: 0 auto; padding: 3rem;">
                    <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: var(--green-400);" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
