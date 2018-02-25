<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSynchronizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('synchronizations', function (Blueprint $table) {
            $table->renameColumn('dmrp_date_transaction', 'sync_date_transaction');
            $table->renameColumn('latitud', 'sync_latitude');
            $table->renameColumn('longitud', 'sync_longitude');
            $table->renameColumn('dmrp_device_id', 'sync_device_id');   
            $table->renameColumn('esdo_id', 'sync_status');

            $table->longText('json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('synchronizations', function (Blueprint $table) {
            $table->renameColumn('sync_date_transaction', 'dmrp_date_transaction');
            $table->renameColumn('sync_latitude', 'latitud');
            $table->renameColumn('sync_longitude', 'longitud');
            $table->renameColumn('sync_device_id', 'dmrp_device_id'); 
            $table->renameColumn('sync_status', 'esdo_id'); 

            $table->dropColumn('json');
        });
    }
}
