@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Upload Video</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Upload New Video</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" id="videoUploadForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Video Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="folder_id" class="form-label">Folder (Optional)</label>
                            <select class="form-select @error('folder_id') is-invalid @enderror" id="folder_id" name="folder_id">
                                <option value="">None (Root)</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ old('folder_id') == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('folder_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="video" class="form-label">Video File</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*" required>
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Supported formats: MP4, AVI, MPEG, MOV, FLV, WMV, WebM. Maximum size: 1GB</div>
                        </div>
                        
                        <div id="uploadProgress" class="mb-3 d-none">
                            <label class="form-label">Upload Progress</label>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                            <div class="upload-info d-flex justify-content-between mt-1">
                                <span id="uploadedSize">0 MB</span>
                                <span id="uploadSpeed">0 MB/s</span>
                                <span id="remainingTime">--:--</span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="uploadButton">Upload Video</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const form = document.getElementById('videoUploadForm');
        const progressBar = document.querySelector('.progress-bar');
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadedSize = document.getElementById('uploadedSize');
        const uploadSpeed = document.getElementById('uploadSpeed');
        const remainingTime = document.getElementById('remainingTime');
        const uploadButton = document.getElementById('uploadButton');
        
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('video');
            if (fileInput.files.length === 0) {
                return;
            }
            
            e.preventDefault();
            
            uploadProgress.classList.remove('d-none');
            uploadButton.disabled = true;
            uploadButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
            
            const xhr = new XMLHttpRequest();
            const formData = new FormData(form);
            
            // Variables for tracking upload speed and time
            let startTime = new Date().getTime();
            let lastLoaded = 0;
            let lastTime = startTime;
            let speed = 0;
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    // Calculate progress percentage
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                    progressBar.setAttribute('aria-valuenow', percent);
                    
                    // Calculate uploaded size
                    const uploadedMB = (e.loaded / 1048576).toFixed(2);
                    const totalMB = (e.total / 1048576).toFixed(2);
                    uploadedSize.textContent = uploadedMB + ' MB / ' + totalMB + ' MB';
                    
                    // Calculate upload speed
                    const currentTime = new Date().getTime();
                    const timeDiff = (currentTime - lastTime) / 1000; // in seconds
                    
                    if (timeDiff > 0.5) { // Update every half second
                        const loadDiff = e.loaded - lastLoaded; // bytes loaded since last update
                        speed = (loadDiff / timeDiff) / 1048576; // MB/s
                        
                        uploadSpeed.textContent = speed.toFixed(2) + ' MB/s';
                        
                        // Calculate remaining time
                        const remainingBytes = e.total - e.loaded;
                        if (speed > 0) {
                            const remainingSecs = Math.round(remainingBytes / (speed * 1048576));
                            const mins = Math.floor(remainingSecs / 60);
                            const secs = remainingSecs % 60;
                            remainingTime.textContent = `${mins}:${secs.toString().padStart(2, '0')} remaining`;
                        }
                        
                        lastLoaded = e.loaded;
                        lastTime = currentTime;
                    }
                }
            });
            
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    window.location.href = xhr.responseURL;
                } else {
                    uploadButton.disabled = false;
                    uploadButton.textContent = 'Upload Video';
                    alert('Upload failed. Please try again.');
                }
            });
            
            xhr.addEventListener('error', function() {
                uploadButton.disabled = false;
                uploadButton.textContent = 'Upload Video';
                alert('Upload failed. Please check your connection and try again.');
            });
            
            xhr.open('POST', form.action, true);
            xhr.send(formData);
        });
    });
</script>
@endsection