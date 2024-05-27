<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnchalikParishadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anchalik_parishads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zila_id')->index()->comment('Id of a zila parishad under which an anchalik parishad belongs.');
            $table->string('anchalik_parishad_name',100)->comment('Name of an anchalik parishads.');
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
        Schema::dropIfExists('anchalik_parishads');
    }
}
