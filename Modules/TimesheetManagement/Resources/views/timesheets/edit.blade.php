@extends('layouts.app')
@section('title', 'Edit Timesheet')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Timesheet - {{ $timesheet->employee->fullname }}@if($timesheet->employee->employee_number) ({{ $timesheet->employee->employee_number }})@endif - {{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->format('F Y') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <form action="{{ route('timesheet.timesheets.update', $timesheet->id) }}" method="POST" id="timesheet-form">
                    @csrf
                    @method('POST')
                    
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-control" id="employee_id" name="employee_id" required>
                                    @foreach($employees as $id => $name)
                                        <option value="{{ $id }}" {{ $timesheet->employee_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period">Period (Month) <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="period" name="period" value="{{ $timesheet->period }}" required>
                                <small class="help-block">{{ \Carbon\Carbon::createFromFormat('Y-m', $timesheet->period)->format('F Y') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $timesheet->start_date->format('Y-m-d') }}" readonly>
                                <small class="help-block">Auto-calculated: 21st of period month</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $timesheet->end_date->format('Y-m-d') }}" readonly>
                                <small class="help-block">Auto-calculated: 20th of next month</small>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; max-height: 600px; overflow-y: auto;">
                        <table class="table table-bordered table-striped" id="timesheet-table" style="margin-bottom: 0;">
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
                            <tbody>
                                @foreach($timesheet->entries as $index => $entry)
                                    <tr>
                                        <td style="background-color: #f4f4f4;">
                                            <strong>{{ $entry->entry_date->format('d-M') }}</strong><br>
                                            <small>{{ $entry->day_of_week }}</small>
                                            <input type="hidden" name="entries[{{ $index }}][entry_date]" value="{{ $entry->entry_date->format('Y-m-d') }}">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control entry-input" name="entries[{{ $index }}][start_time]" value="{{ $entry->start_time ? $entry->start_time->format('H:i') : '' }}" style="min-width: 80px;">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control entry-input" name="entries[{{ $index }}][end_time]" value="{{ $entry->end_time ? $entry->end_time->format('H:i') : '' }}" style="min-width: 80px;">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control entry-input" name="entries[{{ $index }}][mission]" value="{{ $entry->mission }}" style="min-width: 150px;">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="annual_leave" data-index="{{ $index }}" {{ $entry->annual_leave > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][annual_leave]" value="{{ $entry->annual_leave > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="family_resp_fl" data-index="{{ $index }}" {{ $entry->family_resp_fl > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][family_resp_fl]" value="{{ $entry->family_resp_fl > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="mt_pt" data-index="{{ $index }}" {{ $entry->mt_pt > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][mt_pt]" value="{{ $entry->mt_pt > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="pph" data-index="{{ $index }}" {{ $entry->pph > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][pph]" value="{{ $entry->pph > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="cto" data-index="{{ $index }}" {{ $entry->cto > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][cto]" value="{{ $entry->cto > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="sick_leave" data-index="{{ $index }}" {{ $entry->sick_leave > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][sick_leave]" value="{{ $entry->sick_leave > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox leave-checkbox" data-field="unpaid" data-index="{{ $index }}" {{ $entry->unpaid > 0 ? 'checked' : '' }}>
                                            <input type="hidden" name="entries[{{ $index }}][unpaid]" value="{{ $entry->unpaid > 0 ? '1' : '0' }}" class="leave-value">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="entry-checkbox" name="entries[{{ $index }}][absent]" value="1" {{ $entry->absent ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input type="time" class="form-control entry-input" name="entries[{{ $index }}][checkin]" value="{{ $entry->checkin ? $entry->checkin->format('H:i') : '' }}" style="min-width: 80px;">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control entry-input" name="entries[{{ $index }}][checkout]" value="{{ $entry->checkout ? $entry->checkout->format('H:i') : '' }}" style="min-width: 80px;">
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Monthly Totals Row -->
                                <tr style="background-color: #e8f4f8; font-weight: bold;" id="totals-row">
                                    <td style="text-align: center; background-color: #e8f4f8;">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td colspan="2" style="text-align: center; background-color: #e8f4f8;">
                                        <strong>MONTHLY TOTALS</strong>
                                    </td>
                                    <td style="background-color: #e8f4f8;"></td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_annual_leave">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_family_resp_fl">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_mt_pt">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_pph">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_cto">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_sick_leave">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_unpaid">0.00</span>
                                    </td>
                                    <td style="text-align: center; background-color: #fff3cd;">
                                        <span id="total_absent">0</span>
                                    </td>
                                    <td colspan="2" style="background-color: #d1ecf1;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Update Timesheet</button>
                        <a href="{{ route('timesheet.timesheets.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('css')
<style>
    #timesheet-table {
        font-size: 12px;
    }
    #timesheet-table th {
        font-weight: bold;
        text-align: center;
        padding: 8px 4px;
    }
    #timesheet-table td {
        padding: 4px;
        vertical-align: middle;
    }
    #timesheet-table input[type="text"],
    #timesheet-table input[type="time"] {
        border: 1px solid #ddd;
        padding: 4px;
        font-size: 11px;
    }
    #timesheet-table input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    #timesheet-table td[style*="text-align: center"] {
        text-align: center !important;
    }
    #timesheet-table tr:last-child {
        background-color: #e8f4f8 !important;
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
    .table-responsive {
        border: 1px solid #ddd;
    }
