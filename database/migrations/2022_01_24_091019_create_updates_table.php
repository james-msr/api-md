<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->integer('value');
            $table->integer('device_num');
            $table->foreign('device_num')
                    ->references('number')
                    ->on('devices')
                    ->nullOnDelete();
            $table->enum('device_type', array_values(\App\Models\Device::TYPE));
            $table->string('measurement');
            $table->date('date')->default(now());
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
        Schema::dropIfExists('updates');
    }
}
