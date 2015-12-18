<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        /*
         * select * from users;
        +----+---------+---------------------------+--------------------------------------------------------------+--------------------------------------------------------------+---------------------+---------------------+
        | id | name    | email                     | password                                                     | remember_token                                               | created_at          | updated_at          |
        +----+---------+---------------------------+--------------------------------------------------------------+--------------------------------------------------------------+---------------------+---------------------+
        |  1 | pneal   | pneal@bisconsulting.net   | $2y$10$Y8EfKvpQCGhwoE6Bajcb9ukIQwAzo3Lp7hFs6fpviIIKw7rAJu6T. | FR5ZrVgdiZ7CrNGsCcqTftvpzognBGP4TvQ4fJvF0zAquhZPfn6N1J7xhq0q | 2015-02-18 09:44:02 | 2015-03-14 00:18:21 |
        |  2 | worker  | worker@bisconsulting.net  | $2y$10$KqqbgHyOKmGSHU0rro/IaujBCv7La5yJhDGqnSD.4D8UAEbRKT6Si | tr0Aqa0gEQJb9HUJ0JZPQUZzgaU1tTsC949broRh2hgzwfdL59QP3d2y46On | 2015-02-19 14:56:25 | 2015-03-11 17:10:01 |
        |  3 | super   | super@bisconsulting.net   | $2y$10$rJ3kIu9YA5ccZWEt/Wkwi.NWneZtPQk.422TFonztuodHR89iSRNG | goHnOMFAyW9OVn4hrQKwpw5TyJXlcHlEtQihTH86y1rTxI1FsLjxwo57DAqe | 2015-02-19 16:07:13 | 2015-03-11 17:10:35 |
        |  4 | manager | manager@bisconsulting.net | $2y$10$yOc6/HHIsCl8NJCCt8OQNece4OZ6Ah0slALZk8qAiozog2Oug6mci | 5i6eqhhqVfZCbHWyZMjxO2ESG6nH9eGKJWwn1rpqqe3aXN9lYmMRef3MGcP3 | 2015-02-19 16:34:38 | 2015-03-11 17:11:07 |
        |  5 | admin   | admin@bisconsulting.net   | $2y$10$.WIRUlkKJawjURvag8EB5.TmksooxPhD1VOshPjEzwzr15Lty8One | AZG8nvonE54ITkE0CNJusHNBzZbHVdufCShbzKGzbBgyCS55GdZ0FYXr8LV6 | 2015-02-19 16:44:37 | 2015-02-19 17:03:02 |
        |  7 | rbowers | rbowers@legacyscs.com     | $2y$10$5IcArQx2lQwR9.4VlAXX1evbOaUektNAnIoC3fwWj9H8xcqHNCJqS | mgSmHWXKlVNzkMEI7xg1QyinbMA9INjflUVf1UTZno8ZnCc2G7MpJ7nuBgjf | 2015-03-17 02:51:08 | 2015-03-17 02:52:46 |
        +----+---------+---------------------------+--------------------------------------------------------------+--------------------------------------------------------------+---------------------+---------------------+
         */
        User::create(['name' => 'worker'  , 'email' => 'worker@bisconsulting.net'  , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'teamlead', 'email' => 'teamlead@bisconsulting.net', 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'super'   , 'email' => 'super@bisconsulting.net'   , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'manager' , 'email' => 'manager@bisconsulting.net' , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'admin'   , 'email' => 'admin@bisconsulting.net'   , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'rbowers' , 'email' => 'rbowers@legacyscs.com'     , 'password' => Hash::make( 'vital123' )]);
        User::create(['name' => 'pneal'   , 'email' => 'pneal@bisconsulting.net'   , 'password' => Hash::make( 'vital123' )]);
    }

}
