<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserConversationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('devaudit')->create('User_Conversation', function (Blueprint $table) {
            $table->bigIncrements('activityID');
            $table->bigInteger('POD');
            $table->bigInteger('Article');
            $table->string('User_Name', 85);
            $table->string('Sender_Name', 85);
            $table->timestamps();
            $table->text('Text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('devaudit')->drop('User_Conversation');
    }
}
