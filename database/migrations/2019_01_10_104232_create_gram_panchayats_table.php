<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGramPanchayatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gram_panchayats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anchalik_id')->index()->unsigned()->comment('Id of an Anchalik Parishad under which a Gram Panchayat is located.');
            $table->string('gram_panchayat_name',100)->comment('Name of a gram panchayat.');
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
        Schema::dropIfExists('gram_panchayats');
    }
}
