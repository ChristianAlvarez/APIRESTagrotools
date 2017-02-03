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
            $table->string('reap_id', 20)->references('reap_id')->on('reap');
            $table->string('cpny_id', 12);
            $table->string('dmrp_card_identification', 50);
            $table->decimal('dtrp_received_pay_units', 10, 2);
            $table->decimal('dmrp_received_amount', 10, 2);
            $table->dateTime('dmrp_date_transaction');
            $table->boolean('modc_input');
            $table->string('pers_id', 15)->references('pers_id')->on('userspicking');
            $table->boolean('more_record');
            $table->string('dmrp_device_id', 50)->references('devi_id')->on('device');
            $table->timestamps();

            $table->primary(array('reap_id', 'cpny_id', 'dmrp_card_identification'));
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
