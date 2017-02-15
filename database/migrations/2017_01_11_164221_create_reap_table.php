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
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('stus_id', 8);
            $table->string('pers_id', 12);
            $table->string('pers_name', 160);
            $table->string('land_name', 50);
            $table->string('prun_name', 50);
            $table->string('ticu_name', 80);
            $table->string('vare_name', 80);
            $table->string('mere_name', 80);
            $table->boolean('reap_record');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));

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
