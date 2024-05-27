<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVcdcVdcMacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vcdc_vdc_macs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('block_id')->index()->unsigned()->comment('block id under which VCDC, VDC and MAC exists.');
            $table->string('vcdc_vdc_mac_name',100)->comment('Name of VCDC, VDC and MAC under sixth schedule districts.');
            $table->timestamps();
            $table->foreign('id')->references('id')->on('blocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vcdc_vdc_macs');
    }
}
