<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->string('national_id_number')->unique();
            $table->string('tel_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            $table->string('position')->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}

