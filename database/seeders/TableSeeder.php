<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run()
    {
        // Loop to create 15 tables
        for ($i = 1; $i <= 15; $i++) {
            Table::create([
                'description' => "Table $i",
                'number_chairs' => 6,
            ]);
        }
    }
}
