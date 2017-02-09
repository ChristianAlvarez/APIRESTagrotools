<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsdeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailsdevice', function (Blueprint $table) {
            $table->string('devi_id', 50);
            $table->string('pers_id', 12)->references('pers_id')->on('picking');
            $table->boolean('dtde_active');
            $table->boolean('dtde_record');
            $table->timestamps();

            $table->primary(array('devi_id', 'pers_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detailsdevice');
    }
}
