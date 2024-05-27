<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSixFinanceFinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('six_finance_finals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_code',100);
            $table->integer('applicable_id')->index()->unsigned()->nullable()->comment('Id of a applicable');
            $table->integer('district_id')->index()->unsigned()->comment('Id of a district');
            $table->integer('zila_id')->index()->unsigned()->nullable()->comment('Id of a zila parishad.');
            $table->integer('anchalik_id')->index()->unsigned()->nullable()->comment('Id of a anchalik parishads');
            $table->integer('gram_panchayat_id')->index()->unsigned()->nullable()->comment('Id of a Gram Panchayat');
            $table->integer('block_id')->index()->unsigned()->nullable()->comment('Id of a Block');
            $table->integer('council_id')->index()->unsigned()->nullable()->comment('Id of a Council Id');
            $table->integer('vdc_id')->index()->unsigned()->nullable()->comment('Id of a VCD/VCDC/MACS');
            $table->boolean('basic_info')->default(0)->comment('Status of a basic information data completed');
            $table->boolean('staff_info')->default(0)->comment('Status of a staff information data completed');
            $table->boolean('revenue_info')->default(0)->comment('Status of a revenue info completion');
            $table->boolean('expenditure_info')->default(0)->comment('Status of a expenditure info completion');
            $table->boolean('balance_info')->default(0)->comment('Status of a balance info completion');
            $table->boolean('other_info')->default(0)->comment('Status of a other info completion');
            $table->boolean('five_year_info')->default(0)->comment('Status of a five year info completion');
            $table->boolean('final_submission_status')->default(0)->comment('Status of a five year info completion');
            $table->timestamps();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('zila_id')->references('id')->on('zila_parishads');
            $table->foreign('anchalik_id')->references('id')->on('anchalik_parishads');
            $table->foreign('council_id')->references('id')->on('councils');
            $table->foreign('vdc_id')->references('id')->on('vcd_vcdc_macs');
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->foreign('gram_panchayat_id')->references('id')->on('gram_panchayats');
            $table->foreign('applicable_id')->references('id')->on('applicables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('six_finance_finals');
    }
}
