@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ __('common.create_project') }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="category_id" class="form-label">{{ __('common.category') }}</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">{{ __('common.select_option') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">{{ __('common.title') }}</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('common.description') }}</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">{{ __('common.location') }}</label>
            <input type="text" name="location" id="location" class="form-control">
        </div>
        <div class="mb-3">
            <label for="budget" class="form-label">{{ __('common.budget') }}</label>
            <input type="number" name="budget" id="budget" class="form-control" step="0.01">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">{{ __('common.image') }}</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
    </form>
</div>
@endsection
