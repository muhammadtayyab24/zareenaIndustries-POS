@extends('layout.app')

@section('title', 'Employee Salaries | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Employee Salaries</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employees.salaries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Calculate Salary
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
                        <input type="month" class="form-control" id="filter_month" placeholder="Month">
                    </div>
                    <div class="col-md-4">
                        <input type="month" class="form-control" id="filter_start_month" placeholder="Start Month">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Month</th>
                                <th>Present Days</th>
                                <th>Absent Days</th>
                                <th>OT Hours</th>
                                <th>Base Salary</th>
                                <th>OT Amount</th>
                                <th>Final Salary</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $salary)
                                <tr>
                                    <td>{{ $salary->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $salary->month }}</td>
                                    <td>{{ $salary->present_days }}</td>
                                    <td>{{ $salary->absent_days }}</td>
                                    <td>{{ number_format($salary->total_ot_hours, 2) }}</td>
                                    <td>{{ number_format($salary->base_salary, 2) }}</td>
                                    <td>{{ number_format($salary->ot_amount, 2) }}</td>
                                    <td><strong>{{ number_format($salary->final_salary, 2) }}</strong></td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-info view-breakdown" 
                                                    data-id="{{ $salary->id }}"
                                                    data-employee-id="{{ $salary->employee_id }}"
                                                    data-month="{{ $salary->month }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="View Breakdown">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary recalculate-salary" 
                                                    data-id="{{ $salary->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Recalculate">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No salary records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breakdown Modal -->
<div class="modal fade" id="breakdownModal" tabindex="-1" role="dialog" aria-labelledby="breakdownModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="breakdownModalLabel">Salary Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="breakdownContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('filter_employee')?.addEventListener('change', applyFilters);
    document.getElementById('filter_month')?.addEventListener('change', applyFilters);
    document.getElementById('filter_start_month')?.addEventListener('change', applyFilters);

    function applyFilters() {
        const params = new URLSearchParams();
        const employeeId = document.getElementById('filter_employee')?.value;
        const month = document.getElementById('filter_month')?.value;
        const startMonth = document.getElementById('filter_start_month')?.value;

        if (employeeId) params.append('employee_id', employeeId);
        if (month) params.append('month', month);
        if (startMonth) params.append('start_month', startMonth);

        window.location.href = '{{ route("employees.salaries.index") }}?' + params.toString();
    }

    // View Breakdown
    document.querySelectorAll('.view-breakdown').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const employeeId = this.getAttribute('data-employee-id');
            const month = this.getAttribute('data-month');
            
            const modal = new bootstrap.Modal(document.getElementById('breakdownModal'));
            modal.show();
            
            fetch(`/employees/salaries/breakdown/get?employee_id=${employeeId}&month=${month}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const breakdown = data.data;
                    let html = `
                        <div class="row">
                            <div class="col-md-6"><strong>Employee:</strong> ${breakdown.employee.name}</div>
                            <div class="col-md-6"><strong>Month:</strong> ${breakdown.month}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Present Days:</strong> ${breakdown.attendance_summary.present_days}</p>
                                <p><strong>Absent Days:</strong> ${breakdown.attendance_summary.absent_days}</p>
                                <p><strong>Half Days:</strong> ${breakdown.attendance_summary.half_days}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total OT Hours:</strong> ${breakdown.total_ot_hours}</p>
                                <p><strong>Total Advance:</strong> ${breakdown.total_advance_amount}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Base Salary:</strong> ${breakdown.base_salary}</p>
                                <p><strong>OT Amount:</strong> ${breakdown.ot_amount}</p>
                                <p><strong>Final Salary:</strong> <strong>${breakdown.final_salary}</strong></p>
                            </div>
                        </div>
                    `;
                    document.getElementById('breakdownContent').innerHTML = html;
                } else {
                    document.getElementById('breakdownContent').innerHTML = '<p class="text-danger">Error loading breakdown</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('breakdownContent').innerHTML = '<p class="text-danger">Error loading breakdown</p>';
            });
        });
    });

    // Recalculate Salary
    document.querySelectorAll('.recalculate-salary').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to recalculate this salary?')) return;
            
            const id = this.getAttribute('data-id');
            fetch(`/employees/salaries/${id}`, {
                method: 'PUT',
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
                    alert(data.message || 'Error recalculating salary');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
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

