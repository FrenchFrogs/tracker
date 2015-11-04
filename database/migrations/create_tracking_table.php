<?php

use FrenchFrogs\Laravel\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Purging old tables in case of errors
         */
        $this->down();

        /**
         * Creating tracking table
         */
        Schema::create('tracking', function(Blueprint $table){

            $table->binaryUuid('tracking_id')->primary();
            $table->string('name', 128);
            $table->string('tracking_hash', 15)->unique();
            $table->boolean('is_active')->default(0);
            $table->timestamp('created_at');
            $table->index('tracking_hash');
        });

        /**
         * Creating tracking_log table
         */
        Schema::create('tracking_log', function(Blueprint $table){

            $table->binaryUuid('tracking_log_id')->primary();
            $table->binaryUuid('tracking_id');
            $table->text('tracking_data')->nullable();
            $table->string('ip', 32)->nullable();
            $table->text('browser')->nullable();
            $table->text('useragent')->nullable();
            $table->boolean('is_mobile');
            $table->boolean('is_tablet');

            $table->timestamp('created_at');

            $table->foreign('tracking_id')->references('tracking_id')->on('tracking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_log');
        Schema::dropIfExists('tracking');
    }
}
