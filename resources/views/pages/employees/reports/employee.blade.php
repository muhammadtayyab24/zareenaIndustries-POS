@extends('layout.app')

@section('title', 'Employee Report | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Employee Report</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Employees
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <form method="GET" action="{{ route('employees.reports.employee') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id">
                                    <option value="">All Employees</option>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', request('start_date', date('Y-m-01'))) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" 
                                       value="{{ old('end_date', request('end_date', date('Y-m-t'))) }}">
                                @error('end_date')
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

                @if(isset($allReports))
                    <hr>
                    <div class="report-section">
                        <h5 class="mb-3">All Employees Report</h5>
                        @if(isset($period))
                            <p><strong>Period:</strong> {{ $period['start_date'] }} to {{ $period['end_date'] }}</p>
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table mb-0 table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Contact</th>
                                        <th>Designation</th>
                                        <th>Present Days</th>
                                        <th>Absent Days</th>
                                        <th>Half Days</th>
                                        <th>Total Working Days</th>
                                        <th>Salary Records</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allReports as $report)
                                        <tr>
                                            <td>{{ $report['employee']['name'] }}</td>
                                            <td>{{ $report['employee']['contact'] ?? 'N/A' }}</td>
                                            <td>{{ $report['employee']['designation'] ?? 'N/A' }}</td>
                                            <td>{{ $report['attendance_summary']['present_days'] }}</td>
                                            <td>{{ $report['attendance_summary']['absent_days'] }}</td>
                                            <td>{{ $report['attendance_summary']['half_days'] }}</td>
                                            <td>{{ number_format($report['attendance_summary']['total_working_days'], 2) }}</td>
                                            <td>{{ $report['salary_calculations']->count() }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary view-employee-report" 
                                                        data-employee-id="{{ $report['employee']['id'] }}"
                                                        data-start-date="{{ $period['start_date'] ?? '' }}"
                                                        data-end-date="{{ $period['end_date'] ?? '' }}">
                                                    <i class="fas fa-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif(isset($reportData))
                    <hr>
                    <div class="report-section">
                        <h5 class="mb-3">Employee Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p><strong>Name:</strong> {{ $reportData['employee']['name'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Contact:</strong> {{ $reportData['employee']['contact'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Designation:</strong> {{ $reportData['employee']['designation'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Monthly Salary:</strong> {{ number_format($reportData['employee']['monthly_salary'], 2) }}</p>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>OT Rate per Hour:</strong> {{ number_format($reportData['employee']['ot_rate_per_hour'], 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Period:</strong> {{ $reportData['period']['start_date'] }} to {{ $reportData['period']['end_date'] }}</p>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Attendance Summary</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Total Days</th>
                                        <th>Present Days</th>
                                        <th>Absent Days</th>
                                        <th>Half Days</th>
                                        <th>Total Working Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $reportData['attendance_summary']['total_days'] }}</td>
                                        <td>{{ $reportData['attendance_summary']['present_days'] }}</td>
                                        <td>{{ $reportData['attendance_summary']['absent_days'] }}</td>
                                        <td>{{ $reportData['attendance_summary']['half_days'] }}</td>
                                        <td>{{ number_format($reportData['attendance_summary']['total_working_days'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($reportData['monthly_ot']))
                            <h5 class="mb-3 mt-4">Monthly Overtime Summary</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Month</th>
                                            <th>Total OT Hours</th>
                                            <th>OT Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData['monthly_ot'] as $month => $otData)
                                            <tr>
                                                <td>{{ $month }}</td>
                                                <td>{{ number_format($otData['total_hours'], 2) }} hrs</td>
                                                <td>{{ number_format($otData['ot_amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <h5 class="mb-3 mt-4">Advance Salary Deductions</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Total Advances</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $reportData['advance_salary_deductions']['total_advances'] }}</td>
                                        <td>{{ number_format($reportData['advance_salary_deductions']['total_amount'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($reportData['advance_salary_deductions']['advances']->count() > 0)
                            <h6 class="mb-2">Advance Details</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData['advance_salary_deductions']['advances'] as $advance)
                                            <tr>
                                                <td>{{ $advance['date'] }}</td>
                                                <td>{{ number_format($advance['amount'], 2) }}</td>
                                                <td>{{ $advance['notes'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($reportData['salary_calculations']->count() > 0)
                            <h5 class="mb-3 mt-4">Salary Calculations</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Month</th>
                                            <th>Present Days</th>
                                            <th>Absent Days</th>
                                            <th>Half Days</th>
                                            <th>OT Hours</th>
                                            <th>Advance Amount</th>
                                            <th>Base Salary</th>
                                            <th>OT Amount</th>
                                            <th>Final Salary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData['salary_calculations'] as $salary)
                                            <tr>
                                                <td>{{ $salary['month'] }}</td>
                                                <td>{{ $salary['present_days'] }}</td>
                                                <td>{{ $salary['absent_days'] }}</td>
                                                <td>{{ $salary['half_days'] }}</td>
                                                <td>{{ number_format($salary['total_ot_hours'], 2) }}</td>
                                                <td>{{ number_format($salary['total_advance_amount'], 2) }}</td>
                                                <td>{{ number_format($salary['base_salary'], 2) }}</td>
                                                <td>{{ number_format($salary['ot_amount'], 2) }}</td>
                                                <td><strong>{{ number_format($salary['final_salary'], 2) }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-1"></i>
                                No salary calculations found for this period.
                            </div>
                        @endif

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
    // View Employee Report
    document.querySelectorAll('.view-employee-report').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const employeeId = this.getAttribute('data-employee-id');
            const startDate = this.getAttribute('data-start-date');
            const endDate = this.getAttribute('data-end-date');
            
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("employees.reports.employee") }}';
            
            const employeeInput = document.createElement('input');
            employeeInput.type = 'hidden';
            employeeInput.name = 'employee_id';
            employeeInput.value = employeeId;
            form.appendChild(employeeInput);
            
            if (startDate) {
                const startInput = document.createElement('input');
                startInput.type = 'hidden';
                startInput.name = 'start_date';
                startInput.value = startDate;
                form.appendChild(startInput);
            }
            
            if (endDate) {
                const endInput = document.createElement('input');
                endInput.type = 'hidden';
                endInput.name = 'end_date';
                endInput.value = endDate;
                form.appendChild(endInput);
            }
            
            document.body.appendChild(form);
            form.submit();
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
</script>
@endpush
