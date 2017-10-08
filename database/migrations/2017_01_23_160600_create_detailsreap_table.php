<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsreapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailsreap', function (Blueprint $table) {
            $table->string('reap_id', 20)->references('reap_id')->on('reap');
            $table->string('cpny_id', 12)->references('cpny_id')->on('company');
            $table->string('card_identification', 50);
            $table->string('pers_id', 20);
            $table->string('pers_name', 160);
            $table->string('quad_name', 80);
            $table->boolean('dere_status_card');
            $table->boolean('dere_record');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->boolean('row_mode');
            $table->integer('dtrp_line_number');

            $table->primary(array('reap_id', 'cpny_id', 'card_identification'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detailsreap');
    }
}
