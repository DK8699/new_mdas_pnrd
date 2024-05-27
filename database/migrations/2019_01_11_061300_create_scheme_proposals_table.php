<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemeProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheme_proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicable_id')->index()->unsigned()->comment('Estimation applicable in in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->integer('act_id')->index()->unsigned()->comment('Financial year for which new schemes fund will be proposed.');
            $table->integer('entity_id')->index()->unsigned()->comment('Entity for which fund will be released in new scheme.');
            $table->double('estimated_cost')->comment('Estimated cost for entity in a financial year.');
            $table->timestamps();
            $table->foreign('act_id')->references('id')->on('acts');
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('entity_id')->references('id')->on('proposal_entities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheme_proposals');
    }
}
