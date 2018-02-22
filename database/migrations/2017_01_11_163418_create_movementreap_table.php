<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementreapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movementreap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reap_id', 20)->references('reap_id')->on('reap');
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('dmrp_card_identification', 50);
            $table->decimal('dtrp_received_pay_units', 10, 2);
            $table->decimal('dmrp_received_amount', 10, 2);
            $table->dateTime('dmrp_date_transaction');
            $table->boolean('modc_input');
            $table->string('pers_id', 12)->references('pers_id')->on('picking');
            $table->boolean('more_record');
            $table->string('dmrp_device_id', 50)->references('devi_id')->on('device');
            $table->integer('esdo_id')->nullable();
            $table->dateTime('dmrp_date');
            $table->string('synchronizations_id', 50)->references('synchronizations')->on('id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));

            //$table->primary(array('reap_id', 'cpny_id', 'dmrp_card_identification'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movementreap');
    }
}
