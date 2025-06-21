@extends('installer.layout', ['step' => 2])

@section('title', 'System Requirements')

@section('content')
    <div class="mb-4">
        <p>The system will check if your server meets the requirements to run SimpleStream.</p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Requirement</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PHP >= 8.1.0</td>
                <td class="text-center">
                    @if($requirements['php_version'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>PDO Extension</td>
                <td class="text-center">
                    @if($requirements['pdo_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Mbstring Extension</td>
                <td class="text-center">
                    @if($requirements['mbstring_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Tokenizer Extension</td>
                <td class="text-center">
                    @if($requirements['tokenizer_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>XML Extension</td>
                <td class="text-center">
                    @if($requirements['xml_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Ctype Extension</td>
                <td class="text-center">
                    @if($requirements['ctype_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>JSON Extension</td>
                <td class="text-center">
                    @if($requirements['json_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>BCMath Extension</td>
                <td class="text-center">
                    @if($requirements['bcmath_extension'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Storage Directory Writable</td>
                <td class="text-center">
                    @if($requirements['storage_writable'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Bootstrap Cache Directory Writable</td>
                <td class="text-center">
                    @if($requirements['bootstrap_cache_writable'])
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('installer.welcome') }}" class="btn btn-secondary">Back</a>
        @if($allRequirementsMet)
            <a href="{{ route('installer.database') }}" class="btn btn-primary">Next: Database Setup</a>
        @else
            <button class="btn btn-danger" disabled>Please fix the requirements</button>
        @endif
    </div>
@endsection