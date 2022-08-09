<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\Study;
use Illuminate\Database\Seeder;

class ParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Create some participants
         */
        Participant::factory()->count(20)->create();

        /*
         * Create some other studies
         */
        Study::factory()->count(10)->create();
    }
}
