<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicable_id')->index()->unsigned()->comment('salary questionary applicable within block, council or VCDC/VDC');
            $table->integer('designation_id')->index()->unsigned()->comment('designation for which salary summary is to be kept. ');
            $table->integer('act_id')->index()->unsigned()->comment('financial year for which salary summary will be recorded.');
            $table->double('salary_amount')->comment('summation of salary in a finanacial year.');
            $table->timestamps();
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->foreign('act_id')->references('id')->on('acts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_salaries');
    }
}
