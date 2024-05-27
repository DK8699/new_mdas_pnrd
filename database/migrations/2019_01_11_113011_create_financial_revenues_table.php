<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_revenues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('query_id')->index()->unsigned()->nullable()->comment('If query revenue record is to be kept.');
            $table->integer('sub_query_id')->index()->unsigned()->nullable()->comment('If sub query revenue record is to be kept.');
            $table->integer('own_revenue_id')->index()->unsigned()->nullable()->comment('If own revenue record is to be kept.');
            $table->integer('share_type_id')->index()->unsigned()->nullable()->comment('If CSS share revenue record is kept then this coloumn will identify whether it is Central or State.');
            $table->integer('shared_css_id')->index()->unsigned()->nullable()->comment('If CSS share revenue record is kept.');
            $table->integer('grant_id')->index()->unsigned()->nullable()->comment('If Finance commision grant revenue record is to be kept.');
            $table->integer('award_id')->index()->unsigned()->nullable()->comment('Finance commision award revenue record is to be kept.');
            $table->integer('act_id')->index()->unsigned()->nullable()->comment('Financial year for which revenue record is to be kept.');
            $table->integer('applicable_id')->index()->unsigned()->comment('Financial applicable in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->double('revenue_cost')->comment('revenue cost of a financial year under provided revenue sources.');
            $table->timestamps();
            $table->foreign('query_id')->references('id')->on('revenue_questionnaries');
            $table->foreign('sub_query_id')->references('id')->on('revenue_sub_questionnaries');
            $table->foreign('own_revenue_id')->references('id')->on('own_revenues');
            $table->foreign('shared_css_id')->references('id')->on('css_shares');
            $table->foreign('grant_id')->references('id')->on('finance_commissions');
            $table->foreign('award_id')->references('id')->on('finance_commission_awards');
            $table->foreign('act_id')->references('id')->on('acts');
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('share_type_id')-> references('id')->on('css_share_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_revenues');
    }
}
