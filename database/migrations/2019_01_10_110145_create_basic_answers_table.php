<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->index()->unsigned()->nullable()->comment('id of a district');
            $table->integer('council_id')->index()->unsigned()->nullable()->comment('id of a council');
            $table->integer('block_id')->index()->unsigned()->nullable()->comment('id of a block');
            $table->integer('vcdc_id')->index()->unsigned()->nullable()->comment('id of a VCDC, VDC or MAC parishad');
            $table->integer('zila_id')->index()->unsigned()->nullable()->comment('id of a zila parishad');
            $table->integer('anchalik_id')->index()->unsigned()->nullable()->comment('id of a anchalik parishad');
            $table->integer('gram_panchayat_id')->index()->unsigned()->nullable()->comment('id of a gaon panchayat');
            $table->integer('applicable_id')->index()->unsigned()->comment('id of applicabilities either Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->integer('questionnary_id')->index()->unsigned()->comment('id of  a questionnary of which answer is');
            $table->date('election_date')->nullable()->comment('date on which election held on a Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->char('building_status')->nullable()->comment('building existence status of a Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat. Y for yes and N for no.');
            $table->double('office_rent')->nullable()->comment('monthly rent of office building if own building is not present of a Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat');
            $table->double('arrear_salary')->nullable()->comment('Arrear salary if released then its value as on 31-03-2018 ');
            $table->integer('muster_roll')->unsigned()->nullable()->comment('Count of muster roll employees.');
            $table->integer('fixed_pay')->unsigned()->nullable()->comment('Count of fixed pay employees as on 31-03-2018.');
            $table->double('black_topped')->nullable()->comment('length of black topped roads in Km.');
            $table->double('gravelled')->nullable()->comment('length of gravelled roads in Km.');
            $table->double('earthen')->nullable()->comment('length of earthen roads in Km.');
            $table->double('paver_block')->nullable()->comment('length of Paver Blocks roads in Km.');
            $table->text('account_audit_status')->comment('Present status of account audit status in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat.');
            $table->char('own_account_staff')->comment('Own account staff existence status in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat. Y means exist and N means not exist.');
            $table->char('register_maintain_status')->comment('Register maintain status in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat. Y means exist and N means not exist.');
            $table->char('seperate_cashbook_status')->comment('Seperate cashbook status maintained in Council, Block, VCDC/VDC/MAC, Zila parishad, Anchalik Parishad or Gaon Panchayat. Y means exist and N means not exist.');
            $table->integer('male_count')->comment('Male population count on based on 2011 census');
            $table->integer('female_count')->comment('Female population count on based on 2011 census');
            $table->integer('sc_count')->comment('SC population count on based on 2011 census');
            $table->integer('st_count')->comment('ST population count on based on 2011 census');
            $table->integer('year')->comment('Census year of population count.');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('council_id')->references('id')->on('councils');
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->foreign('vcdc_id')->references('id')->on('vcdc_vdc_macs');
            $table->foreign('zila_id')->references('id')->on('zila_parishads');
            $table->foreign('anchalik_id')->references('id')->on('anchalik_parishads');
            $table->foreign('gram_panchayat_id')->references('id')->on('gram_panchayats');
            $table->foreign('applicable_id')->references('id')->on('applicables');
            $table->foreign('questionnary_id')->references('id')->on('questionnaires');
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
        Schema::dropIfExists('basic_answers');
    }
}
