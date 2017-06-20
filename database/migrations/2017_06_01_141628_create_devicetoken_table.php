<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicetokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('devicetoken', function (Blueprint $table) {
            $table->increments('id');
            $table->string('devi_id', 50);
            $table->string('devi_token')->nullable();
            $table->boolean('devi_active')->nullable();
            $table->string('pers_id', 12);
            $table->dateTime('last_conection')->nullable();
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
        Schema::drop('devicetoken');
    }
}
