@extends('layout.app')

@section('title', 'Advance Salaries | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Advance Salaries</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employees.advance-salaries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Add Advance Salary
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @if (session('success') || request('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                            <i class="fas fa-check align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Success!</strong> {{ session('success') ?? request('success') }}
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

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select" id="filter_employee">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="filter_start_date" placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="filter_end_date" placeholder="End Date">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Notes</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($advances as $advance)
                                <tr>
                                    <td>{{ $advance->employee->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($advance->date)->format('Y-m-d') }}</td>
                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                    <td>{{ $advance->notes ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-primary edit-advance" 
                                                    data-id="{{ $advance->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-advance" 
                                                    data-id="{{ $advance->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No advance salaries found.</td>
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
            </div>
            <div class="text-center py-3">
                <p class="mb-0 text-muted">Are you sure you want to delete this advance salary record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('filter_employee')?.addEventListener('change', applyFilters);
    document.getElementById('filter_start_date')?.addEventListener('change', applyFilters);
    document.getElementById('filter_end_date')?.addEventListener('change', applyFilters);

    function applyFilters() {
        const params = new URLSearchParams();
        const employeeId = document.getElementById('filter_employee')?.value;
        const startDate = document.getElementById('filter_start_date')?.value;
        const endDate = document.getElementById('filter_end_date')?.value;

        if (employeeId) params.append('employee_id', employeeId);
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        window.location.href = '{{ route("employees.advance-salaries.index") }}?' + params.toString();
    }

    let deleteAdvanceId = null;

    // Delete Advance Salary
    document.querySelectorAll('.delete-advance').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteAdvanceId = this.getAttribute('data-id');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    // Confirm Delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteAdvanceId) return;
        
        fetch(`/employees/advance-salaries/${deleteAdvanceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error deleting advance salary');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting advance salary');
        });
    });

    // Edit Advance Salary
    document.querySelectorAll('.edit-advance').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `/employees/advance-salaries/${this.getAttribute('data-id')}/edit`;
        });
    });

    // Auto-dismiss alerts
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

