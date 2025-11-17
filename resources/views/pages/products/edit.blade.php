@extends('layout.app')

@section('title', 'Edit Product | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Edit Product</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Products
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

                <form method="POST" action="{{ route('products.update', $product->id) }}" class="my-4">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cat_id" class="form-label">Product Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('cat_id') is-invalid @enderror" id="cat_id" name="cat_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('cat_id', $product->cat_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_id" class="form-label">Product Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type_id') is-invalid @enderror" id="type_id" name="type_id" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id', $product->type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                       id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unit_type" class="form-label">Unit Type</label>
                                <input type="text" class="form-control @error('unit_type') is-invalid @enderror" 
                                       id="unit_type" name="unit_type" value="{{ old('unit_type', $product->unit_type) }}" placeholder="e.g., kg, pcs, box">
                                @error('unit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="opening_qty" class="form-label">Opening Quantity</label>
                                <input type="number" step="0.01" class="form-control @error('opening_qty') is-invalid @enderror" 
                                       id="opening_qty" name="opening_qty" value="{{ old('opening_qty', $product->opening_qty) }}" min="0">
                                @error('opening_qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="current_qty" class="form-label">Current Quantity</label>
                                <input type="number" step="0.01" class="form-control @error('current_qty') is-invalid @enderror" 
                                       id="current_qty" name="current_qty" value="{{ old('current_qty', $product->current_qty) }}" min="0">
                                @error('current_qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Update Product
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">
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

