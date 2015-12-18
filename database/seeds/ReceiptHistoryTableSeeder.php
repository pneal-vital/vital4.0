<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use vital40\ReceiptHistory;

class ReceiptHistoryTableSeeder extends Seeder {

    public function run()
    {
        DB::connection('devaudit')->table('Receipt_History')->delete();

        /*
         * desc Receipt_History;
        +------------+---------------------+------+-----+---------------------+----------------+
        | Field      | Type                | Null | Key | Default             | Extra          |
        +------------+---------------------+------+-----+---------------------+----------------+
        | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
        | PO         | bigint(20)          | NO   |     | NULL                |                |
        | POD        | bigint(20)          | NO   |     | NULL                |                |
        | Article    | bigint(20)          | YES  |     | NULL                |                |
        | UPC        | bigint(20)          | YES  |     | NULL                |                |
        | Inventory  | bigint(20)          | YES  |     | NULL                |                |
        | Tote       | bigint(20)          | YES  |     | NULL                |                |
        | Cart       | bigint(20)          | YES  |     | NULL                |                |
        | Location   | bigint(20)          | YES  |     | NULL                |                |
        | User_Name  | varchar(85)         | NO   |     | NULL                |                |
        | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
        | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
        | Activity   | text                | NO   |     | NULL                |                |
        +------------+---------------------+------+-----+---------------------+----------------+
        11 rows in set (0.01 sec)
         */
        
        ReceiptHistory::create(['PO' => '6232063897', 'POD' => '6232063899', 'Article' => '6217093230', 'UPC' => '6217092826',
            'Inventory' => '6231963444', 'Tote' => '6231978189', 'Cart' => '6216954640', 'Location' => '',
            'User_Name' => 'pneal', 'Activity' => 'Received UPC into Tote - 2015-02-20 01:05:08, UPC# 63664347409, Tote# 52 0030 3099, (1 of 10)']);
        ReceiptHistory::create(['PO' => '6232063897', 'POD' => '6232063899', 'Article' => '6217093230', 'UPC' => '6217092826',
            'Inventory' => '6231963444', 'Tote' => '6231978189', 'Cart' => '6216954640', 'Location' => '',
            'User_Name' => 'pneal', 'Activity' => 'Received UPC into Tote - 2015-02-20 01:05:12, UPC# 63664347409, Tote# 52 0030 3099, (2 of 10)']);
        ReceiptHistory::create(['PO' => '6232063897', 'POD' => '6232063899', 'Article' => '6217093230', 'UPC' => '6217092826',
            'Inventory' => '6231963444', 'Tote' => '6231978189', 'Cart' => '6216954640', 'Location' => '',
            'User_Name' => 'pneal', 'Activity' => 'Received UPC into Tote - 2015-02-20 01:05:17, UPC# 63664347409, Tote# 52 0030 3099, (3 of 10)']);
        ReceiptHistory::create(['PO' => '6232063897', 'POD' => '6232063899', 'Article' => '6217093230', 'UPC' => '6217092826',
            'Inventory' => '6231963444', 'Tote' => '6231978189', 'Cart' => '6216954640', 'Location' => '',
            'User_Name' => 'pneal', 'Activity' => 'Received UPC into Tote - 2015-02-20 01:05:23, UPC# 63664347409, Tote# 52 0030 3099, (4 of 10)']);

    }

}
