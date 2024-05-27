<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_revenues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('query_id')->index()->unsigned()->comment('Id of a revenue query.');
            $table->integer('applicable_id')->index()->unsigned()->comment('Revenue applicable in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->text('own_revenue_name')->comment('Revenue name for own revenues');
            $table->timestamps();
            $table->foreign('applicable_id')->references('id')->on('applicables');
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
        Schema::dropIfExists('own_revenues');
    }
}
