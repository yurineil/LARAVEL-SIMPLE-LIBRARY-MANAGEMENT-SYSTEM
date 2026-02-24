@extends('layouts.app')

@section('title', 'Add Book — Library System')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <nav aria-label="breadcrumb" class="mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Books</a></li>
            <li class="breadcrumb-item active">Add Book</li>
        </ol>
    </nav>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
</div>
<h2 class="h5 fw-bold mb-1"><i class="bi bi-plus-lg me-1"></i>Add Book</h2>
<p class="text-muted small mb-3">Pick authors from the list or add a new one below.</p>

<div class="card table-card" style="max-width: 420px;">
    <div class="card-body p-3">
        <form method="POST" action="{{ route('books.store') }}" id="book-form">
            @csrf
            <div class="mb-2">
                <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Authors</label>
                <select name="author_ids[]" class="form-select form-select-sm" multiple size="3">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>{{ $author->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                <input type="text" class="form-control form-control-sm mt-1" name="author_new" value="{{ old('author_new') }}" placeholder="Or add new author (name)">
                @error('author_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('author_new')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select form-control-sm @error('category') is-invalid @enderror" id="category-select" required>
                    <option value="">Select category</option>
                    @foreach($categoryOptions as $group => $opts)
                        <optgroup label="{{ $group }}">
                            @foreach($opts as $label)
                                <option value="{{ $label }}" {{ old('category') === $label ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                    <option value="__other__" {{ old('category') === '__other__' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="text" class="form-control form-control-sm mt-1 d-none" name="category_other" id="category-other" value="{{ old('category_other') }}" placeholder="Type category">
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('category_other')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold small">Description</label>
                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" name="description" rows="2">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Copies <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-sm @error('copies') is-invalid @enderror" name="copies" value="{{ old('copies', 1) }}" min="1" required style="max-width: 80px;">
                @error('copies')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">Add Book</button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm" id="cancel-btn">Cancel</a>
            </div>
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
