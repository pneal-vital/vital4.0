<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('vitaldev')->create('User_Activity', function (Blueprint $table) {
            $table->bigIncrements('activityID');
            $table->bigInteger('id');
            $table->string('classID', 85);
            $table->string('User_Name', 85);
            $table->timestamps();
            $table->string('Purpose', 85);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('vitaldev')->drop('User_Activity');
    }
}
