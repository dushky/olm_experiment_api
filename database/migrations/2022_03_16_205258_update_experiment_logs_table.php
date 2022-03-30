<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExperimentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('experiment_logs', function (Blueprint $table) {
            $table->string('software_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('experiment_logs', function (Blueprint $table) {
            $table->dropColumn('software_name');
        });
        Schema::dropIfExists('experiment_logs');
    }
}
