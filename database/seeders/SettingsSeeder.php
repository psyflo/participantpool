<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsSeeder extends Seeder
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
         * Insert default settings
         */
        DB::table('settings')->insert(['key' => 'participantpool.contact_email', 'value' => null, 'description' => 'Will be displayed at startpage and after participant has registered']);
        DB::table('settings')->insert(['key' => 'participantpool.updatelink_days', 'value' => null, 'description' => 'Number of days the update link can be used by participant']);
        DB::table('settings')->insert(['key' => 'participantpool.log_entries_limit', 'value' => null, 'description' => 'Maximal number of entries which have to read from log files']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
