@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                </ol>
            </nav>
        </div>
    </div>

    <h2 class="mb-4">Folders</h2>
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

    <h2 class="mb-4 mt-5">Videos</h2>
    <div class="row">
        @if($videos->count() > 0)
            @foreach($videos as $video)
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
                <p class="text-muted">No videos found. <a href="{{ route('videos.create') }}">Upload a video</a> to get started.</p>
            </div>
        @endif
    </div>
@endsection