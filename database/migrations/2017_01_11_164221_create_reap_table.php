<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reap', function (Blueprint $table) {
            $table->string('reap_id', 20);
            $table->string('cpny_id', 12);
            $table->string('stus_id', 8);
            $table->string('pers_id', 12);
            $table->string('pers_name', 160);
            $table->string('land_name', 50);
            $table->string('prun_name', 50);
            $table->string('ticu_name', 80);
            $table->string('vare_name', 80);
            $table->string('mere_name', 80);
            $table->boolean('reap_record');
            $table->timestamps();

            $table->primary(array('reap_id', 'cpny_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reap');
    }
}
