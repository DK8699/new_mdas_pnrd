<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('act_id')->index()->unsigned()->comment('Id of a financial year for which balance record will be kept.');
            $table->integer('applicable_id')->index()->unsigned()->comment('Balances in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->double('opening_balance')->comment('Opening balance of a financial year');
            $table->double('inflow_balance')->comment('Inflow balance of a financial year');
            $table->double('outflow_balance')->comment('Outflow balance of a financial year.');
            $table->timestamps();
            $table->foreign('act_id')->references('id')->on('acts');
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
        Schema::dropIfExists('financial_balances');
    }
}
