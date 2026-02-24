@extends('layouts.app')

@section('title', 'Edit Book — Library System')

@section('content')
<div class="page-header d-flex align-items-center gap-2 flex-wrap">
    <nav aria-label="breadcrumb" class="mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Books</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}" class="text-decoration-none">{{ $book->title }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>
<h2 class="h5 fw-bold mb-3"><i class="bi bi-pencil me-1"></i>Edit Book</h2>

@php
    $currentCategory = old('category', $book->category);
    $listCategories = collect($categoryOptions)->flatten();
    $isOtherCategory = $currentCategory && !$listCategories->contains($currentCategory);
@endphp
<div class="card table-card" style="max-width: 420px;">
    <div class="card-body p-3">
        <form method="POST" action="{{ route('books.update', $book) }}" id="book-form">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" name="title" value="{{ old('title', $book->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Authors</label>
                <select name="author_ids[]" class="form-select form-select-sm" multiple size="3">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ $book->authors->contains($author) ? 'selected' : '' }}>{{ $author->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                <input type="text" class="form-control form-control-sm mt-1" name="author_new" value="{{ old('author_new') }}" placeholder="Or add new author (name)">
                @error('author_new')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select form-control-sm @error('category') is-invalid @enderror" id="category-select" required>
                    <option value="">Select category</option>
                    @foreach($categoryOptions as $group => $opts)
                        <optgroup label="{{ $group }}">
                            @foreach($opts as $label)
                                <option value="{{ $label }}" {{ $currentCategory === $label ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                    <option value="__other__" {{ $isOtherCategory ? 'selected' : '' }}>Other</option>
                </select>
                <input type="text" class="form-control form-control-sm mt-1 {{ $isOtherCategory ? '' : 'd-none' }}" name="category_other" id="category-other" value="{{ $isOtherCategory ? $currentCategory : old('category_other') }}" placeholder="Type category">
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('category_other')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Description</label>
                <textarea class="form-control form-control-sm" name="description" rows="2">{{ old('description', $book->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Copies <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-sm @error('copies') is-invalid @enderror" name="copies" value="{{ old('copies', $book->copies) }}" min="0" required style="max-width: 80px;">
                @error('copies')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
            <a href="{{ route('books.show', $book) }}" class="btn btn-light btn-sm">Cancel</a>
        </form>
    </div>
</div>
<script>
document.getElementById('category-select').addEventListener('change', function() {
    var other = document.getElementById('category-other');
    if (this.value === '__other__') {
        other.classList.remove('d-none');
        other.required = true;
    } else {
        other.classList.add('d-none');
        other.required = false;
        other.value = '';
    }
});
if (document.getElementById('category-select').value === '__other__') {
    document.getElementById('category-other').classList.remove('d-none');
    document.getElementById('category-other').required = true;
}
</script>
@endsection
