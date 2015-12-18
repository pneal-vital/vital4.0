<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustRole as Role;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        /*
         * select * from roles;
        +----+-----------------------+-------------------------------------+--------------------------------------------------------------------------------------+---------------------+---------------------+
        | id | name                  | display_name                        | description                                                                          | created_at          | updated_at          |
        +----+-----------------------+-------------------------------------+--------------------------------------------------------------------------------------+---------------------+---------------------+
        |  1 | receive               | Receiver                            | Receiver worker at a rework table                                                    | 2015-02-19 01:51:05 | 2015-02-19 01:51:05 |
        |  2 | receiveSuper          | Receiver Supervisor                 | Receiver supervising the rework tables                                               | 2015-02-19 01:52:05 | 2015-02-19 01:52:05 |
        |  3 | receiveManager        | Receiver Manager                    | Receiver managing the receiving operations                                           | 2015-02-19 01:53:12 | 2015-02-19 01:53:12 |
        |  4 | putawayReserve        | Put Away to reserve                 | Put Away to reserve locations                                                        | 2015-02-19 11:23:03 | 2015-02-19 11:23:03 |
        |  5 | putawayReserveSuper   | Put Away to reserve Supervisor      | Supervising Put Away to reserve locations                                            | 2015-02-19 11:23:47 | 2015-02-19 11:23:47 |
        |  6 | putawayReserveManager | Put Away to reserve Manager         | Managing Put Away to reserve locations                                               | 2015-02-19 11:24:20 | 2015-02-19 11:24:20 |
        |  7 | putawayPick           | Put Away to pick face               | Put Away to forward pick face locations                                              | 2015-02-19 11:24:53 | 2015-02-19 11:24:53 |
        |  8 | putawayPickSuper      | Put Away to pick face Supervisor    | Supervising Put Away to forward pick face locations                                  | 2015-02-19 11:25:22 | 2015-02-19 11:25:22 |
        |  9 | putawayPickManager    | Put Away to pick face Manager       | Managing Put Away to forward pick face locations                                     | 2015-02-19 11:25:48 | 2015-02-19 11:25:48 |
        | 10 | replen                | Replen                              | Remove Inventory from reserve locations to place into transfer locations             | 2015-02-19 11:30:12 | 2015-02-19 11:30:12 |
        | 11 | replenSuper           | Replen Supervisor                   | Supervising Remove Inventory from reserve locations to place into transfer locations | 2015-02-19 11:30:51 | 2015-02-19 11:30:51 |
        | 12 | replenManager         | Replen Manager                      | Managing Remove Inventory from reserve locations to place into transfer locations    | 2015-02-19 11:32:07 | 2015-02-19 11:32:07 |
        | 13 | poReconcile           | PO Receipt Reconciliator            | Reconcile Receipt POs variances                                                      | 2015-02-19 11:43:41 | 2015-02-19 11:43:41 |
        | 14 | poReconcileSuper      | PO Receipt Reconciliator Supervisor | Supervising Reconcile Receipt POs variances                                          | 2015-02-19 11:44:10 | 2015-02-19 11:44:10 |
        | 15 | poReconcileManager    | PO Receipt Reconciliator Manager    | Managing Reconcile Receipt POs variances                                             | 2015-02-19 11:44:35 | 2015-02-19 11:44:35 |
        | 16 | admin                 | Administrator                       | Administrates users of this application                                              | 2015-02-19 11:46:39 | 2015-02-19 11:46:39 |
        | 17 | support               | IT Support                          | IT Support for this application                                                      | 2015-02-19 12:24:18 | 2015-02-19 12:24:18 |
        +----+-----------------------+-------------------------------------+--------------------------------------------------------------------------------------+---------------------+---------------------+
         */
        
        Role::create(['name' => 'receive'       , 'display_name' => 'Receiver'                , 'description' => 'Receiver worker at a rework table'      ]);
        Role::create(['name' => 'putAwayReserve', 'display_name' => 'Put Away to reserve'     , 'description' => 'Put Away to reserve locations'          ]);
        Role::create(['name' => 'putAwayPick'   , 'display_name' => 'Put Away to pick face'   , 'description' => 'Put Away to pick face locations'        ]);
        Role::create(['name' => 'replen'        , 'display_name' => 'Replen'                  , 'description' => 'Move Inventory from reserve to pick face locations']);
        Role::create(['name' => 'poReconcile'   , 'display_name' => 'PO Receipt Reconciliator', 'description' => 'Reconcile Receipt POs variances'        ]);
        Role::create(['name' => 'teamLead'      , 'display_name' => 'Shift Team Leader'       , 'description' => 'Team Lead for the shift'                ]);
        Role::create(['name' => 'super'         , 'display_name' => 'Shift Supervisor'        , 'description' => 'Supervising the shift'                  ]);
        Role::create(['name' => 'manager'       , 'display_name' => 'Shift Manager'           , 'description' => 'Managing shift activities'              ]);
        Role::create(['name' => 'admin'         , 'display_name' => 'Administrator'           , 'description' => 'Administrates users of this application']);
        Role::create(['name' => 'support'       , 'display_name' => 'IT Support'              , 'description' => 'IT Support for this application'        ]);
    }

}
