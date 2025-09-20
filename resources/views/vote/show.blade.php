@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            @if($suggestion->getFirstMediaUrl('images'))
                <img src="{{ $suggestion->getFirstMediaUrl('images') }}" class="w-full h-64 object-cover rounded-md" alt="suggestion">
            @endif
        </div>

        <h1 class="text-2xl font-bold mb-2">{{ $suggestion->title }}</h1>
        <div class="text-sm text-gray-500 mb-4">{{ $suggestion->address ?? ($suggestion->city . ' / ' . $suggestion->district) }}</div>

        <div class="prose max-w-none">{!! nl2br(e($suggestion->description)) !!}</div>

        <div class="mt-6 flex items-center space-x-3">
            @livewire(\App\Http\Livewire\SuggestionLike::class, ['suggestion' => $suggestion], $suggestion->id)
            <a href="/" class="text-sm text-gray-600">Geri</a>
        </div>
    </div>
</div>
@endsection
