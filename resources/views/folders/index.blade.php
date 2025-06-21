@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Folders</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2>All Folders</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('folders.create') }}" class="btn btn-primary">
                <i class="fas fa-folder-plus"></i> New Folder
            </a>
        </div>
    </div>

    <div class="row">
        @if($folders->count() > 0)
            @foreach($folders as $folder)
                <div class="col-md-3">
                    <div class="card folder-card" onclick="window.location.href='{{ route('folders.show', $folder->id) }}'">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-folder text-warning"></i> 
                                {{ $folder->name }}
                            </h5>
                            <p class="card-text text-muted">
                                {{ $folder->videos->count() }} videos
                                @if($folder->children->count() > 0)
                                    | {{ $folder->children->count() }} subfolders
                                @endif
                            </p>
                            @if($folder->parent)
                                <p class="card-text">
                                    <small class="text-muted">
                                        Parent: <a href="{{ route('folders.show', $folder->parent->id) }}">{{ $folder->parent->name }}</a>
                                    </small>
                                </p>
                            @endif
                            <div class="d-flex justify-content-end mt-2">
                                <a href="{{ route('folders.edit', $folder->id) }}" class="btn btn-secondary btn-sm me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this folder and all its contents?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <p class="text-muted">No folders found. <a href="{{ route('folders.create') }}">Create a folder</a> to organize your videos.</p>
            </div>
        @endif
    </div>
@endsection