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
        $limit = 151; // Fetch first 151 Pokémon (1st generation)
        
        try {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon?limit={$limit}");
            $response->throw();  // This will throw an exception for non-2xx responses
            $pokemons = $response->json()['results'];

            $this->command->info("Fetched {$limit} Pokémon from PokeAPI. Starting to seed...");

            foreach ($pokemons as $index => $pokemon) {
                try {
                    $pokemonData = Http::get($pokemon['url'])->throw()->json();
                    $speciesData = Http::get($pokemonData['species']['url'])->throw()->json();

                    Pokemon::create([
                        'name' => $pokemonData['name'],
                        'pokedex_number' => $pokemonData['id'],
                        'types' => json_encode(array_map(fn($type) => $type['type']['name'], $pokemonData['types'])),
                        'abilities' => json_encode(array_map(fn($ability) => $ability['ability']['name'], $pokemonData['abilities'])),
                        'stats' => json_encode(array_map(fn($stat) => [
                            'name' => $stat['stat']['name'],
                            'base_stat' => $stat['base_stat']
                        ], $pokemonData['stats'])),
                        'description' => $this->getEnglishDescription($speciesData['flavor_text_entries']),
                        'image_url' => $pokemonData['sprites']['other']['official-artwork']['front_default'],
                    ]);

                    $this->command->info("Seeded Pokémon");
                } catch (\Exception $e) {
                    Log::error("Error seeding Pokémon {$pokemon['name']}: " . $e->getMessage());
                    $this->command->error("Failed to seed Pokémon: {$pokemon['name']}. Error: " . $e->getMessage());
                }
            }

            $this->command->info("Pokémon seeding completed.");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("HTTP Error fetching Pokémon list: " . $e->getMessage());
            $this->command->error("Failed to fetch Pokémon list from PokeAPI. HTTP Error: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Unexpected error during Pokémon seeding: " . $e->getMessage());
            $this->command->error("An unexpected error occurred during Pokémon seeding. Check the logs for more details.");
        }
    }

    private function getEnglishDescription($flavorTextEntries)
    {
        foreach ($flavorTextEntries as $entry) {
            if ($entry['language']['name'] === 'en') {
                return str_replace("\f", " ", $entry['flavor_text']);
            }
        }
        return '';
    }
}