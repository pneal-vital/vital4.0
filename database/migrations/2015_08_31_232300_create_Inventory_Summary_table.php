<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
            * desc Inventory_Summary;
            +-------------+--------------+------+-----+---------------------+-------+
            | Field       | Type         | Null | Key | Default             | Extra |
            +-------------+--------------+------+-----+---------------------+-------+
            | objectID    | bigint(20)   | NO   | PRI | NULL                |       |
            | Client_SKU  | varchar(85)  | YES  |     | NULL                |       |
            | Description | varchar(255) | YES  |     | NULL                |       |
            | pickQty     | int(10)      | NO   |     | NULL                |       |
            | actQty      | int(10)      | NO   |     | NULL                |       |
            | resQty      | int(10)      | NO   |     | NULL                |       |
            | replenPrty  | int(10)      | YES  |     | NULL                |       |
            | created_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
            | updated_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
            +-------------+--------------+------+-----+---------------------+-------+
            9 rows in set (0.04 sec)
         */

        Schema::connection('vitaldev')->create('Inventory_Summary', function (Blueprint $table) {
            $table->bigInteger('objectID');
            $table->string('Client_SKU', $length=85)->nullable();
            $table->string('Description', $length=255)->nullable();
            $table->integer('pickQty')->default(0);
            $table->integer('actQty')->default(0);
            $table->integer('resQty')->default(0);
            $table->integer('replenPrty')->nullable();
            $table->timestamps();
            $table->primary(['objectID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('vitaldev')->drop('Inventory_Summary');
    }
}
