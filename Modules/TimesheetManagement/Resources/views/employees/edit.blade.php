@extends('layouts.app')
@section('title', 'Edit Employee')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Employee</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <form action="{{ route('timesheet.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_number">Employee Number</label>
                                <input type="text" class="form-control" id="employee_number" name="employee_number" value="{{ old('employee_number', $employee->employee_number) }}">
                                @error('employee_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $employee->fullname) }}" required>
                                @error('fullname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="national_id_number">National ID Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="national_id_number" name="national_id_number" value="{{ old('national_id_number', $employee->national_id_number) }}" required>
                                @error('national_id_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tel_no">Tel No</label>
                                <input type="text" class="form-control" id="tel_no" name="tel_no" value="{{ old('tel_no', $employee->tel_no) }}">
                                @error('tel_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contract_start_date">Contract Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date', $employee->contract_start_date->format('Y-m-d')) }}" required>
                                @error('contract_start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contract_start_date">Contract Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date', $employee->contract_start_date->format('Y-m-d')) }}" required>
                                @error('contract_start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contract_end_date">Contract End Date</label>
                                <input type="date" class="form-control" id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date', $employee->contract_end_date ? $employee->contract_end_date->format('Y-m-d') : '') }}">
                                @error('contract_end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position) }}">
                                @error('position')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}">
                                @error('salary')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                        <a href="{{ route('timesheet.employees.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

