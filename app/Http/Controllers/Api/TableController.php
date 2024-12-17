<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        return Table::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'number_chairs' => 'required|integer|min:1',
        ]);

        return Table::create($request->all());
    }

    public function show(Table $table)
    {
        return $table;
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'number_chairs' => 'required|integer|min:1',
        ]);

        $table->update($request->all());

        return $table;
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully.']);
    }
}
