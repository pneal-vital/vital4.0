<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use vital40\UserConversation;

class UserConversationTableSeeder extends Seeder {

    public function run()
    {
        DB::connection('devaudit')->table('User_Conversation')->delete();

        /*
         * select * from User_Conversation;
        +------------+------------+------------+-----------+-------------+---------------------+---------------------+-----------------------------------+
        | activityID | POD        | Article    | User_Name | Sender_Name | created_at          | updated_at          | Text                              |
        +------------+------------+------------+-----------+-------------+---------------------+---------------------+-----------------------------------+
        |          1 | 6232063899 | 6217093230 | pneal     | VITaL4      | 2015-03-18 22:20:51 | 2015-03-18 22:25:02 | Please enter UPC #                |
        |          2 | 6232063899 | 6217093230 | pneal     | pneal       | 2015-03-18 22:21:26 | 2015-03-18 22:28:23 | no                                |
        |          3 | 6232063899 | 6217093230 | pneal     | VITaL4      | 2015-03-18 22:27:10 | 2015-03-18 22:27:10 | I said, enter the dam UPC number! |
        |          4 | 6232063899 | 6217093230 | pneal     | pneal       | 2015-03-18 22:27:55 | 2015-03-18 22:27:55 | get lost                          |
        |          5 | 6232063899 | 6217093230 | pneal     | pneal       | 2015-03-19 00:25:19 | 2015-03-19 00:25:19 | 63664347362                       |
        +------------+------------+------------+-----------+-------------+---------------------+---------------------+-----------------------------------+
        5 rows in set (0.00 sec)
         */
        UserConversation::create(['POD' => '6232063899'  , 'Article' => '6217093230', 'User_Name' => 'pneal', 'Sender_Name' => 'VITaL4', 'Text' => 'Please enter UPC #']);
        UserConversation::create(['POD' => '6232063899'  , 'Article' => '6217093230', 'User_Name' => 'pneal', 'Sender_Name' => 'pneal' , 'Text' => 'no']);
        UserConversation::create(['POD' => '6232063899'  , 'Article' => '6217093230', 'User_Name' => 'pneal', 'Sender_Name' => 'VITaL4', 'Text' => 'I said, enter the UPC number!']);
        UserConversation::create(['POD' => '6232063899'  , 'Article' => '6217093230', 'User_Name' => 'pneal', 'Sender_Name' => 'pneal' , 'Text' => 'noway']);
        UserConversation::create(['POD' => '6232063899'  , 'Article' => '6217093230', 'User_Name' => 'pneal', 'Sender_Name' => 'VITaL4', 'Text' => 'Way!']);
    }

}
