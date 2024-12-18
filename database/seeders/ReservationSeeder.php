<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        Reservation::insert([
            [
                'table_id' => 1,
                'user_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'start_hour' => '12:00:00',
                'end_hour' => '14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_id' => 2,
                'user_id' => 1,
                'date' => Carbon::today()->addDay()->toDateString(),
                'start_hour' => '18:00:00',
                'end_hour' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_id' => 3,
                'user_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'start_hour' => '16:00:00',
                'end_hour' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_id' => 4,
                'user_id' => 1,
                'date' => Carbon::today()->addDays(2)->toDateString(),
                'start_hour' => '10:00:00',
                'end_hour' => '12:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
