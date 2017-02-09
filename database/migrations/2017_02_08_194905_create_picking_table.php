<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picking', function (Blueprint $table) {
            $table->string('pers_id', 12)->primary();
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('pers_name', 160);
            $table->string('pick_password');
            $table->boolean('pick_active');
            $table->boolean('pick_record');
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
        Schema::drop('picking');
    }
}
