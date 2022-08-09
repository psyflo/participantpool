<?php

namespace Database\Seeders;

use App\Models\Mailing;
use App\Models\Participant;
use App\Models\Study;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Roles and abilities
         * 
         * Models               Manager Admin
         * 
         * Participants CRUD    XXXX    XXXX
         * Studies CRUD         0X00    XXXX
         * Mailings CRUD        XX00    XXXX
         * Own Mailings CRUD    XXXX    XXXX
         * Users CRUD           0000    XXXX
         * Settings CRUD        0000    XXXX
         * 
         */

        /*
         * Truncate and create roles
         */
        DB::table('roles')->truncate();
        
        $admin = BouncerFacade::role()->firstOrCreate(['name' => 'admin', 'title' => 'Admin']);
        $manager = BouncerFacade::role()->firstOrCreate(['name' => 'manager', 'title' => 'Manager']);

        /*
         * Truncate and create permissions and abilities
         */
        DB::table('permissions')->truncate();
        DB::table('abilities')->truncate();

        BouncerFacade::allow($admin)->everything();
        BouncerFacade::allow($manager)->toManage(Participant::class);
        BouncerFacade::allow($manager)->to(['read'], Study::class);
        BouncerFacade::allow($manager)->to(['create', 'read'], Mailing::class);
        BouncerFacade::allow($manager)->toOwn(Mailing::class)->to(['update', 'delete']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
