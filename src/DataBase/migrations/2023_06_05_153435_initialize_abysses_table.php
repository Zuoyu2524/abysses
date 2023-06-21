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
        
        Schema::dropIfExists('abysses_results');
        Schema::dropIfExists('abysses_jobs');
        Schema::dropIfExists('abysses_job_states');
        
        Schema::create('abysses_job_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
        });

        // Create all the possible job states.
        DB::table('abysses_job_states')->insert([
            ['name' => 'label-recognition'],
            ['name' => 'failed-label-recognition'],
            ['name' => 'retraining-proposals'],
            ['name' => 'failed-retraining-proposals'],
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

        
        Schema::create('abysses_test', function (Blueprint $table) {
            $table->increments('id')->notNullable()->unique();
            $table->string('label')->notNullable();
            $table->timestamps();
            $table->foreignId('job_id')->constrained('abysses_jobs');
            $table->foreignId('image_id')->constrained('images');
            $table->boolean('is_train');
        });
        
/*
        Schema::create('abysses_labels', function (Blueprint $table){
            $table->increments('id')->notNullable()->unique();
            $table->string('type')->notNullable();
            $table->string('name')->notNullable();
        });
        
        // Create all the possible labels.
        DB::table('abysses_labels')->insert([
            ['type'=>'Lithology', 'name' => 'Basalt'],
            ['type'=>'Lithology', 'name' => 'Slab'],
            ['type'=>'Lithology', 'name' => 'Sulfurs'],
            ['type'=>'Lithology', 'name' => 'Volcanoclastic'],
            ['type'=>'Morphology', 'name' => 'Fractured'],
            ['type'=>'Morphology', 'name' => 'Marbled'],
            ['type'=>'Morphology', 'name' => 'Scree/Rubbles'],
            ['type'=>'Morphology', 'name' => 'Sedimented'],
            ['type'=>'Morphology', 'name' => 'BP-Lava'],
            ['type'=>'SW-fragments', 'name' => '0-10%'],
            ['type'=>'SW-fragments', 'name' => '10-50%'],
            ['type'=>'SW-fragments', 'name' => '50-100%'],
        ]);
*/
        
        Schema::create('abysses_train', function (Blueprint $table) {
            $table->increments('id')->notNullable()->unique();
            $table->timestamps();
            $table->foreignId('job_id')->constrained('abysses_jobs');
            $table->json('attrs')->nullable();
            $table->float('score');
        });
        
        Schema::create('abysses_train_labels', function (Blueprint $table){
            $table->increments('id')->notNullable()->unique();
            $table->integer('train_id')->unsigned();
    	    $table->foreignId('image_id')->constrained('images');
    	    $table->json('labels')->nullable();

    	    $table->foreign('train_id')
    	    	  ->references('id')
    	    	  ->on('abysses_train')
    	    	  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abysses_train_labels');
        Schema::dropIfExists('abysses_train');
        Schema::dropIfExists('abysses_test');
        Schema::dropIfExists('abysses_labels');
        Schema::dropIfExists('abysses_jobs');
        Schema::dropIfExists('abysses_job_states');
    }
};
