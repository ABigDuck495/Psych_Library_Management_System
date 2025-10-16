@extends('layouts.app')

@section('title', 'Edit Thesis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Thesis: {{ $thesis->title }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('theses.update', $thesis) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="SKU">SKU *</label>
                                    <input type="text" class="form-control @error('SKU') is-invalid @enderror" 
                                           id="SKU" name="SKU" value="{{ old('SKU', $thesis->SKU) }}" required readonly>
                                    <small class="form-text text-muted">SKU cannot be changed after creation</small>
                                    @error('SKU')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dept_id">Department *</label>
                                    <select class="form-control @error('dept_id') is-invalid @enderror" 
                                            id="dept_id" name="dept_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                {{ old('dept_id', $thesis->dept_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->dept_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dept_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $thesis->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="abstract">Abstract *</label>
                            <textarea class="form-control @error('abstract') is-invalid @enderror" 
                                      id="abstract" name="abstract" rows="5" required>{{ old('abstract', $thesis->abstract) }}</textarea>
                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_published">Year Published *</label>
                                    <input type="number" class="form-control @error('year_published') is-invalid @enderror" 
                                           id="year_published" name="year_published" 
                                           value="{{ old('year_published', $thesis->year_published) }}" 
                                           min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('year_published')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="advisor">Advisor</label>
                                    <input type="text" class="form-control @error('advisor') is-invalid @enderror" 
                                           id="advisor" name="advisor" value="{{ old('advisor', $thesis->advisor) }}">
                                    @error('advisor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pages">Pages</label>
                                    <input type="number" class="form-control @error('pages') is-invalid @enderror" 
                                           id="pages" name="pages" value="{{ old('pages', $thesis->pages) }}" min="1">
                                    @error('pages')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="author_ids">Authors *</label>
                            <div class="input-group">
                                <select class="form-control select2 @error('author_ids') is-invalid @enderror" 
                                        id="author_ids" name="author_ids[]" multiple required
                                        data-placeholder="Select authors...">
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}"
                                            {{ in_array($author->id, old('author_ids', $thesis->author_ids)) ? 'selected' : '' }}>
                                            {{ $author->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#authorModal">
                                        <i class="fas fa-plus"></i> Add New Author
                                    </button>
                                </div>
                                @error('author_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Thesis</button>
                            <a href="{{ route('theses.show', $thesis) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Author Modal -->
<div class="modal fade" id="authorModal" tabindex="-1" role="dialog" aria-labelledby="authorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorModalLabel">Add New Author</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="quickAuthorForm">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" class="form-control" id="modal_first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" class="form-control" id="modal_last_name" name="last_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAuthor">Save Author</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Handle quick author creation
    $('#saveAuthor').click(function() {
        const firstName = $('#modal_first_name').val();
        const lastName = $('#modal_last_name').val();

        if (!firstName || !lastName) {
            alert('Please fill in both first and last name');
            return;
        }

        $.ajax({
            url: '{{ route("authors.quick-store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                first_name: firstName,
                last_name: lastName
            },
            success: function(response) {
                if (response.success) {
                    // Add new author to select2
                    const newOption = new Option(response.full_name, response.id, true, true);
                    $('#author_ids').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#authorModal').modal('hide');
                    $('#quickAuthorForm')[0].reset();
                    
                    // Show success message
                    alert('Author added successfully!');
                }
            },
            error: function(xhr) {
                alert('Error adding author: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    // Reset modal when closed
    $('#authorModal').on('hidden.bs.modal', function() {
        $('#quickAuthorForm')[0].reset();
    });
});
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet">
@endpush