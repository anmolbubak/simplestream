@extends('installer.layout', ['step' => 3])

@section('title', 'Database Configuration')

@section('content')
    <div class="mb-4">
        <p>Configure your database connection. You can choose between SQLite (recommended for small installations) or MySQL.</p>
    </div>
    
    <form action="{{ route('installer.database.setup') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Database Type</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="database_type" id="sqlite" value="sqlite" checked>
                <label class="form-check-label" for="sqlite">
                    SQLite (Simple, file-based database)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="database_type" id="mysql" value="mysql">
                <label class="form-check-label" for="mysql">
                    MySQL / MariaDB
                </label>
            </div>
        </div>
        
        <div id="mysql-fields" style="display: none;">
            <div class="mb-3">
                <label for="database_host" class="form-label">Database Host</label>
                <input type="text" class="form-control @error('database_host') is-invalid @enderror" id="database_host" name="database_host" value="{{ old('database_host', '127.0.0.1') }}">
                @error('database_host')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="database_port" class="form-label">Database Port</label>
                <input type="text" class="form-control @error('database_port') is-invalid @enderror" id="database_port" name="database_port" value="{{ old('database_port', '3306') }}">
                @error('database_port')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="database_name" class="form-label">Database Name</label>
                <input type="text" class="form-control @error('database_name') is-invalid @enderror" id="database_name" name="database_name" value="{{ old('database_name') }}">
                @error('database_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="database_username" class="form-label">Database Username</label>
                <input type="text" class="form-control @error('database_username') is-invalid @enderror" id="database_username" name="database_username" value="{{ old('database_username') }}">
                @error('database_username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="database_password" class="form-label">Database Password</label>
                <input type="password" class="form-control @error('database_password') is-invalid @enderror" id="database_password" name="database_password">
                @error('database_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('installer.requirements') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Next: Admin Setup</button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const databaseTypeRadios = document.querySelectorAll('input[name="database_type"]');
        const mysqlFields = document.getElementById('mysql-fields');
        
        function toggleMySQLFields() {
            const selectedValue = document.querySelector('input[name="database_type"]:checked').value;
            mysqlFields.style.display = selectedValue === 'mysql' ? 'block' : 'none';
        }
        
        databaseTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleMySQLFields);
        });
        
        // Initial toggle
        toggleMySQLFields();
    });
</script>
@endsection