<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformanceTallyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * {{--
            * desc Performance_Tally;
            +----------------+---------------------+------+-----+---------------------+----------------+
            | Field          | Type                | Null | Key | Default             | Extra          |
            +----------------+---------------------+------+-----+---------------------+----------------+
            | recordID       | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
            | dateStamp      | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
            | userName       | varchar(45)         | NO   |     | NULL                |                |
            | receivedUnits  | int(11)             | NO   |     | NULL                |                | <== populated by ArticleFlow.putUPCsIntoTote(..)
            | putAwayRec     | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.takePutAwayJob(..)
            | putAwayRplComb | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.takePutAwayJob(..)
            | putAwayRplSngl | int(11)             | NO   |     | NULL                |                | <== populated by gunApp3.takePutAwayJob(..)
            | putAwayReserve | int(11)             | NO   |     | NULL                |                | <== populated by
            | replenTotes    | int(11)             | NO   |     | NULL                |                | <== populated by
            +----------------+---------------------+------+-----+---------------------+----------------+
            9 rows in set (0.01 sec)

            --}}
         */
        Schema::connection('vitaldev')->create('Performance_Tally', function (Blueprint $table) {
            $table->bigIncrements('recordID');
            $table->timestamp('dateStamp');
            $table->string('userName', $length=45);
            $table->integer('receivedUnits');
            $table->integer('putAwayRec');
            $table->integer('putAwayRplComb');
            $table->integer('putAwayRplSngl');
            $table->integer('putAwayReserve');
            $table->integer('replenTotes');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('vitaldev')->drop('Performance_Tally');
    }
}
