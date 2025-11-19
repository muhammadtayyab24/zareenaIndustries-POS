@extends('layout.app')

@section('title', 'Monthly Salary Report | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Monthly Salary Report</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Employees
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @if(isset($error))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
                            <i class="fas fa-xmark align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Error!</strong> {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="GET" action="{{ route('employees.reports.monthly') }}" class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', request('employee_id')) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control @error('month') is-invalid @enderror" 
                                       id="month" name="month" 
                                       value="{{ old('month', request('month', date('Y-m'))) }}" required>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-search me-1"></i> Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(isset($breakdown))
                    <hr>
                    <div class="report-section">
                        <h5 class="mb-3">Employee Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p><strong>Name:</strong> {{ $breakdown['employee']['name'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Contact:</strong> {{ $breakdown['employee']['contact'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Designation:</strong> {{ $breakdown['employee']['designation'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Monthly Salary:</strong> {{ number_format($breakdown['employee']['monthly_salary'], 2) }}</p>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>OT Rate per Hour:</strong> {{ number_format($breakdown['employee']['ot_rate_per_hour'], 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Month:</strong> {{ $breakdown['month'] }}</p>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Attendance Summary</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Present Days</th>
                                        <th>Absent Days</th>
                                        <th>Half Days</th>
                                        <th>Total Working Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $breakdown['attendance_summary']['present_days'] }}</td>
                                        <td>{{ $breakdown['attendance_summary']['absent_days'] }}</td>
                                        <td>{{ $breakdown['attendance_summary']['half_days'] }}</td>
                                        <td>{{ number_format($breakdown['attendance_summary']['total_working_days'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Overtime Summary</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Total OT Hours</th>
                                        <th>OT Rate per Hour</th>
                                        <th>OT Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($breakdown['total_ot_hours'], 2) }} hrs</td>
                                        <td>{{ number_format($breakdown['employee']['ot_rate_per_hour'], 2) }}</td>
                                        <td>{{ number_format($breakdown['ot_amount'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Advance Salary Deductions</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Total Advance Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($breakdown['total_advance_amount'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Salary Calculation</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Base Salary</th>
                                        <th>OT Amount</th>
                                        <th>Advance Deduction</th>
                                        <th>Final Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($breakdown['base_salary'], 2) }}</td>
                                        <td>{{ number_format($breakdown['ot_amount'], 2) }}</td>
                                        <td class="text-danger">- {{ number_format($breakdown['total_advance_amount'], 2) }}</td>
                                        <td><strong class="text-success">{{ number_format($breakdown['final_salary'], 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Calculation Formula</h6>
                                <p class="mb-0">
                                    <strong>Base Salary</strong> = (Monthly Salary / Total Days in Month) × Present Days + (Monthly Salary / Total Days in Month / 2) × Half Days<br>
                                    <strong>OT Amount</strong> = Total OT Hours × OT Rate per Hour<br>
                                    <strong>Final Salary</strong> = Base Salary + OT Amount - Advance Deductions
                                </p>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print me-1"></i> Print Report
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-dismiss alerts
    document.querySelectorAll('[data-auto-dismiss]').forEach(function(alert) {
        const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, delay);
    });
</script>
@endpush