</style>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        var calculationTimeout;
        
        // Debounced calculation to avoid too many calculations (increased delay for better performance)
        function debounceCalculate() {
            clearTimeout(calculationTimeout);
            calculationTimeout = setTimeout(calculateMonthlyTotals, 500);
        }

        // Update hidden input when checkbox changes
        $(document).on('change', '.leave-checkbox', function() {
            var $checkbox = $(this);
            var field = $checkbox.data('field');
            var index = $checkbox.data('index');
            var $hidden = $('input[name="entries[' + index + '][' + field + ']"]');
            $hidden.val($checkbox.is(':checked') ? '1' : '0');
            debounceCalculate();
        });
        
        // Use more specific selectors for better performance
        $(document).on('change', '.entry-checkbox', debounceCalculate);

        function calculateMonthlyTotals() {
            var totalAnnualLeave = 0;
            var totalFamilyRespFl = 0;
            var totalMtPt = 0;
            var totalPph = 0;
            var totalCto = 0;
            var totalSickLeave = 0;
            var totalUnpaid = 0;
            var totalAbsent = 0;

            // Optimized: Cache selectors and use direct input selectors
            var $rows = $('#timesheet-table tbody tr').not('#totals-row');
            
            // Process in batches to avoid blocking UI
            $rows.each(function(index) {
                var $row = $(this);
                var $inputs = $row.find('input');
                
                // Only process if row has entry inputs
                if ($inputs.length > 0) {
                    // Read from hidden inputs (each value '1' = 1 day)
                    totalAnnualLeave += parseFloat($row.find('input[name*="[annual_leave]"][type="hidden"]').val() || 0);
                    totalFamilyRespFl += parseFloat($row.find('input[name*="[family_resp_fl]"][type="hidden"]').val() || 0);
                    totalMtPt += parseFloat($row.find('input[name*="[mt_pt]"][type="hidden"]').val() || 0);
                    totalPph += parseFloat($row.find('input[name*="[pph]"][type="hidden"]').val() || 0);
                    totalCto += parseFloat($row.find('input[name*="[cto]"][type="hidden"]').val() || 0);
                    totalSickLeave += parseFloat($row.find('input[name*="[sick_leave]"][type="hidden"]').val() || 0);
                    totalUnpaid += parseFloat($row.find('input[name*="[unpaid]"][type="hidden"]').val() || 0);
                    totalAbsent += $row.find('input[name*="[absent]"]').is(':checked') ? 1 : 0;
                }
            });

            // Update totals in one batch (display as integers since checkboxes = 1 day each)
            $('#total_annual_leave').text(totalAnnualLeave);
            $('#total_family_resp_fl').text(totalFamilyRespFl);
            $('#total_mt_pt').text(totalMtPt);
            $('#total_pph').text(totalPph);
            $('#total_cto').text(totalCto);
            $('#total_sick_leave').text(totalSickLeave);
            $('#total_unpaid').text(totalUnpaid);
            $('#total_absent').text(totalAbsent);
        }

        // Initial calculation
        calculateMonthlyTotals();

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
    });
</script>
@endsection
