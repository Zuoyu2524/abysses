<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abysses_job_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
        });

        // Create all the possible job states.
        DB::table('maia_job_states')->insert([
            ['name' => 'test-for-labels'],
            ['name' => 'retraining-for-labels'],
            ['name' => 'failed-test'],
            ['name' => 'failed-retraining'],
        ]);

        
        Schema::create('abysses_jobs', function (Blueprint $table) {
            $table->increments('id')->notNullable()->unique();

            $table->integer('volume_id')->unsigned()->index();
            $table->foreign('volume_id')
                  ->references('id')
                  ->on('volumes')
                  ->onDelete('cascade');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->timestamps();

            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')
                  ->references('id')
                  ->on('abysses_job_states')
                  ->onDelete('restrict');

            // Stores parameters for novelty detection and retraining of CNNs,
            // as well as any error messages.
            $table->json('attrs')->nullable();
        });

        
        Schema::create('abysses_results', function (Blueprint $table) {
            $table->increments('id')->notNullable()->unique();
            $table->string('label')->notNullable();
            $table->jsonb('attrs')->notNullable();
            $table->timestamps();
            $table->foreignId('job_id')->constrained('abysses_jobs');
            $table->foreignId('image_id')->constrained('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abysses_job_states');
        Schema::dropIfExists('abysses_jobs');
        Schema::dropIfExists('abysses_results');
    }
};
