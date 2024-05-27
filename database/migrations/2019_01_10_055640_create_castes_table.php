<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('castes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caste_name',100)->comment('name of caste of people in a population');
            $table->string('column_name',100)->comment('Male or Female count coloumn name in basic information table.');
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
        Schema::dropIfExists('castes');
    }
}
