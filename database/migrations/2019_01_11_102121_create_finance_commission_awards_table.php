<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceCommissionAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_commission_awards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_query_id')->index()->unsigned()->comment('Id of a revenue sub query.');
            $table->text('award_name')->comment('Name of state finance commission awards.');
            $table->timestamps();
            $table->foreign('sub_query_id')->references('id')->on('revenue_sub_questionnaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_commission_awards');
    }
}
