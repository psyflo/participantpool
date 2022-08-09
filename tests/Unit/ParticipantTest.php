<?php

namespace Tests\Unit;

use App\Models\Participant;
use App\Models\Study;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test participants
     *
     * @return void
     */
    public function test_participants()
    {
        Participant::factory()->count(20)->create();

        $this->assertEquals(20, Participant::all()->count());
    }

    /**
     * Test participant to studies relation
     *
     * @return void
     */
    public function test_participant_to_studies_relation()
    {
        Participant::factory()->count(1)->has(Study::factory()->count(2), 'studies')->create();

        $participant = Participant::all()->first();

        $this->assertEquals(2, $participant->studies->count());
    }
}
