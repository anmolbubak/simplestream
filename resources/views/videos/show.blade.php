@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @if($video->folder)
                        <li class="breadcrumb-item"><a href="{{ route('folders.show', $video->folder->id) }}">{{ $video->folder->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $video->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $video->title }}</h2>
            @if($video->description)
                <p class="text-muted">{{ $video->description }}</p>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-secondary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this video?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <video controls class="w-100" style="max-height: 500px;">
                        <source src="{{ $video->url }}" type="{{ $video->mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Video Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Original Filename:</span>
                            <span class="text-muted">{{ $video->original_filename }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Size:</span>
                            <span class="text-muted">{{ number_format($video->size / 1048576, 2) }} MB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Type:</span>
                            <span class="text-muted">{{ $video->mime_type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Uploaded:</span>
                            <span class="text-muted">{{ $video->created_at->format('M d, Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Last Updated:</span>
                            <span class="text-muted">{{ $video->updated_at->format('M d, Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Folder:</span>
                            <span class="text-muted">
                                @if($video->folder)
                                    <a href="{{ route('folders.show', $video->folder->id) }}">{{ $video->folder->name }}</a>
                                @else
                                    None (Root)
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Direct Link</h5>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="videoUrl" value="{{ $video->url }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyButton" onclick="copyToClipboard()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="form-text">Copy this link to share the video directly.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function copyToClipboard() {
        const videoUrl = document.getElementById('videoUrl');
        videoUrl.select();
        document.execCommand('copy');
        
        const copyButton = document.getElementById('copyButton');
        const originalHTML = copyButton.innerHTML;
        
        copyButton.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(function() {
            copyButton.innerHTML = originalHTML;
        }, 2000);
    }
</script>
@endsection