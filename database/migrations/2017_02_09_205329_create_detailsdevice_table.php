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
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('pers_id', 12)->references('pers_id')->on('picking');
            $table->boolean('dtde_active');
            $table->boolean('dtde_record');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));

            $table->primary(array('devi_id', 'cpny_id', 'pers_id'));
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
