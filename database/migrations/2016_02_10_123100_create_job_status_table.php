<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('job_status', function (Blueprint $table) {
            $table->string('name');
            $table->unsignedInteger('id');
            $table->string('parameters');
            $table->timestamp('requested');
            $table->unsignedInteger('attempts')->nullable();
            $table->timestamp('started')->nullable();
            $table->timestamp('completed')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->unsignedInteger('rc')->nullable();
            $table->text('results')->nullable();
            $table->primary(['name', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('job_status');
    }
}
