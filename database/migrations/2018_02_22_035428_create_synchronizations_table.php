<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSynchronizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synchronizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->dateTime('dmrp_date_transaction');
            $table->string('dmrp_device_id', 50)->references('devi_id')->on('device');
            $table->string('pers_id', 12)->references('pers_id')->on('picking');
            $table->string('latitud');
            $table->string('longitud');
            $table->integer('esdo_id')->nullable();   
            $table->longText('json');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('synchronizations');
    }
}
