<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to create a reservation.
     *
     * @return void
     */
    public function test_create_reservation()
    {
        $user = User::factory()->create();
        $table = Table::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/reservations', [
                'user_id' => $user->id,
                'table_id' => $table->id,
                'date' => '2024-12-20',
                'start_hour' => '18:00',
                'end_hour' => '20:00',
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => $user->id,
            'table_id' => $table->id,
            'date' => '2024-12-20',
            'start_hour' => '18:00',
            'end_hour' => '20:00',
        ]);

        $this->assertDatabaseHas('reservations', [
            'table_id' => $table->id,
            'date' => '2024-12-20',
            'start_hour' => '18:00',
            'end_hour' => '20:00',
        ]);
    }

    /**
     * Test reservation conflict.
     *
     * @return void
     */
    public function test_reservation_conflict()
    {
        $user = User::factory()->create();
        $table = Table::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/reservations', [
                'user_id' => $user->id,
                'table_id' => $table->id,
                'date' => '2024-12-20',
                'start_hour' => '18:00',
                'end_hour' => '20:00',
            ]);

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/reservations', [
                'user_id' => $user->id,
                'table_id' => $table->id,
                'date' => '2024-12-20',
                'start_hour' => '19:00',
                'end_hour' => '21:00',   
            ]);

        $response->assertStatus(409);
        $response->assertJson([
            'error' => 'A mesa não tem disponibilidade na data e horário informado',
        ]);
    }
}


