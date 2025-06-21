@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Videos</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2>All Videos</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('videos.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Video
            </a>
        </div>
    </div>

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
                            @if($video->folder)
                                <p class="card-text">
                                    <small class="text-muted">
                                        Folder: <a href="{{ route('folders.show', $video->folder->id) }}">{{ $video->folder->name }}</a>
                                    </small>
                                </p>
                            @endif
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