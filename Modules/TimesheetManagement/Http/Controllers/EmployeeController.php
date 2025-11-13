<?php

namespace Modules\TimesheetManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TimesheetManagement\Entities\Employee;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::select('*');

            return DataTables::of($employees)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('timesheet.employees.show', $row->id) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                    $html .= '<a href="' . route('timesheet.employees.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $html .= '<form action="' . route('timesheet.employees.destroy', $row->id) . '" method="POST" class="d-inline">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>';
                    $html .= '</form>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('employee_number', function ($row) {
                    return $row->employee_number ?? '-';
                })
                ->editColumn('contract_start_date', function ($row) {
                    return $row->contract_start_date ? $row->contract_start_date->format('Y-m-d') : '-';
                })
                ->editColumn('contract_end_date', function ($row) {
                    return $row->contract_end_date ? $row->contract_end_date->format('Y-m-d') : '-';
                })
                ->editColumn('date_of_birth', function ($row) {
                    return $row->date_of_birth ? $row->date_of_birth->format('Y-m-d') : '-';
                })
                ->editColumn('salary', function ($row) {
                    return $row->salary ? number_format($row->salary, 2) : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('timesheetmanagement::employees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('timesheetmanagement::employees.create');
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
            'employee_number' => 'nullable|string|max:255|unique:employees,employee_number',
            'fullname' => 'required|string|max:255',
            'national_id_number' => 'required|string|unique:employees,national_id_number',
            'tel_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
        ]);

        Employee::create($request->all());

        return redirect()->route('timesheet.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::with('timesheets')->findOrFail($id);
        return view('timesheetmanagement::employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('timesheetmanagement::employees.edit', compact('employee'));
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
        $employee = Employee::findOrFail($id);

        $request->validate([
            'employee_number' => 'nullable|string|max:255|unique:employees,employee_number,' . $id,
            'fullname' => 'required|string|max:255',
            'national_id_number' => 'required|string|unique:employees,national_id_number,' . $id,
            'tel_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
        ]);

        $employee->update($request->all());

        return redirect()->route('timesheet.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('timesheet.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}

