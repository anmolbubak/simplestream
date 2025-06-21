@extends('installer.layout', ['step' => 4])

@section('title', 'Admin Account Setup')

@section('content')
    <div class="mb-4">
        <p>Create your admin account to manage SimpleStream.</p>
    </div>
    
    <form action="{{ route('installer.admin.setup') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', 'Admin') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Password must be at least 8 characters long.</div>
        </div>
        
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('installer.database') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Complete Installation</button>
        </div>
    </form>
@endsection