<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboundOrderDetailAdditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('vitaldev')->create('Inbound_Order_Detail_Additional', function (Blueprint $table) {
            $table->bigInteger('objectID');
            $table->string('Name', 85);
            $table->text('Value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('vitaldev')->drop('Inbound_Order_Detail_Additional');
    }
}
