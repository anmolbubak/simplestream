@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @if($folder->parent)
                        <li class="breadcrumb-item"><a href="{{ route('folders.show', $folder->parent->id) }}">{{ $folder->parent->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $folder->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2>
                <i class="fas fa-folder-open text-warning"></i> 
                {{ $folder->name }}
            </h2>
            @if($folder->description)
                <p class="text-muted">{{ $folder->description }}</p>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('folders.edit', $folder->id) }}" class="btn btn-secondary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this folder and all its contents?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    @if($folder->children->count() > 0)
        <h3 class="mb-3">Subfolders</h3>
        <div class="row mb-4">
            @foreach($folder->children as $childFolder)
                <div class="col-md-3">
                    <div class="card folder-card" onclick="window.location.href='{{ route('folders.show', $childFolder->id) }}'">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-folder text-warning"></i> 
                                {{ $childFolder->name }}
                            </h5>
                            <p class="card-text text-muted">
                                {{ $childFolder->videos->count() }} videos
                                @if($childFolder->children->count() > 0)
                                    | {{ $childFolder->children->count() }} subfolders
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h3 class="mb-3">Videos in this folder</h3>
    <div class="row">
        @if($folder->videos->count() > 0)
            @foreach($folder->videos as $video)
                <div class="col-md-4">
                    <div class="card video-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $video->title }}</h5>
                            <p class="card-text">{{ Str::limit($video->description, 100) }}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Size: {{ number_format($video->size / 1048576, 2) }} MB | 
                                    Uploaded: {{ $video->created_at->diffForHumans() }}
                                </small>
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('videos.show', $video->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i> Play
                                </a>
                                <div>
                                    <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this video?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <p class="text-muted">No videos in this folder. <a href="{{ route('videos.create') }}">Upload a video</a> and select this folder.</p>
            </div>
        @endif
    </div>
@endsection