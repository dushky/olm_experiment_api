<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperimentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->text('input_arguments');
            $table->string('output_path');
            $table->integer('process_pid');
            $table->dateTime("started_at")->nullable()->default(null);
            $table->dateTime("finished_at")->nullable()->default(null);
            $table->dateTime("stopped_at")->nullable()->default(null);
            $table->dateTime("timedout_at")->nullable()->default(null);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiments_logs');
    }
}
