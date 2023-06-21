<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropTableall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('abysses_results');
        Schema::dropIfExists('abysses_jobs');
        Schema::dropIfExists('abysses_job_states');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            // ...
    }
}

