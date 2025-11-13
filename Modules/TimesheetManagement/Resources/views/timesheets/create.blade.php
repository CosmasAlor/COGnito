@extends('layouts.app')
@section('title', 'Create Timesheet')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create Timesheet</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('error') }}
            </div>
        @endif
        
        @if (session('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('info') }}
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @endif
        
        <div class="box box-primary">
            <div class="box-body">
                <form action="{{ route('timesheet.timesheets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-control" id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $id => $name)
                                        <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Period (Month) <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="period" name="period" value="{{ old('period', date('Y-m')) }}" required>
                                <small class="help-block">Select the month for this timesheet</small>
                                @error('period')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" readonly>
                                <small class="help-block">Auto-calculated: 21st of selected month</small>
                                @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" readonly>
                                <small class="help-block">Auto-calculated: 20th of next month</small>
                                @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Create Timesheet</button>
                        <a href="{{ route('timesheet.timesheets.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        // Auto-set start and end dates based on selected month
        // Period: 21st of selected month to 20th of next month
        $('#period').on('change', function() {
            var period = $(this).val();
            if (period) {
                var year = parseInt(period.split('-')[0]);
                var month = parseInt(period.split('-')[1]);
                
                // Start date: 21st of the selected month
                var startDate = year + '-' + (month < 10 ? '0' + month : month) + '-21';
                $('#start_date').val(startDate);
                
                // End date: 20th of the next month
                var nextMonth = month + 1;
                var nextYear = year;
                if (nextMonth > 12) {
                    nextMonth = 1;
                    nextYear = year + 1;
                }
                var endDate = nextYear + '-' + (nextMonth < 10 ? '0' + nextMonth : nextMonth) + '-20';
                $('#end_date').val(endDate);
            }
        });
        
        // Trigger on page load if period is already set
        if ($('#period').val()) {
            $('#period').trigger('change');
        }
    });
</script>
@endsection

