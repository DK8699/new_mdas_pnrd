<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('council_id')->index()->unsigned()->nullable()->comment('Only for sixth schedule districts belonging to a autonomous council.');
            $table->string('district_name',100)->comment('name of a district.');
            $table->integer('status')->usigned()->default(0)->comment('1 for Sixth Schedule districts status and 0 for PRI districts.');
            $table->timestamps();
            $table->foreign('council_id')->references('id')->on('councils');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
