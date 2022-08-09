<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_DEBUG', false))
        {
            $this->call([BouncerSeeder::class, UsersSeeder::class, SettingsSeeder::class, ParticipantsSeeder::class]);
        }
        else
        {
            $this->call([BouncerSeeder::class, UsersSeeder::class, SettingsSeeder::class]);
        }
    }
}
