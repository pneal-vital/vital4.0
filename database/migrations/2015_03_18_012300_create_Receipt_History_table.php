<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('devaudit')->create('Receipt_History', function (Blueprint $table) {
            $table->bigIncrements('activityID');
            $table->bigInteger('PO');
            $table->bigInteger('POD')->nullable();
            $table->bigInteger('Article')->nullable();
            $table->bigInteger('UPC')->nullable();
            $table->bigInteger('Inventory')->nullable();
            $table->bigInteger('Tote')->nullable();
            $table->bigInteger('Cart')->nullable();
            $table->bigInteger('Location')->nullable();
            $table->string('User_Name', 85);
            $table->timestamps();
            $table->text('Activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('devaudit')->drop('Receipt_History');
    }
}
