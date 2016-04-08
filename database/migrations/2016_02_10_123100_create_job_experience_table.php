<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('job_experience', function (Blueprint $table) {
            $table->string('name');
            $table->unsignedInteger('id');
            $table->unsignedInteger('experience');
            $table->unsignedInteger('elapsed');
            $table->timestamp('started');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->primary(['name', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('job_experience');
    }
}
