<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustRole as Role;

class RoleUserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('role_user')->delete();

        /*
        Role::create(['name' => 'receipt'       , 'display_name' => 'Receiver'                , 'description' => 'Receiver worker at a rework table'      ]);
        Role::create(['name' => 'putAwayReserve', 'display_name' => 'Put Away to reserve'     , 'description' => 'Put Away to reserve locations'          ]);
        Role::create(['name' => 'putAwayPick'   , 'display_name' => 'Put Away to pick face'   , 'description' => 'Put Away to pick face locations'        ]);
        Role::create(['name' => 'replen'        , 'display_name' => 'Replen'                  , 'description' => 'Move Inventory from reserve to pick face locations']);
        Role::create(['name' => 'poReconcile'   , 'display_name' => 'PO Receipt Reconciliator', 'description' => 'Reconcile Receipt POs variances'        ]);
        Role::create(['name' => 'teamLead'      , 'display_name' => 'Shift Team Leader'       , 'description' => 'Team Lead for the shift'                ]);
        Role::create(['name' => 'super'         , 'display_name' => 'Shift Supervisor'        , 'description' => 'Supervising the shift'                  ]);
        Role::create(['name' => 'manager'       , 'display_name' => 'Shift Manager'           , 'description' => 'Managing shift activities'              ]);
        Role::create(['name' => 'admin'         , 'display_name' => 'Administrator'           , 'description' => 'Administrates users of this application']);
        Role::create(['name' => 'support'       , 'display_name' => 'IT Support'              , 'description' => 'IT Support for this application'        ]);

        User::create(['name' => 'worker' , 'email' => 'worker@bisconsulting.net' , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'teamLead'  , 'email' => 'super@bisconsulting.net'  , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'super'  , 'email' => 'super@bisconsulting.net'  , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'manager', 'email' => 'manager@bisconsulting.net', 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'admin'  , 'email' => 'admin@bisconsulting.net'  , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'rbowers', 'email' => 'rbowers@legacyscs.com'    , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'pneal'  , 'email' => 'pneal@bisconsulting.net'  , 'password' => Hash::make( 'vital123' )]);
         */

        $roles = Role::whereRaw("name in ('receipt', 'putAwayReserve', 'putAwayPick', 'replen')")-> get();
        $users = User::whereRaw("name in ('worker')")->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }
        $roles = Role::whereRaw("name in ('receipt', 'putAwayReserve', 'putAwayPick', 'replen', 'poReconcile', 'teamLead')")->get();
        $users = User::whereRaw("name in ('teamLead')")->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }
        $roles = Role::whereRaw("name in ('receipt', 'putAwayReserve', 'putAwayPick', 'replen', 'poReconcile', 'teamLead', 'super')")->get();
        $users = User::whereRaw("name in ('super')")->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }
        $roles = Role::whereRaw("name in ('receipt', 'putAwayReserve', 'putAwayPick', 'replen', 'poReconcile', 'teamLead', 'super', 'manager')")->get();
        $users = User::whereRaw("name in ('manager', 'rbowers')")->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }
        $roles = Role::whereName('admin')->get();
        $users = User::whereRaw("name in ('admin', 'rbowers')")->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }
        $roles = Role::get();
        $users = User::whereName('pneal')->get();
        foreach($roles as $role) {
            foreach($users as $user) {
                DB::table('role_user')->insert(['role_id' => $role->id, 'user_id' => $user->id]);
            }
        }

    }

}
