<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCssSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('css_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('query_id')->index()->unsigned()->comment('Id of a revenue query.');
            $table->text('scheme_name')->comment('name of a scheme under State and Central share of CSS');
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
        Schema::dropIfExists('css_shares');
    }
}
