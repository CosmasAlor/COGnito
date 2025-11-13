<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('timesheet_id');
            $table->date('entry_date');
            $table->string('day_of_week', 10); // SUN, MON, TUE, etc.
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('mission')->nullable();
            $table->decimal('annual_leave', 8, 2)->default(0); // days
            $table->decimal('family_resp_fl', 8, 2)->default(0); // days
            $table->decimal('mt_pt', 8, 2)->default(0); // days
            $table->decimal('pph', 8, 2)->default(0); // days
            $table->decimal('cto', 8, 2)->default(0); // days
            $table->decimal('sick_leave', 8, 2)->default(0); // days
            $table->decimal('unpaid', 8, 2)->default(0); // days
            $table->boolean('absent')->default(false);
            $table->time('checkin')->nullable();
            $table->time('checkout')->nullable();
            $table->timestamps();
            
            $table->foreign('timesheet_id')->references('id')->on('timesheets')->onDelete('cascade');
            $table->unique(['timesheet_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_entries');
    }
}

