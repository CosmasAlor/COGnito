<?php

namespace Modules\TimesheetManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TimesheetEntry extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'entry_date' => 'date',
        'annual_leave' => 'decimal:2',
        'family_resp_fl' => 'decimal:2',
        'mt_pt' => 'decimal:2',
        'pph' => 'decimal:2',
        'cto' => 'decimal:2',
        'sick_leave' => 'decimal:2',
        'unpaid' => 'decimal:2',
        'absent' => 'boolean',
    ];

    /**
     * Get the timesheet that owns the entry.
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    /**
     * Get day name from date
     */
    public function getDayNameAttribute()
    {
        $days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        return $days[$this->entry_date->dayOfWeek] ?? '';
    }
}

