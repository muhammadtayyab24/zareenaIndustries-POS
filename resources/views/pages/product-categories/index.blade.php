@extends('layout.app')

@section('title', 'Product Categories Management | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Product Categories Management</h4>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" id="createCategoryBtn">
                            <i class="fas fa-plus-circle me-1"></i> Create New Category
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="alertContainer"></div>

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="categoriesTableBody">
                            @forelse($categories as $category)
                                <tr id="category_row_{{ $category->id }}">
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   id="status_{{ $category->id }}"
                                                   data-category-id="{{ $category->id }}"
                                                   {{ $category->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_{{ $category->id }}">
                                                {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary edit-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}"
                                                data-category-status="{{ $category->status }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger delete-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" id="category_id" name="category_id">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                        <div class="invalid-feedback" id="category_name_error"></div>
                    </div>
                    <div class="mb-3" id="statusField" style="display: none;">
                        <label for="category_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="categorySubmitBtn">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade bd-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="mySmallModalLabel">Confirm Delete</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="text-center py-3">
                <p class="mb-0 text-muted">Are you sure you want to delete category <strong id="deleteCategoryName"></strong>?</p>                                                   
            </div><!--end modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div><!--end modal-footer-->
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div><!--end modal-->
@endsection

@push('scripts')
<script>
    // Show alert function
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check' : 'fa-xmark';
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        
        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                <div class="d-inline-flex justify-content-center align-items-center thumb-xs ${bgClass} rounded-circle mx-auto me-1">
                    <i class="fas ${iconClass} align-self-center mb-0 text-white"></i>
                </div>
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    // Reset modal form
    function resetCategoryModal() {
        document.getElementById('categoryForm').reset();
        document.getElementById('category_id').value = '';
        document.getElementById('categoryModalLabel').textContent = 'Create Category';
        document.getElementById('categorySubmitBtn').textContent = 'Create Category';
        document.getElementById('statusField').style.display = 'none';
        document.getElementById('category_name').classList.remove('is-invalid');
        document.getElementById('category_name_error').textContent = '';
    }

    // Create Category Button
    document.getElementById('createCategoryBtn').addEventListener('click', function() {
        resetCategoryModal();
    });

    // Edit Category Button
    document.querySelectorAll('.edit-category').forEach(function(editBtn) {
        editBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            const categoryName = this.getAttribute('data-category-name');
            const categoryStatus = this.getAttribute('data-category-status');
            
            document.getElementById('category_id').value = categoryId;
            document.getElementById('category_name').value = categoryName;
            document.getElementById('category_status').value = categoryStatus;
            document.getElementById('categoryModalLabel').textContent = 'Edit Category';
            document.getElementById('categorySubmitBtn').textContent = 'Update Category';
            document.getElementById('statusField').style.display = 'block';
            
            const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
            categoryModal.show();
        });
    });

    // Category Form Submit
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const categoryId = document.getElementById('category_id').value;
        const formData = new FormData(this);
        const url = categoryId ? `/product-categories/${categoryId}` : '/product-categories';
        const method = categoryId ? 'PUT' : 'POST';
        
        // Add _method for PUT request
        if (categoryId) {
            formData.append('_method', 'PUT');
        }
        
        fetch(url, {
            method: method === 'PUT' ? 'POST' : 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
                modal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Handle validation errors
                if (data.errors) {
                    if (data.errors.name) {
                        document.getElementById('category_name').classList.add('is-invalid');
                        document.getElementById('category_name_error').textContent = data.errors.name[0];
                    }
                } else {
                    showAlert(data.message || 'An error occurred', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while processing your request', 'error');
        });
    });

    // Status Toggle
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const categoryId = this.getAttribute('data-category-id');
            const status = this.checked ? 1 : 0;
            
            fetch(`/product-categories/${categoryId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const label = this.nextElementSibling;
                    label.textContent = data.status == 1 ? 'Active' : 'Inactive';
                } else {
                    this.checked = !this.checked;
                    showAlert('Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                showAlert('An error occurred', 'error');
            });
        });
    });

    // Delete Category
    document.querySelectorAll('.delete-category').forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            const categoryName = this.getAttribute('data-category-name');
            
            document.getElementById('deleteCategoryName').textContent = categoryName;
            document.getElementById('deleteForm').action = `/product-categories/${categoryId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
