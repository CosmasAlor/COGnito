@extends('layouts.app')
@section('title', 'Timesheets')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Timesheets
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">Manage Timesheets</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => 'All Timesheets'])
            @slot('tool')
                <div class="box-tools">
                    <a href="{{ route('timesheet.timesheets.create') }}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus"></i> Add Timesheet
                    </a>
                </div>
            @endslot
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_month">Filter by Month</label>
                        <select class="form-control" id="filter_month" name="filter_month">
                            <option value="">All Months</option>
                            @for($i = 0; $i < 12; $i++)
                                @php
                                    $date = now()->subMonths($i);
                                    $monthValue = $date->format('Y-m');
                                    $monthName = $date->format('F Y');
                                    $selected = request('month') == $monthValue ? 'selected' : '';
                                @endphp
                                <option value="{{ $monthValue }}" {{ $selected }}>{{ $monthName }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_employee">Filter by Employee</label>
                        <select class="form-control" id="filter_employee" name="filter_employee">
                            <option value="">All Employees</option>
                            @foreach(\Modules\TimesheetManagement\Entities\Employee::orderBy('fullname')->get() as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->fullname }}@if($employee->employee_number) ({{ $employee->employee_number }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 25px;">
                    <button type="button" class="btn btn-default" id="clear_filters">
                        <i class="fa fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="timesheets_table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Employee Number</th>
                            <th>Period</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Entries</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcomponent
    </section>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        var timesheets_table = $('#timesheets_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('timesheet.timesheets.index') }}",
                data: function(d) {
                    d.month = $('#filter_month').val();
                    d.employee_id = $('#filter_employee').val();
                }
            },
            columns: [
                {data: 'employee_name', name: 'employee.fullname'},
                {data: 'employee_number', name: 'employee.employee_number'},
                {data: 'period', name: 'period'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'entries_count', name: 'entries_count'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[2, 'desc']] // Order by period descending (newest first)
        });

        // Filter by month
        $('#filter_month, #filter_employee').on('change', function() {
            timesheets_table.ajax.reload();
        });

        // Clear filters
        $('#clear_filters').on('click', function() {
            $('#filter_month').val('');
            $('#filter_employee').val('');
            timesheets_table.ajax.reload();
        });
    });
</script>
@endsection

