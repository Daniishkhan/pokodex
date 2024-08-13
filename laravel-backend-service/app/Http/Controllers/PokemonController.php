<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        return Pokemon::all();
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

    // Add update and destroy methods as needed
}
