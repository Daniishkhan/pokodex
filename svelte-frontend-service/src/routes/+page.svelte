<script lang="ts">
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';
  import type { Pokemon } from '$lib/types';
  
  let pokemonList: Pokemon[] = [];
  let searchQuery = '';
  let currentPage = 1;
  const itemsPerPage = 20;

  onMount(async () => {
    try {
      const response = await fetch('http://localhost:8000/api/pokemon/');
      const result = await response.json();
      if (result.status === 'success') {
        pokemonList = result.data.map((pokemon: any) => ({
          id: pokemon.id,
          name: pokemon.name,
          type: pokemon.type,
          evolves_from: pokemon.evolves_from,
          evolves_to: pokemon.evolves_to,
          notes: pokemon.notes
        }));
      } else {
        console.error('Failed to fetch Pokemon data');
      }
    } catch (error) {
      console.error('Error fetching Pokemon data:', error);
    }
  });

  $: filteredPokemon = pokemonList.filter(pokemon => 
    pokemon.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  $: paginatedPokemon = filteredPokemon.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  function handleSearch() {
    currentPage = 1;
  }

  function goToPage(page: number) {
    currentPage = page;
  }

  function goToPokemonDetails(id: number) {
    goto(`/pokemon/${id}`);
  }
</script>

<main>
  <h1>Welcome to Pokedex</h1>
  <p class="description">This is a Pokedex application built with Svelte, Laravel and Python AI</p>
  
  <img src="/images/pokemon-logo.png" alt="Pokemon Logo" class="pokemon-logo">
  
  <div class="search-container">
    <input 
      type="text" 
      bind:value={searchQuery} 
      placeholder="Search Pokemon" 
      on:input={handleSearch}
    />
  </div>

  <div class="pokemon-grid">
    {#each paginatedPokemon as pokemon (pokemon.id)}
      <button class="pokemon-card" on:click={() => goToPokemonDetails(pokemon.id)}>
        <div class="pokemon-image">
          <div class="image-placeholder">{pokemon.name[0]}</div>
        </div>
        <p>{pokemon.name}</p>
      </button>
    {/each}
  </div>

  <div class="pagination">
    {#each Array(Math.ceil(filteredPokemon.length / itemsPerPage)) as _, i}
      <button on:click={() => goToPage(i + 1)} class:active={currentPage === i + 1}>
        {i + 1}
      </button>
    {/each}
  </div>
</main>

<style>
  .pokemon-card {
    cursor: pointer;
  }
</style>