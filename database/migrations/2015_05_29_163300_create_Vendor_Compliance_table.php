<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('devaudit')->create('Vendor_Compliance', function (Blueprint $table) {
            $table->bigIncrements('activityID');
            $table->bigInteger('vendorID');
            $table->bigInteger('poID');
            $table->bigInteger('podID');
            $table->bigInteger('articleID');
            $table->bigInteger('upcID');
            $table->integer('expectedQty');
            $table->integer('receivedQty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('devaudit')->drop('Vendor_Compliance');
    }
}
