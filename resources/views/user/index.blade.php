@extends('user.layout')

@section('title', 'Ana Sayfa - Proje Paneli')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjA1Ij48Y2lyY2xlIGN4PSI0IiBjeT0iNCIgcj0iMiI+PC9jaXJjbGU+PC9nPjwvZz48L3N2Zz4='); opacity: 0.1;"></div>
    
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                Şehri Birlikte 
                <span class="text-yellow-400">Dönüştürelim</span>
            </h1>
            <p class="mt-6 text-lg leading-8 text-gray-200 max-w-3xl mx-auto">
                Şehrimizin daha güzel, daha yaşanabilir olması için projeler oluşturun, 
                öneriler paylaşın ve en iyi fikirleri birlikte belirleyin.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('user.projects') }}" 
                   class="rounded-lg bg-white px-6 py-3 text-base font-semibold text-gray-900 shadow-lg hover:bg-gray-50 transition duration-200 transform hover:scale-105">
                    Projeleri Keşfet
                </a>
                <a href="#projects" 
                   class="text-base font-semibold leading-6 text-white hover:text-gray-200 transition duration-200">
                    Daha fazla bilgi <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Section -->
<section id="projects" class="py-16 lg:py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Öne Çıkan Projeler
            </h2>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Şehrimizi dönüştürecek en popüler projeler ve yaratıcı öneriler
            </p>
        </div>

        @if($randomProjects->count() > 0)
            <div class="mt-12 grid gap-8 lg:gap-12">
                @foreach($randomProjects as $index => $project)
                    <div class="flex flex-col {{ $index % 2 == 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} items-center gap-8 lg:gap-12">
                        <!-- Project Content -->
                        <div class="flex-1">
                            <div class="max-w-xl">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                    {{ $project->name }}
                                </h3>
                                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                                    {{ Str::limit($project->description, 200) }}
                                </p>
                                
                                <!-- Project Stats -->
                                <div class="flex items-center gap-6 mb-6">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $project->oneriler->count() }} Öneri
                                    </div>
                                    @if($project->start_datetime)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $project->start_datetime->format('d.m.Y') }}
                                    </div>
                                    @endif
                                </div>
                                
                                <a href="{{ route('user.projects') }}#project-{{ $project->id }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                                    Projeyi İncele
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Project Image -->
                        <div class="flex-1">
                            <div class="aspect-video bg-gray-200 rounded-xl overflow-hidden shadow-lg">
                                @if($project->getFirstMediaUrl('images'))
                                    <img src="{{ $project->getFirstMediaUrl('images') }}" 
                                         alt="{{ $project->name }}"
                                         class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <p class="text-gray-500 text-sm">Proje Görseli</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-12 text-center">
                <div class="bg-gray-50 rounded-lg p-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz proje bulunmuyor</h3>
                    <p class="text-gray-500">İlk projelerin eklenmesi bekleniyor.</p>
                </div>
            </div>
        @endif
        
        @if($randomProjects->count() > 0)
        <div class="text-center mt-12">
            <a href="{{ route('user.projects') }}" 
               class="inline-flex items-center px-8 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                Tüm Projeleri Görüntüle
                <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection