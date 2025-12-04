@extends('layout.app')

@section('title', 'Companies Management | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Companies Management</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('companies.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create New Company
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
                                <th>Logo</th>
                                <th>Company Name</th>
                                <th>Email</th>
                                <th>NTN</th>
                                <th>STRN</th>
                                <th>Tel No</th>
                                <th>Mobile No</th>
                                <th>Total Admins</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td>{{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}</td>
                                    <td>
                                        @if($company->favicon)
                                            <img src="{{ Storage::url($company->favicon) }}" 
                                                 alt="{{ $company->name }}" 
                                                 class="rounded" 
                                                 style="width: 70px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-building text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $company->name }}</strong></td>
                                    <td>{{ $company->email ?? 'N/A' }}</td>
                                    <td>{{ $company->ntn ?? 'N/A' }}</td>
                                    <td>{{ $company->strn ?? 'N/A' }}</td>
                                    <td>{{ $company->tel_no ?? 'N/A' }}</td>
                                    <td>{{ $company->mobile_no ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $company->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $company->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $company->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $company->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No companies found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($companies->hasPages())
                    <div class="mt-3">
                        {{ $companies->links() }}
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

