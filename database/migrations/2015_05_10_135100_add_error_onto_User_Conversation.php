<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErrorOntoUserConversation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('devaudit')->table('User_Conversation', function($table) {
            $table->text('error');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('devaudit')->table('User_Conversation', function($table) {
            $table->dropColumn('error');
        });
    }
}
