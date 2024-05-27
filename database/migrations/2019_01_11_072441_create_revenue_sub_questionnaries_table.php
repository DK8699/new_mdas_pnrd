<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueSubQuestionnariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_sub_questionnaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('query_id')->index()->unsigned()->comment('Id of a revenue query');
            $table->text('revenue_sub_query')->comment('Revenue sub query');
            $table->integer('level_status')->unsigned()->comment('Wehter second level of a sub questionnaries exist or not. 0 means second level does not exist and 1 means second level exist.');
            $table->timestamps();
            $table->foreign('query_id')->references('id')->on('revenue_questionnaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenue_sub_questionnaries');
    }
}
