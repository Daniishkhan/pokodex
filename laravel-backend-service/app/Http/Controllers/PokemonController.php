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
            'pokedex_number' => 'required|integer|unique:pokemon',
            'types' => 'required|array',
            'abilities' => 'required|array',
            'stats' => 'required|array',
            'description' => 'required|string',
            'image_url' => 'required|url',
        ]);

        return Pokemon::create($validated);
    }

    // Add update and destroy methods as needed
}
