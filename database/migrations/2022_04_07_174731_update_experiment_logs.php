<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExperimentLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiment_logs', function (Blueprint $table) {
            $table->string('schema_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiment_logs', function (Blueprint $table) {
            $table->dropColumn('schema_name');
        });
        Schema::dropIfExists('experiment_logs');
    }
}
