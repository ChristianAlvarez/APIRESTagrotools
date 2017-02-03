<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserspickingcompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userspickingcompany', function (Blueprint $table) {
            $table->string('cpny_id');
            $table->string('pers_id', 12)->references('pers_id')->on('userspicking');
            $table->string('cpny_name', 200);
            $table->boolean('cpny_active');
            $table->boolean('cpny_record');
            $table->timestamps();

            $table->primary(array('cpny_id', 'pers_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userspickingcompany');
    }
}
