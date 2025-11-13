<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeNumberToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('employees', 'employee_number')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('employee_number')->nullable()->after('id');
            });
            
            // Add unique index separately for better compatibility
            Schema::table('employees', function (Blueprint $table) {
                $table->unique('employee_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('employee_number');
        });
    }
}

