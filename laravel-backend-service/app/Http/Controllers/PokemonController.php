<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        try {
            $pokemon = Pokemon::all();
            return response()->json([
                'count' => $pokemon->count(),
                'data' => $pokemon,
                'message' => $pokemon->isEmpty() ? 'No Pokémon found' : 'Pokémon retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching Pokémon: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function show(Pokemon $pokemon)
    {
        return $pokemon;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'evolves_from' => 'required|string',
            'evolves_to' => 'required|string',
            'type' => 'required|string',
            'notes' => 'required|string',
        ]);

        return Pokemon::create($validated);
    }

    public function update(Request $request, Pokemon $pokemon)
    {
        $pokemon->update($request->all());
        return $pokemon;
    }

    public function destroy(Pokemon $pokemon)
    {
        $pokemon->delete();
        return response()->noContent();
    }
}