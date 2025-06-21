<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleStream - Video Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 20px;
        }
        .video-card {
            margin-bottom: 20px;
        }
        .folder-card {
            margin-bottom: 20px;
            cursor: pointer;
        }
        .progress {
            height: 25px;
        }
        .upload-info {
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h1><a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">SimpleStream</a></h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('videos.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-upload"></i> Upload Video
                    </a>
                    <a href="{{ route('folders.create') }}" class="btn btn-secondary">
                        <i class="fas fa-folder-plus"></i> New Folder
                    </a>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        <footer class="mt-5 pt-3 border-top text-center text-muted">
            <p>&copy; {{ date('Y') }} SimpleStream. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>