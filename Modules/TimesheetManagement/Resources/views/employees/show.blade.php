@extends('layouts.app')
@section('title', 'View Employee')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Employee Details</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Employee Number:</th>
                                <td>{{ $employee->employee_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Full Name:</th>
                                <td>{{ $employee->fullname }}</td>
                            </tr>
                            <tr>
                                <th>National ID Number:</th>
                                <td>{{ $employee->national_id_number }}</td>
                            </tr>
                            <tr>
                                <th>Tel No:</th>
                                <td>{{ $employee->tel_no ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td>{{ $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Position:</th>
                                <td>{{ $employee->position ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Contract Start Date:</th>
                                <td>{{ $employee->contract_start_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Contract End Date:</th>
                                <td>{{ $employee->contract_end_date ? $employee->contract_end_date->format('Y-m-d') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Salary:</th>
                                <td>{{ $employee->salary ? number_format($employee->salary, 2) : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('timesheet.employees.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('timesheet.employees.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>

        <!-- Timesheets Section -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Timesheets</h3>
            </div>
            <div class="box-body">
                <div class="box-tools pull-right" style="margin-bottom: 10px;">
                    <form action="{{ route('timesheet.timesheets.auto_generate', $employee->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Auto-Generate Timesheet (Current Month)
                        </button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->timesheets as $timesheet)
                                <tr>
                                    <td>{{ $timesheet->period }}</td>
                                    <td>{{ $timesheet->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $timesheet->end_date->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('timesheet.timesheets.show', $timesheet->id) }}" class="btn btn-info btn-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No timesheets found. Click "Auto-Generate Timesheet" to create one for the current month.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

