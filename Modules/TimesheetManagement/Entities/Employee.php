<?php

namespace Modules\TimesheetManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = [
        'employee_number',
        'fullname',
        'national_id_number',
        'tel_no',
        'date_of_birth',
        'contract_start_date',
        'contract_end_date',
        'position',
        'salary',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the timesheets for the employee.
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    /**
     * Get formatted salary attribute.
     */
    public function getFormattedSalaryAttribute()
    {
        return number_format($this->salary, 2);
    }
}

