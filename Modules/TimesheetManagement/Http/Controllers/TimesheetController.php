<?php

namespace Modules\TimesheetManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TimesheetManagement\Entities\Timesheet;
use Modules\TimesheetManagement\Entities\TimesheetEntry;
use Modules\TimesheetManagement\Entities\Employee;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    /**
     * Calculate start and end dates from period (21st of period month to 20th of next month)
     *
     * @param  string  $period  Format: YYYY-MM
     * @return array  ['start_date' => Carbon, 'end_date' => Carbon]
     */
    private function calculatePeriodDates($period)
    {
        $periodDate = Carbon::createFromFormat('Y-m', $period);
        
        // Start date: 21st of the period month
        $startDate = $periodDate->copy()->day(21);
        
        // End date: 20th of the next month
        $endDate = $periodDate->copy()->addMonth()->day(20);
        
        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $timesheets = Timesheet::with('employee')->withCount('entries')->select('timesheets.*');

            // Filter by month (period)
            if ($request->has('month') && !empty($request->month)) {
                $timesheets->where('period', $request->month);
            }

            // Filter by employee
            if ($request->has('employee_id') && !empty($request->employee_id)) {
                $timesheets->where('employee_id', $request->employee_id);
            }

            return DataTables::of($timesheets)
                ->editColumn('period', function ($row) {
                    // Format period as "Month Year" (e.g., "November 2025")
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $row->period);
                    return $date->format('F Y');
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->employee ? $row->employee->fullname : '-';
                })
                ->addColumn('employee_number', function ($row) {
                    return $row->employee && $row->employee->employee_number ? $row->employee->employee_number : '-';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('timesheet.timesheets.show', $row->id) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                    $html .= '<a href="' . route('timesheet.timesheets.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $html .= '<form action="' . route('timesheet.timesheets.destroy', $row->id) . '" method="POST" class="d-inline">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>';
                    $html .= '</form>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date->format('Y-m-d') : '-';
                })
                ->editColumn('end_date', function ($row) {
                    return $row->end_date ? $row->end_date->format('Y-m-d') : '-';
                })
                ->addColumn('entries_count', function ($row) {
                    return $row->entries_count ?? 0;
                })
                ->rawColumns(['action'])
                ->orderColumn('period', 'period $1')
                ->make(true);
        }

        return view('timesheetmanagement::timesheets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::select('id', 'fullname', 'employee_number')
            ->get()
            ->mapWithKeys(function ($employee) {
                $name = $employee->fullname;
                if ($employee->employee_number) {
                    $name .= ' (' . $employee->employee_number . ')';
                }
                return [$employee->id => $name];
            });
        return view('timesheetmanagement::timesheets.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        // Check if timesheet for this employee and period already exists (including soft-deleted)
        $existing = Timesheet::withTrashed()
            ->where('employee_id', $request->employee_id)
            ->where('period', $request->period)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                // If soft-deleted, restore it and redirect to edit
                $existing->restore();
                return redirect()->route('timesheet.timesheets.edit', $existing->id)
                    ->with('success', 'Timesheet restored. You can now edit it.');
            } else {
                // If exists and not deleted, redirect to existing timesheet
                return redirect()->route('timesheet.timesheets.edit', $existing->id)
                    ->with('info', 'Timesheet for this employee and period already exists. You can edit it here.');
            }
        }

        // Calculate dates from period (21st to 20th of next month)
        $dates = $this->calculatePeriodDates($request->period);

        try {
            $timesheet = Timesheet::create([
                'employee_id' => $request->employee_id,
                'period' => $request->period,
                'start_date' => $dates['start_date'],
                'end_date' => $dates['end_date'],
            ]);
            
            // Generate daily entries
            $this->generateDailyEntries($timesheet);

            return redirect()->route('timesheet.timesheets.edit', $timesheet->id)
                ->with('success', 'Timesheet created successfully. Please fill in the daily entries.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) {
                $existing = Timesheet::where('employee_id', $request->employee_id)
                    ->where('period', $request->period)
                    ->first();
                
                if ($existing) {
                    return redirect()->route('timesheet.timesheets.edit', $existing->id)
                        ->with('info', 'Timesheet for this employee and period already exists. You can edit it here.');
                }
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the timesheet. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $timesheet = Timesheet::with('employee')->findOrFail($id);
        
        // Get unique months from database (optimized query)
        // Use Carbon-compatible date formatting
        $uniqueMonths = TimesheetEntry::where('timesheet_id', $timesheet->id)
            ->selectRaw('YEAR(entry_date) as year, MONTH(entry_date) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function($item) {
                return sprintf('%04d-%02d', $item->year, $item->month);
            })
            ->unique()
            ->values();
        
        // Load entries with database filtering (much faster than PHP filtering)
        $query = $timesheet->entries()->orderBy('entry_date');
        
        $filterMonth = $request->get('filter_month');
        if ($filterMonth) {
            $filterDate = Carbon::createFromFormat('Y-m', $filterMonth);
            $query->whereYear('entry_date', $filterDate->year)
                  ->whereMonth('entry_date', $filterDate->month);
        }
        
        $entries = $query->get();
        $timesheet->setRelation('entries', $entries);
        
        // Pass unique months for dropdown
        $timesheet->unique_months = $uniqueMonths;
        
        return view('timesheetmanagement::timesheets.show', compact('timesheet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Optimize: Load only needed columns and eager load employee
        $timesheet = Timesheet::with('employee')
            ->with(['entries' => function($query) {
                $query->select([
                    'id', 'timesheet_id', 'entry_date', 'day_of_week',
                    'start_time', 'end_time', 'mission',
                    'annual_leave', 'family_resp_fl', 'mt_pt', 'pph', 'cto', 'sick_leave', 'unpaid',
                    'absent', 'checkin', 'checkout'
                ])->orderBy('entry_date');
            }])
            ->findOrFail($id);
        
        $employees = Employee::select('id', 'fullname', 'employee_number')
            ->get()
            ->mapWithKeys(function ($employee) {
                $name = $employee->fullname;
                if ($employee->employee_number) {
                    $name .= ' (' . $employee->employee_number . ')';
                }
                return [$employee->id => $name];
            });
        
        // If no entries exist, generate them
        if ($timesheet->entries->isEmpty()) {
            $this->generateDailyEntries($timesheet);
            $timesheet->refresh();
            $timesheet->load(['entries' => function($query) {
                $query->select([
                    'id', 'timesheet_id', 'entry_date', 'day_of_week',
                    'start_time', 'end_time', 'mission',
                    'annual_leave', 'family_resp_fl', 'mt_pt', 'pph', 'cto', 'sick_leave', 'unpaid',
                    'absent', 'checkin', 'checkout'
                ])->orderBy('entry_date');
            }]);
        }
        
        return view('timesheetmanagement::timesheets.edit', compact('timesheet', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $timesheet = Timesheet::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'entries' => 'required|array',
            'entries.*.entry_date' => 'required|date',
            'entries.*.start_time' => 'nullable|date_format:H:i',
            'entries.*.end_time' => 'nullable|date_format:H:i',
            'entries.*.mission' => 'nullable|string',
            'entries.*.annual_leave' => 'nullable',
            'entries.*.family_resp_fl' => 'nullable',
            'entries.*.mt_pt' => 'nullable',
            'entries.*.pph' => 'nullable',
            'entries.*.cto' => 'nullable',
            'entries.*.sick_leave' => 'nullable',
            'entries.*.unpaid' => 'nullable',
            'entries.*.absent' => 'nullable|boolean',
            'entries.*.checkin' => 'nullable|date_format:H:i',
            'entries.*.checkout' => 'nullable|date_format:H:i',
        ]);

        // Check if timesheet for this employee and period already exists (excluding current)
        $existing = Timesheet::where('employee_id', $request->employee_id)
            ->where('period', $request->period)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Timesheet for this employee and period already exists.');
        }

        // Calculate dates from period (21st to 20th of next month)
        $dates = $this->calculatePeriodDates($request->period);

        $timesheet->update([
            'employee_id' => $request->employee_id,
            'period' => $request->period,
            'start_date' => $dates['start_date'],
            'end_date' => $dates['end_date'],
        ]);
        
        // Update entries
        if (isset($request->entries) && is_array($request->entries)) {
            foreach ($request->entries as $entryData) {
                $entry = TimesheetEntry::where('timesheet_id', $timesheet->id)
                    ->where('entry_date', $entryData['entry_date'])
                    ->first();
                
                if ($entry) {
                    // Handle checkbox values: hidden inputs send '1' or '0' as strings
                    $getCheckboxValue = function($value) {
                        return ($value == '1' || $value == 1) ? 1.0 : 0.0;
                    };
                    
                    $updateData = [
                        'start_time' => !empty($entryData['start_time']) ? $entryData['start_time'] : null,
                        'end_time' => !empty($entryData['end_time']) ? $entryData['end_time'] : null,
                        'mission' => $entryData['mission'] ?? null,
                        'annual_leave' => $getCheckboxValue($entryData['annual_leave'] ?? 0),
                        'family_resp_fl' => $getCheckboxValue($entryData['family_resp_fl'] ?? 0),
                        'mt_pt' => $getCheckboxValue($entryData['mt_pt'] ?? 0),
                        'pph' => $getCheckboxValue($entryData['pph'] ?? 0),
                        'cto' => $getCheckboxValue($entryData['cto'] ?? 0),
                        'sick_leave' => $getCheckboxValue($entryData['sick_leave'] ?? 0),
                        'unpaid' => $getCheckboxValue($entryData['unpaid'] ?? 0),
                        'absent' => isset($entryData['absent']) && ($entryData['absent'] == '1' || $entryData['absent'] == 1) ? true : false,
                        'checkin' => !empty($entryData['checkin']) ? $entryData['checkin'] : null,
                        'checkout' => !empty($entryData['checkout']) ? $entryData['checkout'] : null,
                    ];
                    $entry->update($updateData);
                }
            }
        }

        return redirect()->route('timesheet.timesheets.edit', $timesheet->id)
            ->with('success', 'Timesheet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();

        return redirect()->route('timesheet.timesheets.index')
            ->with('success', 'Timesheet deleted successfully.');
    }

    /**
     * Get timesheets by employee
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function getByEmployee($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $timesheets = Timesheet::where('employee_id', $employee_id)->orderBy('period', 'desc')->get();
        
        return view('timesheetmanagement::timesheets.by_employee', compact('employee', 'timesheets'));
    }

    /**
     * Auto-generate timesheet for employee for current month
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function autoGenerate($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        
        // Get current month in YYYY-MM format
        $currentPeriod = date('Y-m');
        
        // Check if timesheet already exists for this period (including soft-deleted)
        $existing = Timesheet::withTrashed()
            ->where('employee_id', $employee_id)
            ->where('period', $currentPeriod)
            ->first();
            
        if ($existing) {
            if ($existing->trashed()) {
                // If soft-deleted, restore it and redirect to edit
                $existing->restore();
                return redirect()->route('timesheet.timesheets.edit', $existing->id)
                    ->with('success', 'Timesheet restored. You can now edit it.');
            } else {
                // If exists and not deleted, redirect to existing timesheet
                return redirect()->route('timesheet.timesheets.edit', $existing->id)
                    ->with('info', 'Timesheet for this period already exists. You can edit it here.');
            }
        }
        
        // Calculate dates from period (21st to 20th of next month)
        $dates = $this->calculatePeriodDates($currentPeriod);
        
        try {
            // Create new timesheet
            $timesheet = Timesheet::create([
                'employee_id' => $employee_id,
                'period' => $currentPeriod,
                'start_date' => $dates['start_date'],
                'end_date' => $dates['end_date'],
            ]);
            
            // Generate daily entries
            $this->generateDailyEntries($timesheet);
            
            return redirect()->route('timesheet.timesheets.edit', $timesheet->id)
                ->with('success', 'Timesheet auto-generated successfully. Please fill in the daily entries.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) {
                $existing = Timesheet::where('employee_id', $employee_id)
                    ->where('period', $currentPeriod)
                    ->first();
                
                if ($existing) {
                    return redirect()->route('timesheet.timesheets.edit', $existing->id)
                        ->with('info', 'Timesheet for this period already exists. You can edit it here.');
                }
            }
            
            return redirect()->back()
                ->with('error', 'An error occurred while generating the timesheet. Please try again.');
        }
    }

    /**
     * Generate daily entries for a timesheet
     *
     * @param  Timesheet  $timesheet
     * @return void
     */
    private function generateDailyEntries($timesheet)
    {
        $startDate = Carbon::parse($timesheet->start_date);
        $endDate = Carbon::parse($timesheet->end_date);
        $days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        
        // Get existing entry dates to avoid duplicates
        $existingDates = TimesheetEntry::where('timesheet_id', $timesheet->id)
            ->pluck('entry_date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        
        $entriesToInsert = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Only create if doesn't exist
            if (!in_array($dateString, $existingDates)) {
                $entriesToInsert[] = [
                    'timesheet_id' => $timesheet->id,
                    'entry_date' => $dateString,
                    'day_of_week' => $days[$currentDate->dayOfWeek],
                    'start_time' => null,
                    'end_time' => null,
                    'mission' => null,
                    'annual_leave' => 0,
                    'family_resp_fl' => 0,
                    'mt_pt' => 0,
                    'pph' => 0,
                    'cto' => 0,
                    'sick_leave' => 0,
                    'unpaid' => 0,
                    'absent' => false,
                    'checkin' => null,
                    'checkout' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $currentDate->addDay();
        }
        
        // Bulk insert for better performance
        if (!empty($entriesToInsert)) {
            TimesheetEntry::insert($entriesToInsert);
        }
    }
}

