@extends('installer.layout', ['step' => 5])

@section('title', 'Installation Complete')

@section('content')
    <div class="text-center mb-4">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h2>Installation Complete!</h2>
        <p class="lead">SimpleStream has been successfully installed.</p>
    </div>
    
    <div class="alert alert-success">
        <p>You can now log in with the admin account you created.</p>
    </div>
    
    <div class="d-grid gap-2">
        <a href="{{ route('login') }}" class="btn btn-success">Go to Login</a>
    </div>
@endsection