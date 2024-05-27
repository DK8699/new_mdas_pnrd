<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_expenditures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expenditure_id')->index()->unsigned()->comment('id of a expenditure.');
            $table->integer('act_id')->index()->unsigned()->comment('id of a financial year of which expenditure record will be kept.');
            $table->integer('applicable_id')->index()->unsigned()->comment('Id of a applicability on a Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->double('expenditure_cost')->comment('expenditure cost of an expenditure.');
            $table->timestamps();
            $table->foreign('act_id')->references('id')->on('acts');
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('expenditure_id')->references('id')->on('expenditures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_expenditures');
    }
}
