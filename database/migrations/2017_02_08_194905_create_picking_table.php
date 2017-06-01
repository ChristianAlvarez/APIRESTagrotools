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
            $table->increments('id');
            $table->string('pers_id', 12)->unique();
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('pers_name', 160);
            $table->string('password');
            $table->boolean('pick_active');
            $table->boolean('pick_record');
            $table->dateTime('last_conection')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->boolean('row_mode');
            $table->rememberToken();
            
            //$table->primary(array('id', 'pers_id', 'cpny_id'));
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
