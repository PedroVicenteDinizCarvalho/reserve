<?php

namespace Tests\Feature;

use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to fetch all tables.
     *
     * @return void
     */
    public function test_fetch_all_tables()
    {
        // Create 10 tables using the factory
        Table::factory()->count(10)->create();

        // Make a GET request to fetch all tables
        $response = $this->getJson('/api/tables');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that 10 tables are returned
        $response->assertJsonCount(10, 'data');
    }


    /**
     * Test to check if a table exists.
     *
     * @return void
     */
    public function test_table_exists()
    {
        // Create a table using the factory
        $table = Table::factory()->create();

        // Make a GET request to fetch the table by ID
        $response = $this->getJson("/api/tables/{$table->id}");

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the correct table is returned
        $response->assertJson([
            'id' => $table->id,
            'description' => $table->description,
            'number_chairs' => $table->number_chairs,  // Ensure all relevant data is returned
        ]);
    }

    /**
     * Test creating a new table.
     *
     * @return void
     */
    public function test_create_table()
    {
        $data = [
            'description' => 'Window Seat',
            'number_chairs' => 4,
        ];

        // Make a POST request to create a new table
        $response = $this->postJson('/api/tables', $data);

        // Assert the table was created successfully
        $response->assertStatus(201);

        // Assert the table is in the database
        $this->assertDatabaseHas('tables', $data);
    }

    /**
     * Test updating an existing table.
     *
     * @return void
     */
    public function test_update_table()
    {
        $table = Table::factory()->create();
        $newData = [
            'description' => 'Updated Table Description',
            'number_chairs' => 6,
        ];

        // Make a PUT request to update the table
        $response = $this->putJson("/api/tables/{$table->id}", $newData);

        // Assert the table was updated successfully
        $response->assertStatus(200);

        // Assert the table's new data is in the database
        $this->assertDatabaseHas('tables', $newData);
    }

    /**
     * Test deleting an existing table.
     *
     * @return void
     */
    public function test_delete_table()
    {
        $table = Table::factory()->create();

        // Make a DELETE request to remove the table
        $response = $this->deleteJson("/api/tables/{$table->id}");

        // Assert the table was deleted successfully
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Table deleted successfully.',
        ]);

        // Assert the table is no longer in the database
        $this->assertDatabaseMissing('tables', [
            'id' => $table->id,
        ]);
    }
}

