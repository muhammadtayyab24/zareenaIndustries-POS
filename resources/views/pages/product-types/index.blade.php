@extends('layout.app')

@section('title', 'Product Types Management | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Product Types Management</h4>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#typeModal" id="createTypeBtn">
                            <i class="fas fa-plus-circle me-1"></i> Create New Type
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
                        <tbody id="typesTableBody">
                            @forelse($types as $type)
                                <tr id="type_row_{{ $type->id }}">
                                    <td>{{ $type->name }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   id="status_{{ $type->id }}"
                                                   data-type-id="{{ $type->id }}"
                                                   {{ $type->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_{{ $type->id }}">
                                                {{ $type->status == 1 ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="#" class="btn btn-sm btn-primary edit-type"
                                                data-type-id="{{ $type->id }}"
                                                data-type-name="{{ $type->name }}"
                                                data-type-status="{{ $type->status }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger delete-type"
                                                data-type-id="{{ $type->id }}"
                                                data-type-name="{{ $type->name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No types found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Type Modal -->
<div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Create Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="typeForm">
                <div class="modal-body">
                    <input type="hidden" id="type_id" name="type_id">
                    <div class="mb-3">
                        <label for="type_name" class="form-label">Type Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type_name" name="name" required>
                        <div class="invalid-feedback" id="type_name_error"></div>
                    </div>
                    <div class="mb-3" id="statusField" style="display: none;">
                        <label for="type_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="typeSubmitBtn">Create Type</button>
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
                <p class="mb-0 text-muted">Are you sure you want to delete type <strong id="deleteTypeName"></strong>?</p>                                                   
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
    function resetTypeModal() {
        document.getElementById('typeForm').reset();
        document.getElementById('type_id').value = '';
        document.getElementById('typeModalLabel').textContent = 'Create Type';
        document.getElementById('typeSubmitBtn').textContent = 'Create Type';
        document.getElementById('statusField').style.display = 'none';
        document.getElementById('type_name').classList.remove('is-invalid');
        document.getElementById('type_name_error').textContent = '';
    }

    // Create Type Button
    document.getElementById('createTypeBtn').addEventListener('click', function() {
        resetTypeModal();
    });

    // Edit Type Button
    document.querySelectorAll('.edit-type').forEach(function(editBtn) {
        editBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const typeId = this.getAttribute('data-type-id');
            const typeName = this.getAttribute('data-type-name');
            const typeStatus = this.getAttribute('data-type-status');
            
            document.getElementById('type_id').value = typeId;
            document.getElementById('type_name').value = typeName;
            document.getElementById('type_status').value = typeStatus;
            document.getElementById('typeModalLabel').textContent = 'Edit Type';
            document.getElementById('typeSubmitBtn').textContent = 'Update Type';
            document.getElementById('statusField').style.display = 'block';
            
            const typeModal = new bootstrap.Modal(document.getElementById('typeModal'));
            typeModal.show();
        });
    });

    // Type Form Submit
    document.getElementById('typeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const typeId = document.getElementById('type_id').value;
        const formData = new FormData(this);
        const url = typeId ? `/product-types/${typeId}` : '/product-types';
        const method = typeId ? 'PUT' : 'POST';
        
        // Add _method for PUT request
        if (typeId) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('typeModal'));
                modal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Handle validation errors
                if (data.errors) {
                    if (data.errors.name) {
                        document.getElementById('type_name').classList.add('is-invalid');
                        document.getElementById('type_name_error').textContent = data.errors.name[0];
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
            const typeId = this.getAttribute('data-type-id');
            const status = this.checked ? 1 : 0;
            
            fetch(`/product-types/${typeId}/toggle-status`, {
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

    // Delete Type
    document.querySelectorAll('.delete-type').forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const typeId = this.getAttribute('data-type-id');
            const typeName = this.getAttribute('data-type-name');
            
            document.getElementById('deleteTypeName').textContent = typeName;
            document.getElementById('deleteForm').action = `/product-types/${typeId}`;
            
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
