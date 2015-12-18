<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustPermission as Permission;
use Zizaco\Entrust\EntrustRole as Role;


class PermissionRoleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permission_role')->delete();

        /*
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
        Permissions::create(['name' => 'pallet.create'            , 'display_name' => 'New Pallet'               , 'description' => 'Create a New Pallet'              ]);
        Permissions::create(['name' => 'pallet.edit'              , 'display_name' => 'Update Pallet'            , 'description' => 'Update a Pallet'                  ]);
        Permissions::create(['name' => 'tote.create'              , 'display_name' => 'New Tote'                 , 'description' => 'Create a New Tote'                ]);
        Permissions::create(['name' => 'tote.edit'                , 'display_name' => 'Update Tote'              , 'description' => 'Update a Tote'                    ]);
        Permissions::create(['name' => 'upc.create'               , 'display_name' => 'New UPC'                  , 'description' => 'Create a New UPC'                 ]);
        Permissions::create(['name' => 'upc.edit'                 , 'display_name' => 'Update UPC'               , 'description' => 'Update a UPC'                     ]);
        Permissions::create(['name' => 'userActivity.create'      , 'display_name' => 'New UserActivity'         , 'description' => 'Create a New User Activity'       ]);
        Permissions::create(['name' => 'userActivity.edit'        , 'display_name' => 'Update UserActivity'      , 'description' => 'Update an User Activity'          ]);

        Role::create(['name' => 'receiver'      , 'display_name' => 'Receiver'                , 'description' => 'Receiver worker at a rework table'      ]);
        Role::create(['name' => 'putAwayReserve', 'display_name' => 'Put Away to reserve'     , 'description' => 'Put Away to reserve locations'          ]);
        Role::create(['name' => 'putAwayPick'   , 'display_name' => 'Put Away to pick face'   , 'description' => 'Put Away to pick face locations'        ]);
        Role::create(['name' => 'replen'        , 'display_name' => 'Replen'                  , 'description' => 'Move Inventory from reserve to pick face locations']);
        Role::create(['name' => 'poReconcile'   , 'display_name' => 'PO Receipt Reconciliator', 'description' => 'Reconcile Receipt POs variances'        ]);
        Role::create(['name' => 'teamLead'      , 'display_name' => 'Shift Team Leader'       , 'description' => 'Team Lead for the shift'                ]);
        Role::create(['name' => 'super'         , 'display_name' => 'Shift Supervisor'        , 'description' => 'Supervising the shift'                  ]);
        Role::create(['name' => 'manager'       , 'display_name' => 'Shift Manager'           , 'description' => 'Managing shift activities'              ]);
        Role::create(['name' => 'admin'         , 'display_name' => 'Administrator'           , 'description' => 'Administrates users of this application']);
        Role::create(['name' => 'support'       , 'display_name' => 'IT Support'              , 'description' => 'IT Support for this application'        ]);
         */

        $roles = Role::whereRaw("name in ('receiver', 'putAwayReserve', 'putAwayPick', 'replen', 'poReconcile', 'teamLead', 'super', 'manager', 'support')")-> get();
        $permissions = Permission::whereRaw("name in ('none')")->get();
        foreach($roles as $role) {
            foreach($permissions as $permission) {
                DB::table('permission_role')->insert(['role_id' => $role->id, 'permission_id' => $permission->id]);
            }
        }
        $roles = Role::whereRaw("name in ('teamLead', 'super', 'manager', 'support')")->get();
        $permissions = Permission::whereRaw("name in ('article.create', 'article.edit', 'location.create', 'location.edit', 'pallet.create', 'pallet.edit'
                                                    , 'tote.create', 'tote.edit', 'upc.create', 'upc.edit'
                                                    , 'userActivity.create', 'userActivity.edit')")->get();
        foreach($roles as $role) {
            foreach($permissions as $permission) {
                DB::table('permission_role')->insert(['role_id' => $role->id, 'permission_id' => $permission->id]);
            }
        }
        $roles = Role::whereName('support')->get();
        $permissions = Permission::whereRaw("name in ('inboundOrder.create', 'inboundOrder.edit', 'inboundOrderDetail.create', 'inboundOrderDetail.edit'
                                                    , 'inventory.create', 'inventory.edit'
                                                    , 'receiptHistory.create', 'receiptHistory.edit', 'userConversation.create', 'userConversation.edit')")->get();
        foreach($roles as $role) {
            foreach($permissions as $permission) {
                DB::table('permission_role')->insert(['role_id' => $role->id, 'permission_id' => $permission->id]);
            }
        }

    }

}
