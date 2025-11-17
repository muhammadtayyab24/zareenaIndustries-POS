@extends('layout.app')

@section('title', 'Users Management | Zareena Industries')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Users Management</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Create New User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                            <div
                                class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                                <i class="fas fa-check align-self-center mb-0 text-white "></i>
                            </div>
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                            <div
                                class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
                                <i class="fas fa-xmark align-self-center mb-0 text-white "></i>
                            </div>
                            <strong>Oh snap!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table mb-0 table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 1)
                                                <span class="badge bg-primary">Admin</span>
                                            @elseif($user->role == 2)
                                                <span class="badge bg-info">Manager</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    id="status_{{ $user->id }}" data-user-id="{{ $user->id }}"
                                                    {{ $user->status == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_{{ $user->id }}">
                                                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-danger delete-user"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <p class="mb-0 text-muted">Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>                                                   
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
        // Status Toggle
        document.querySelectorAll('.status-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const status = this.checked ? 1 : 0;

                fetch(`/users/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const label = this.nextElementSibling;
                            label.textContent = data.status == 1 ? 'Active' : 'Inactive';
                        } else {
                            this.checked = !this.checked; // Revert toggle
                            alert('Failed to update status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !this.checked; // Revert toggle
                        alert('An error occurred');
                    });
            });
        });

        // Delete User
        document.querySelectorAll('.delete-user').forEach(function(deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');

                document.getElementById('deleteUserName').textContent = userName;
                document.getElementById('deleteForm').action = `/users/${userId}`;

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('[data-auto-dismiss]').forEach(function(alert) {
            const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, delay);
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush
