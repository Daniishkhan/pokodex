<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Log;

class PokemonController extends Controller
{
    public function index()
    {
        try {
            $pokemon = Pokemon::all();
            Log::info('Fetched Pokemon count: ' . $pokemon->count());
            return response()->json([
                'status' => 'success',
                'count' => $pokemon->count(),
                'data' => $pokemon,
                'message' => $pokemon->isEmpty() ? 'No Pokémon found' : 'Pokémon retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Pokémon: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching Pokémon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Pokemon $pokemon)
    {
        try {
            Log::info('Fetched Pokemon: ' . $pokemon->id);
            return response()->json([
                'status' => 'success',
                'data' => $pokemon,
                'message' => 'Pokémon retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Pokémon ' . $pokemon->id . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching Pokémon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'evolves_from' => 'required|string',
                'evolves_to' => 'required|string',
                'type' => 'required|string',
                'notes' => 'required|string',
            ]);

            $pokemon = \DB::transaction(function () use ($validated) {
                return Pokemon::create($validated);
            });

            Log::info('Pokemon created: ' . $pokemon->id);
            return response()->json([
                'status' => 'success',
                'data' => $pokemon,
                'message' => 'Pokémon created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for Pokemon creation: ' . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to create Pokemon: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Pokémon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Pokemon $pokemon)
    {
        try {
            $validated = $request->validate([
                'name' => 'string',
                'evolves_from' => 'string',
                'evolves_to' => 'string',
                'type' => 'string',
                'notes' => 'string',
            ]);

            $pokemon->update($validated);
            Log::info('Pokemon updated: ' . $pokemon->id);
            return response()->json([
                'status' => 'success',
                'data' => $pokemon,
                'message' => 'Pokémon updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for Pokemon update: ' . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update Pokemon ' . $pokemon->id . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Pokémon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Pokemon $pokemon)
    {
        try {
            $id = $pokemon->id;
            $pokemon->delete();
            Log::info('Pokemon deleted: ' . $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Pokémon deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete Pokemon ' . $pokemon->id . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Pokémon',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}