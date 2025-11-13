<?php

namespace Modules\TimesheetManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the employee that owns the timesheet.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the timesheet entries (daily rows).
     */
    public function entries()
    {
        return $this->hasMany(TimesheetEntry::class)->orderBy('entry_date');
    }

    /**
     * Get monthly totals for all leave types
     */
    public function getMonthlyTotals()
    {
        // Use database aggregation for better performance
        $totals = $this->entries()
            ->selectRaw('
                SUM(annual_leave) as annual_leave,
                SUM(family_resp_fl) as family_resp_fl,
                SUM(mt_pt) as mt_pt,
                SUM(pph) as pph,
                SUM(cto) as cto,
                SUM(sick_leave) as sick_leave,
                SUM(unpaid) as unpaid,
                SUM(CASE WHEN absent = 1 THEN 1 ELSE 0 END) as absent_count,
                COUNT(*) as total_days
            ')
            ->first();

        return [
            'annual_leave' => (float) ($totals->annual_leave ?? 0),
            'family_resp_fl' => (float) ($totals->family_resp_fl ?? 0),
            'mt_pt' => (float) ($totals->mt_pt ?? 0),
            'pph' => (float) ($totals->pph ?? 0),
            'cto' => (float) ($totals->cto ?? 0),
            'sick_leave' => (float) ($totals->sick_leave ?? 0),
            'unpaid' => (float) ($totals->unpaid ?? 0),
            'absent_count' => (int) ($totals->absent_count ?? 0),
            'total_days' => (int) ($totals->total_days ?? 0),
        ];
    }
}

