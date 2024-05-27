<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designation_applicables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicable_id')->index()->unsigned()->comment('Id of applicable wehter designation belongs to Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->integer('designation_id')->index()->unsigned()->comment('Id of a designation.');
            $table->timestamps();
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('designation_id')->references('id')->on('designations');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designation_applicables');
    }
}
