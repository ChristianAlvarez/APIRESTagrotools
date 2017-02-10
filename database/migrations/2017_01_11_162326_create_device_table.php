<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->string('devi_id', 50);
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('devi_name', 50);
            $table->boolean('devi_active');
            $table->boolean('devi_record');
            $table->timestamps();

            $table->primary(array('devi_id', 'cpny_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('device');
    }
}
