@extends('layout.app')

@section('title', 'Create Company | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Create New Company & Admin</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('companies.create-admin') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user-plus me-1"></i> Create Admin for Existing Company
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
                            <i class="fas fa-xmark align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Error!</strong> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                            <i class="fas fa-check align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data" class="my-4">
                    @csrf
                    
                    <!-- Company Information Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">
                            <i class="fas fa-building me-2"></i> Company Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                           id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_email" class="form-label">Company Email</label>
                                    <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                           id="company_email" name="company_email" value="{{ old('company_email') }}">
                                    @error('company_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_tel_no" class="form-label">Telephone Number</label>
                                    <input type="text" class="form-control @error('company_tel_no') is-invalid @enderror" 
                                           id="company_tel_no" name="company_tel_no" value="{{ old('company_tel_no') }}">
                                    @error('company_tel_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_mobile_no" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control @error('company_mobile_no') is-invalid @enderror" 
                                           id="company_mobile_no" name="company_mobile_no" value="{{ old('company_mobile_no') }}">
                                    @error('company_mobile_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_ntn" class="form-label">NTN (National Tax Number)</label>
                                    <input type="text" class="form-control @error('company_ntn') is-invalid @enderror" 
                                           id="company_ntn" name="company_ntn" value="{{ old('company_ntn') }}">
                                    @error('company_ntn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_strn" class="form-label">STRN (Sales Tax Registration Number)</label>
                                    <input type="text" class="form-control @error('company_strn') is-invalid @enderror" 
                                           id="company_strn" name="company_strn" value="{{ old('company_strn') }}">
                                    @error('company_strn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_website" class="form-label">Website</label>
                                    <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                           id="company_website" name="company_website" value="{{ old('company_website') }}" placeholder="https://example.com">
                                    @error('company_website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Company Address</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                              id="company_address" name="company_address" rows="3">{{ old('company_address') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_logo" class="form-label">Company Logo</label>
                                    <input type="file" class="form-control @error('company_logo') is-invalid @enderror" 
                                           id="company_logo" name="company_logo" accept="image/*" onchange="previewLogo(this)">
                                    <small class="text-muted">Recommended: PNG, JPG, SVG (Max: 2MB)</small>
                                    @error('company_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="logo_preview" class="mt-2" style="display: none;">
                                        <img id="logo_preview_img" src="" alt="Logo Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_favicon" class="form-label">Company Favicon</label>
                                    <input type="file" class="form-control @error('company_favicon') is-invalid @enderror" 
                                           id="company_favicon" name="company_favicon" accept="image/*" onchange="previewFavicon(this)">
                                    <small class="text-muted">Recommended: ICO, PNG (Max: 500KB, Size: 32x32 or 16x16)</small>
                                    @error('company_favicon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="favicon_preview" class="mt-2" style="display: none;">
                                        <img id="favicon_preview_img" src="" alt="Favicon Preview" style="max-width: 64px; max-height: 64px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Information Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">
                            <i class="fas fa-user-shield me-2"></i> Company Admin Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('admin_name') is-invalid @enderror" 
                                           id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                                    @error('admin_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('admin_email') is-invalid @enderror" 
                                           id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                                    @error('admin_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('admin_password') is-invalid @enderror" 
                                           id="admin_password" name="admin_password" required>
                                    @error('admin_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('admin_password_confirmation') is-invalid @enderror" 
                                           id="admin_password_confirmation" name="admin_password_confirmation" required>
                                    @error('admin_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Create Company & Admin
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logo Preview Function
    function previewLogo(input) {
        const preview = document.getElementById('logo_preview');
        const previewImg = document.getElementById('logo_preview_img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    // Favicon Preview Function
    function previewFavicon(input) {
        const preview = document.getElementById('favicon_preview');
        const previewImg = document.getElementById('favicon_preview_img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('[data-auto-dismiss]').forEach(function(alert) {
        const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, delay);
    });
</script>
@endpush

