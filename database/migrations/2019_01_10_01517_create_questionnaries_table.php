<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicable_id')->index()->unsigned()->nullable()->comment('question either applicable to Block, Council, and VCDC or VDC');
            $table->text('question');
            $table->timestamps();
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
        Schema::dropIfExists('questionnaires');
    }
}
