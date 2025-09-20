@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">DUT Voting Platform - Projeler</h1>

    @foreach($projects as $project)
        <div class="bg-white rounded-lg shadow p-5 mb-6">
            <div class="md:flex md:space-x-6">
                <div class="md:w-1/4 flex items-center justify-center">
                    @if($project->getFirstMediaUrl('images'))
                        <img src="{{ $project->getFirstMediaUrl('images') }}" class="rounded-md w-full object-cover" alt="project">
                    @else
                        <div class="bg-gray-100 rounded p-6 w-full text-center text-sm text-gray-500">No Image</div>
                    @endif
                </div>
                <div class="md:flex-1 mt-4 md:mt-0">
                    <h3 class="text-xl font-semibold">{{ $project->title }}</h3>
                    <div class="text-sm text-gray-500 mt-1">{{ $project->address ?? ($project->city . ' / ' . $project->district) }}</div>
                    <div class="mt-2 text-sm"><strong>Bütçe:</strong> {{ $project->budget ?? '-' }}</div>

                    <div class="mt-4">
                        <h5 class="font-medium mb-3">Öneriler</h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($project->suggestions as $s)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    @if($s->getFirstMediaUrl('images'))
                                        <img src="{{ $s->getFirstMediaUrl('images') }}" class="rounded mb-3 w-full h-36 object-cover" alt="suggestion">
                                    @endif
                                    <p class="text-sm text-gray-600">{{ Str::limit($s->description, 120) }}</p>

                                    <div class="flex items-center justify-between mt-3">
                                        @livewire(\App\Http\Livewire\SuggestionLike::class, ['suggestion' => $s], $s->id)
                                        <a class="text-sm text-indigo-600" href="{{ route('vote.show', $s) }}">Detay</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
