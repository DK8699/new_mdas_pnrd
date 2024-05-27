<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueQuestionnariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_questionnaries', function (Blueprint $table) {
            $table->increments('id');
            $table->text('revenue_query')->comment('Revenue query releated to revenue');
            $table->integer('level_status')->unsigned()->comment('Wehter sub query level of a sub questionnaries exist or not. 0 means sub query level does not exist and 1 means sub query level exist.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenue_questionnaries');
    }
}
