@extends('layout.app')

@section('title', 'Employee Attendances | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Employee Attendances</h4>
                    </div>
                    <div class="col-auto">
                        {{--  <a href="{{ route('employees.attendances.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Add Attendance
                        </a>  --}}
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

                <!-- Mark Attendance Section -->
                <div class="card bg-light mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mark Attendance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="mark_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="mark_date" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="mark_employee_id" class="form-label">Employee</label>
                                <select class="form-select" id="mark_employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-warning mark-attendance-btn" 
                                            data-status="late">
                                        <span class="btn-text">Late</span>
                                    </button>
                                    <button type="button" class="btn btn-success mark-attendance-btn" 
                                            data-status="present">
                                        <span class="btn-text">Present</span>
                                    </button>
                                    <button type="button" class="btn btn-danger mark-attendance-btn" 
                                            data-status="absent">
                                        <span class="btn-text">Absent</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Attendance List</h5>

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : ($attendance->status == 'late' ? 'warning' : 'info')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-primary edit-attendance" 
                                                    data-id="{{ $attendance->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-attendance" 
                                                    data-id="{{ $attendance->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No attendances found.</td>
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
                <p class="mb-0 text-muted">Are you sure you want to delete this attendance record?</p>
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
    let deleteAttendanceId = null;

    // Mark Attendance functionality
    document.querySelectorAll('.mark-attendance-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const employeeId = document.getElementById('mark_employee_id').value;
            const status = this.getAttribute('data-status');
            const date = document.getElementById('mark_date').value;
            
            if (!employeeId) {
                alert('Please select an employee');
                return;
            }
            
            if (!date) {
                alert('Please select a date');
                return;
            }
            
            const originalText = this.querySelector('.btn-text').textContent;
            const allButtons = document.querySelectorAll('.mark-attendance-btn');
            
            // Disable all buttons
            allButtons.forEach(b => {
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
            });

            fetch('{{ route("employees.attendances.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    date: date,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page after a short delay to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    alert(data.message || 'Error saving attendance');
                    allButtons.forEach(b => {
                        b.disabled = false;
                        const status = b.getAttribute('data-status');
                        if (status === 'present') {
                            b.innerHTML = '<span class="btn-text">Present</span>';
                        } else if (status === 'absent') {
                            b.innerHTML = '<span class="btn-text">Absent</span>';
                        } else if (status === 'late') {
                            b.innerHTML = '<span class="btn-text">Late</span>';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving attendance');
                allButtons.forEach(b => {
                    b.disabled = false;
                    const status = b.getAttribute('data-status');
                    if (status === 'present') {
                        b.innerHTML = '<span class="btn-text">Present</span>';
                    } else if (status === 'absent') {
                        b.innerHTML = '<span class="btn-text">Absent</span>';
                    } else if (status === 'late') {
                        b.innerHTML = '<span class="btn-text">Late</span>';
                    }
                });
            });
        });
    });

    // Delete Attendance
    document.querySelectorAll('.delete-attendance').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteAttendanceId = this.getAttribute('data-id');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    // Confirm Delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteAttendanceId) return;
        
        fetch(`/employees/attendances/${deleteAttendanceId}`, {
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
                alert(data.message || 'Error deleting attendance');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting attendance');
        });
    });

    // Edit Attendance
    document.querySelectorAll('.edit-attendance').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `/employees/attendances/${this.getAttribute('data-id')}/edit`;
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
