<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicable_id')->index()->unsigned()->comment('salary questionary applicable within block, council or VCDC/VDC');
            $table->integer('designation_id')->index()->unsigned()->comment('designation for which salary summary is to be kept. ');
            $table->integer('sanctioned_post')->unsigned()->comment('no of post sanctioned against a designation in council,block and VCDC or VDC');
            $table->string('scale_pay',100)->comment('payment band of a designation.');
            $table->double('consolidated_pay')->comment('consolidated payment under a designation.');
            $table->integer('vacant_post')->unsigned()->comment('no of post vaccaned against a designation in council,block and VCDC or VDC');
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
        Schema::dropIfExists('staff_details');
    }
}
