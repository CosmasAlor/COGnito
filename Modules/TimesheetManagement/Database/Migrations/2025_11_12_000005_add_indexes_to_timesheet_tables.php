<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToTimesheetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('period');
        });

        Schema::table('timesheet_entries', function (Blueprint $table) {
            $table->index('timesheet_id');
            $table->index('entry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['period']);
        });

        Schema::table('timesheet_entries', function (Blueprint $table) {
            $table->dropIndex(['timesheet_id']);
            $table->dropIndex(['entry_date']);
        });
    }
}

