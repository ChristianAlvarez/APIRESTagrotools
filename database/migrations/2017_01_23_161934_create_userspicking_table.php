<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserspickingTable extends Migration
{
    public $incrementing = false;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userspicking', function (Blueprint $table) {
            $table->string('pers_id', 12)->primary();
            $table->string('pers_name', 160);
            $table->string('uspi_password', 160);
            $table->boolean('uspi_active');
            $table->boolean('uspi_record');
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
        Schema::drop('userspicking');
    }
    
}
