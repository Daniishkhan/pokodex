<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Log;

class PokemonSeeder extends Seeder
{
    public function run()
    {
        try {
            $pythonServiceUrl = env('PYTHON_SERVICE_URL', 'http://python-ai-service:8001');
            $response = Http::get("{$pythonServiceUrl}/pokemon-data");
            $response->throw();  
            $pokemonData = $response->json()['data'];

            $this->command->info("Fetched " . count($pokemonData) . " Pokémon from Python service. Starting to seed...");

            foreach ($pokemonData as $pokemon) {
                try {
                    $name = preg_replace('/\s*\(.*/', '', $pokemon['name']);

                    Pokemon::create([
                        'name' => $name,
                        'type' => $pokemon['type'],
                        'evolves_from' => $pokemon['evolves_from'] !== '—' ? $pokemon['evolves_from'] : null,
                        'evolves_to' => $pokemon['evolves_into'] !== '—' ? $pokemon['evolves_into'] : null,
                        'notes' => $pokemon['notes'],
                    ]);

                    $this->command->info("Seeded Pokémon: {$name}");
                } catch (\Exception $e) {
                    Log::error("Error seeding Pokémon {$pokemon['name']}: " . $e->getMessage());
                    $this->command->error("Failed to seed Pokémon: {$pokemon['name']}. Error: " . $e->getMessage());
                }
            }

            $this->command->info("Pokémon seeding completed.");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("HTTP Error fetching Pokémon data: " . $e->getMessage());
            $this->command->error("Failed to fetch Pokémon data from Python service. HTTP Error: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Unexpected error during Pokémon seeding: " . $e->getMessage());
            $this->command->error("An unexpected error occurred during Pokémon seeding. Check the logs for more details.");
        }
    }
}