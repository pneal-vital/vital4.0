<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustPermission as Permissions;

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        /*
         * select * from permissions;
        +----+---------------------------+---------------------------+---------------------------------------------+---------------------+---------------------+
        | id | name                      | display_name              | description                                 | created_at          | updated_at          |
        +----+---------------------------+---------------------------+---------------------------------------------+---------------------+---------------------+
        |  1 | inboundOrder.index        | InboundOrder List         | Filtered List of Inbound Orders             | 2015-02-19 12:49:53 | 2015-02-19 12:49:53 |
        |  2 | inboundOrder.show         | InboundOrder Show         | Display the Inbound Order screen            | 2015-02-19 12:51:25 | 2015-02-19 12:51:25 |
        |  3 | inboundOrder.create       | New InboundOrder          | Create a New Inbound Order                  | 2015-02-19 12:52:09 | 2015-02-19 12:52:09 |
        |  4 | inboundOrder.edit         | Update InboundOrder       | Update an Inbound Order field values        | 2015-02-19 12:52:44 | 2015-02-19 12:52:44 |
        |  5 | inboundOrderDetail.index  | InboundOrderDetail List   | Filtered List of Inbound Order Details      | 2015-02-19 21:58:29 | 2015-02-19 21:58:29 |
        |  6 | inboundOrderDetail.show   | InboundOrderDetail Show   | Display the Inbound Order Detail screen     | 2015-02-19 21:59:18 | 2015-02-19 21:59:18 |
        |  7 | inboundOrderDetail.create | New InboundOrderDetail    | Create a New Inbound Order Detail           | 2015-02-19 22:00:00 | 2015-02-19 22:00:00 |
        |  8 | inboundOrderDetail.edit   | Update InboundOrderDetail | Update an Inbound Order Detail field values | 2015-02-19 22:00:49 | 2015-02-19 22:00:49 |
        |  9 | article.index             | Article List              | Filtered List of Articles                   | 2015-02-19 22:03:57 | 2015-02-19 22:03:57 |
        | 10 | article.show              | Article Show              | Display the Article screen                  | 2015-02-19 22:04:47 | 2015-02-19 22:04:47 |
        | 11 | article.create            | New Article               | Create a New Article                        | 2015-02-19 22:05:27 | 2015-02-19 22:05:27 |
        | 12 | article.edit              | Update Article            | Update an Article field values              | 2015-02-19 22:06:02 | 2015-02-19 22:06:02 |
        | 13 | upc.index                 | UPC List                  | Filtered List of UPCs                       | 2015-02-19 22:09:54 | 2015-02-19 22:09:54 |
        | 14 | upc.show                  | UPC Show                  | Display the UPC screen                      | 2015-02-19 22:10:51 | 2015-02-19 22:10:51 |
        | 15 | upc.create                | New UPC                   | Create a New UPC                            | 2015-02-19 22:11:34 | 2015-02-19 22:11:34 |
        | 16 | upc.edit                  | Update UPC                | Update a UPC field values                   | 2015-02-19 22:12:13 | 2015-02-19 22:12:13 |
        | 17 | userActivity.create       | New UserActivity          | Create a new User Activity                  | 2015-03-05 11:08:45 | 2015-03-05 11:08:45 |
        | 18 | userActivity.edit         | Update UserActivity       | Update a User Activity field values         | 2015-03-05 11:09:23 | 2015-03-05 11:09:23 |
        | 19 | inventory.create          | New Inventory             | Create a new Inventory                      | 2015-03-13 22:50:49 | 2015-03-13 22:50:49 |
        | 20 | inventory.edit            | Update Inventory          | Update a Inventory field values             | 2015-03-13 22:50:49 | 2015-03-13 22:50:49 |
        | 21 | tote.create               | New Tote                  | Create a new Tote                           | 2015-03-15 21:18:07 | 2015-03-15 21:18:07 |
        | 22 | tote.edit                 | Update Tote               | Update a Tote field values                  | 2015-03-15 21:18:15 | 2015-03-15 21:18:15 |
        | 23 | pallet.create             | New Pallet                | Create a new Pallet                         | 2015-03-15 23:34:51 | 2015-03-15 23:34:51 |
        | 24 | pallet.edit               | Update Pallet             | Update a Pallet field values                | 2015-03-15 23:34:53 | 2015-03-15 23:34:53 |
        | 25 | location.create           | New Location              | Create a new Location                       | 2015-03-16 14:24:36 | 2015-03-16 14:24:36 |
        | 26 | location.edit             | Update Location           | Update a Location field values              | 2015-03-16 14:24:38 | 2015-03-16 14:24:38 |
        +----+---------------------------+---------------------------+---------------------------------------------+---------------------+---------------------+
         */

        Permissions::create(['name' => 'article.create'           , 'display_name' => 'New Article'              , 'description' => 'Create a New Article'             ]);
        Permissions::create(['name' => 'article.edit'             , 'display_name' => 'Update Article'           , 'description' => 'Update an Article'                ]);
        Permissions::create(['name' => 'inboundOrder.create'      , 'display_name' => 'New InboundOrder'         , 'description' => 'Create a New Inbound Order'       ]);
        Permissions::create(['name' => 'inboundOrder.edit'        , 'display_name' => 'Update InboundOrder'      , 'description' => 'Update an Inbound Order'          ]);
        Permissions::create(['name' => 'inboundOrderDetail.create', 'display_name' => 'New InboundOrderDetail'   , 'description' => 'Create a New Inbound Order Detail']);
        Permissions::create(['name' => 'inboundOrderDetail.edit'  , 'display_name' => 'Update InboundOrderDetail', 'description' => 'Update an Inbound Order Detail'   ]);
        Permissions::create(['name' => 'inventory.create'         , 'display_name' => 'New Inventory'            , 'description' => 'Create a New Inventory'           ]);
        Permissions::create(['name' => 'inventory.edit'           , 'display_name' => 'Update Inventory'         , 'description' => 'Update Inventory'                 ]);
        Permissions::create(['name' => 'location.create'          , 'display_name' => 'New Location'             , 'description' => 'Create a New Location'            ]);
        Permissions::create(['name' => 'location.edit'            , 'display_name' => 'Update Location'          , 'description' => 'Update a Location'                ]);
        Permissions::create(['name' => 'receive'                  , 'display_name' => 'Receive'                  , 'description' => 'Receive UPCs and Articles'        ]);
        Permissions::create(['name' => 'receiptHistory.create'    , 'display_name' => 'New Receipt History'      , 'description' => 'Create a New Receipt History'     ]);
        Permissions::create(['name' => 'receiptHistory.edit'      , 'display_name' => 'Update Receipt History'   , 'description' => 'Update a Receipt History'         ]);
        Permissions::create(['name' => 'pallet.create'            , 'display_name' => 'New Pallet'               , 'description' => 'Create a New Pallet'              ]);
        Permissions::create(['name' => 'pallet.edit'              , 'display_name' => 'Update Pallet'            , 'description' => 'Update a Pallet'                  ]);
        Permissions::create(['name' => 'tote.create'              , 'display_name' => 'New Tote'                 , 'description' => 'Create a New Tote'                ]);
        Permissions::create(['name' => 'tote.edit'                , 'display_name' => 'Update Tote'              , 'description' => 'Update a Tote'                    ]);
        Permissions::create(['name' => 'upc.create'               , 'display_name' => 'New UPC'                  , 'description' => 'Create a New UPC'                 ]);
        Permissions::create(['name' => 'upc.edit'                 , 'display_name' => 'Update UPC'               , 'description' => 'Update a UPC'                     ]);
        Permissions::create(['name' => 'userActivity.create'      , 'display_name' => 'New UserActivity'         , 'description' => 'Create a New User Activity'       ]);
        Permissions::create(['name' => 'userActivity.edit'        , 'display_name' => 'Update UserActivity'      , 'description' => 'Update an User Activity'          ]);
        Permissions::create(['name' => 'userConversation.create'  , 'display_name' => 'New UserConversation'     , 'description' => 'Create a New User Conversation'   ]);
        Permissions::create(['name' => 'userConversation.edit'    , 'display_name' => 'Update UserConversation'  , 'description' => 'Update an User Conversation'      ]);
    }

}
