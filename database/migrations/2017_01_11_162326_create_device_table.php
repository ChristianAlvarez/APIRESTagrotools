<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTable extends Migration
{
    public $incrementing = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->string('devi_id', 50)->primary();
            $table->string('devi_name', 50);
            $table->boolean('devi_active');
            $table->string('pers_id', 12)->references('pers_id')->on('userspicking');
            $table->boolean('devi_record');
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
        Schema::drop('device');
    }
}
