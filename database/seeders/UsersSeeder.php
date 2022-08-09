<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Create superadmin
         */
        DB::table('users')->insert(['id' => 1, 'name' => 'Webadmin', 'email' => 'webadmin-psychologie@unibas.ch', 'password' => Hash::make('web-admin99')]);

        /*
         * Assign superadmin to admin role
         */
        BouncerFacade::assign('admin')->to(User::find(1));
    }
}
