<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_query_id')->index()->unsigned()->comment('Id of a revenue sub query.');
            $table->text('grant_name')->comment('Name of finance grant of financial commission.');
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
        Schema::dropIfExists('finance_commissions');
    }
}
