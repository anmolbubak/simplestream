@extends('installer.layout', ['step' => 1])

@section('title', 'Welcome to SimpleStream')

@section('content')
    <div class="text-center mb-4">
        <i class="fas fa-video fa-4x text-primary mb-3"></i>
        <h2>Welcome to SimpleStream</h2>
        <p class="lead">A simple video management system</p>
    </div>
    
    <div class="alert alert-info">
        <p>This installer will guide you through the setup process. Please follow these steps:</p>
        <ol>
            <li>Check system requirements</li>
            <li>Configure database connection</li>
            <li>Create admin account</li>
            <li>Complete installation</li>
        </ol>
    </div>
    
    <div class="d-grid gap-2">
        <a href="{{ route('installer.requirements') }}" class="btn btn-primary">Start Installation</a>
    </div>
@endsection