@extends('layout.app')

@section('title', 'Company Admins Management | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Company Admins Management</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('companies.create-admin') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create New Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                            <i class="fas fa-check align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
                            <i class="fas fa-xmark align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Admin Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                                    <td><strong>{{ $admin->name }}</strong></td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $admin->company->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $admin->getRoleName() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $admin->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $admin->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No company admins found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($admins->hasPages())
                    <div class="mt-3">
                        {{ $admins->links() }}
                    </div>
                @endif
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

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

