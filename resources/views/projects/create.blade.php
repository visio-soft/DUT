@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Proje Ekle</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Seçiniz</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Konum</label>
            <input type="text" name="location" id="location" class="form-control">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="min_budget" class="form-label">Minimum Bütçe</label>
                    <input type="number" name="min_budget" id="min_budget" class="form-control" step="0.01">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="max_budget" class="form-label">Maksimum Bütçe</label>
                    <input type="number" name="max_budget" id="max_budget" class="form-control" step="0.01">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Resim</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
</div>
@endsection
