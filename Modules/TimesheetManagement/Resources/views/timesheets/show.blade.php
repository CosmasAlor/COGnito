@extends('layouts.app')
@section('title', 'View Timesheet')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Timesheet Details - {{ $timesheet->employee->fullname }}@if($timesheet->employee->employee_number) ({{ $timesheet->employee->employee_number }})@endif - {{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->format('F Y') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Timesheet Information</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Employee:</th>
                                <td>
                                    {{ $timesheet->employee->fullname }}
                                    @if($timesheet->employee->employee_number)
                                        <span class="text-muted">({{ $timesheet->employee->employee_number }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Period:</th>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->format('F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td>{{ $timesheet->start_date->format('Y-m-d') }} (21st of {{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->format('F Y') }})</td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td>{{ $timesheet->end_date->format('Y-m-d') }} (20th of {{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->addMonth()->format('F Y') }})</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('timesheet.timesheets.edit', $timesheet->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('timesheet.timesheets.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Monthly Summary - {{ $timesheet->period }}</h3>
            </div>
            <div class="box-body">
                @php
                    // Use optimized method for totals
                    $totals = $timesheet->getMonthlyTotals();
                @endphp
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-blue"><i class="fa fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Days</span>
                                <span class="info-box-number">{{ $totals['total_days'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Absent Days</span>
                                <span class="info-box-number">{{ $totals['absent_count'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Leave Days</span>
                                <span class="info-box-number">{{ number_format($totals['annual_leave'] + $totals['family_resp_fl'] + $totals['mt_pt'] + $totals['pph'] + $totals['cto'] + $totals['sick_leave'] + $totals['unpaid'], 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Working Days</span>
                                <span class="info-box-number">{{ $totals['total_days'] - $totals['absent_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Total Days (Month)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Annual Leave</td>
                                    <td><strong>{{ number_format($totals['annual_leave'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Family Resp/FL</td>
                                    <td><strong>{{ number_format($totals['family_resp_fl'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>MT/PT</td>
                                    <td><strong>{{ number_format($totals['mt_pt'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>PPH</td>
                                    <td><strong>{{ number_format($totals['pph'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>CTO</td>
                                    <td><strong>{{ number_format($totals['cto'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Sick Leave</td>
                                    <td><strong>{{ number_format($totals['sick_leave'], 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Unpaid</td>
                                    <td><strong>{{ number_format($totals['unpaid'], 0) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Entries Table -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Daily Entries</h3>
            </div>
            <div class="box-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_month">Filter by Month</label>
                            <select class="form-control" id="filter_month" name="filter_month">
                                <option value="">All Months</option>
                                @php
                                    // Use pre-calculated unique months from database
                                    $months = isset($timesheet->unique_months) ? $timesheet->unique_months : collect([]);
                                @endphp
                                @foreach($months as $month)
                                    @php
                                        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month);
                                        $selected = request('filter_month') == $month ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $month }}" {{ $selected }}>{{ $monthDate->format('F Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding-top: 25px;">
                        <button type="button" class="btn btn-default" id="clear_filter">
                            <i class="fa fa-times"></i> Clear Filter
                        </button>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #f4f4f4;">PERIOD</th>
                                <th colspan="3" style="text-align: center; background-color: #e8f4f8;">TIME</th>
                                <th colspan="8" style="text-align: center; background-color: #fff3cd;">LEAVES</th>
                                <th colspan="2" style="text-align: center; background-color: #d1ecf1;">SIGNATURE</th>
                            </tr>
                            <tr>
                                <th style="background-color: #e8f4f8;">START</th>
                                <th style="background-color: #e8f4f8;">END</th>
                                <th style="background-color: #e8f4f8;">MISSION</th>
                                <th style="background-color: #fff3cd;">ANNUAL LEAVE</th>
                                <th style="background-color: #fff3cd;">Family Resp/FL</th>
                                <th style="background-color: #fff3cd;">MT/PT</th>
                                <th style="background-color: #fff3cd;">PPH</th>
                                <th style="background-color: #fff3cd;">CTO</th>
                                <th style="background-color: #fff3cd;">SICK LEAVE</th>
                                <th style="background-color: #fff3cd;">UNPAID</th>
                                <th style="background-color: #fff3cd;">ABSENT</th>
                                <th style="background-color: #d1ecf1;">IN</th>
                                <th style="background-color: #d1ecf1;">OUT</th>
                            </tr>
                        </thead>
                        <tbody id="entries_tbody">
                            @forelse($timesheet->entries as $entry)
                                <tr>
                                    <td style="background-color: #f4f4f4;">
                                        <strong>{{ $entry->entry_date->format('d-M') }}</strong><br>
                                        <small>{{ $entry->day_of_week }}</small>
                                    </td>
                                    <td>{{ $entry->start_time ? $entry->start_time->format('H:i') : '-' }}</td>
                                    <td>{{ $entry->end_time ? $entry->end_time->format('H:i') : '-' }}</td>
                                    <td>{{ $entry->mission ?? '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->annual_leave > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->family_resp_fl > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->mt_pt > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->pph > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->cto > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->sick_leave > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->unpaid > 0 ? '✓' : '-' }}</td>
                                    <td style="text-align: center;">{{ $entry->absent ? '✓' : '-' }}</td>
                                    <td>{{ $entry->checkin ? $entry->checkin->format('H:i') : '-' }}</td>
                                    <td>{{ $entry->checkout ? $entry->checkout->format('H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">No entries found</td>
                                </tr>
                            @endforelse
                            <!-- Monthly Totals Row -->
                            <tr style="background-color: #e8f4f8; font-weight: bold;">
                                <td style="text-align: center; background-color: #e8f4f8;">
                                    <strong>TOTAL</strong>
                                </td>
                                <td colspan="2" style="text-align: center; background-color: #e8f4f8;">
                                    <strong>MONTHLY TOTALS</strong>
                                </td>
                                <td style="background-color: #e8f4f8;"></td>
                                @php
                                    $totals = $timesheet->getMonthlyTotals();
                                @endphp
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['annual_leave'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['family_resp_fl'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['mt_pt'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['pph'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['cto'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['sick_leave'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ number_format($totals['unpaid'], 0) }}</strong>
                                </td>
                                <td style="text-align: center; background-color: #fff3cd;">
                                    <strong>{{ $totals['absent_count'] }}</strong>
                                </td>
                                <td colspan="2" style="background-color: #d1ecf1;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
<style>
    .table th {
        font-weight: bold;
        text-align: center;
        padding: 8px 4px;
    }
    .table td {
        padding: 4px;
        vertical-align: middle;
    }
</style>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        // Filter entries by month
        $('#filter_month').on('change', function() {
            var selectedMonth = $(this).val();
            var url = new URL(window.location.href);
            
            if (selectedMonth) {
                url.searchParams.set('filter_month', selectedMonth);
            } else {
                url.searchParams.delete('filter_month');
            }
            
            window.location.href = url.toString();
        });

        // Clear filter
        $('#clear_filter').on('click', function() {
            var url = new URL(window.location.href);
            url.searchParams.delete('filter_month');
            window.location.href = url.toString();
        });
    });
</script>
@endsection
