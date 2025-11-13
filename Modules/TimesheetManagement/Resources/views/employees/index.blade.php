@extends('layouts.app')
@section('title', 'Employees')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Employees
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">Manage Employees</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => 'All Employees'])
            @slot('tool')
                <div class="box-tools">
                    <a href="{{ route('timesheet.employees.create') }}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus"></i> Add Employee
                    </a>
                </div>
            @endslot
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="employees_table">
                    <thead>
                        <tr>
                            <th>Employee Number</th>
                            <th>Full Name</th>
                            <th>National ID</th>
                            <th>Tel No</th>
                            <th>Position</th>
                            <th>Contract Start</th>
                            <th>Contract End</th>
                            <th>Salary</th>
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
        var employees_table = $('#employees_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('timesheet.employees.index') }}",
            },
            columns: [
                {data: 'employee_number', name: 'employee_number'},
                {data: 'fullname', name: 'fullname'},
                {data: 'national_id_number', name: 'national_id_number'},
                {data: 'tel_no', name: 'tel_no'},
                {data: 'position', name: 'position'},
                {data: 'contract_start_date', name: 'contract_start_date'},
                {data: 'contract_end_date', name: 'contract_end_date'},
                {data: 'salary', name: 'salary'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endsection

