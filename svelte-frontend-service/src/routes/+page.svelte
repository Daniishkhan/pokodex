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
        pokemonList = result.data;
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

<svelte:head>
  <title>Pokodex</title>
</svelte:head>

<main>
  <h1>Welcome to Pokodex</h1>
  <p class="app-description">Explore the world of Pokemon with our Pokodex app!</p>
  
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

<style lang="scss">
  $primary-color: #e3350d;
  $secondary-color: #3d7dca;
  $background-color: #000000;
  $card-background: #ffffff;
  $text-color: #ffffff;

  $font-family: 'Arial', sans-serif;
  $font-size-base: 16px;
  $font-size-large: 1.5em;
  $font-size-xlarge: 2em;

  $spacing-small: 10px;
  $spacing-medium: 1rem;
  $spacing-large: 2rem;

  $border-radius: 10px;

  $transition-speed: 0.3s;

  :global(body) {
    font-family: $font-family;
    font-size: $font-size-base;
    background-color: $background-color;
    color: $text-color;
    margin: 0;
    padding: 0;
  }

  main {
    max-width: 1200px;
    margin: 0 auto;
    padding: $spacing-large;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h1 {
    text-align: center;
    color: $primary-color;
    font-size: $font-size-xlarge;
    margin-bottom: $spacing-medium;
  }

  .app-description {
    color: $text-color;
    text-align: center;
    font-size: 1.2rem;
    margin-bottom: $spacing-large;
  }

  .pokemon-logo {
    width: 300px;
    margin-bottom: $spacing-large;
  }

  .search-container {
    margin-bottom: $spacing-large;
    width: 100%;
    max-width: 500px;
    
    input {
      width: 100%;
      padding: $spacing-small;
      font-size: $font-size-base;
      border: 2px solid $primary-color;
      border-radius: $border-radius;
      background-color: $background-color;
      color: $text-color;
    }
  }

  .pokemon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: $spacing-large;
    width: 100%;
  }

  .pokemon-card {
    background-color: $card-background;
    border-radius: $border-radius;
    padding: $spacing-medium;
    text-align: center;
    transition: transform $transition-speed ease;
    cursor: pointer;
    border: none;

    &:hover {
      transform: scale(1.05);
    }

    .pokemon-image {
      width: 100px;
      height: 100px;
      margin: 0 auto $spacing-small;
      background-color: $background-color;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;

      .image-placeholder {
        font-size: $font-size-large;
        color: $primary-color;
      }
    }

    p {
      margin: 0;
      font-weight: bold;
      color: $background-color;
    }
  }

  .pagination {
    margin-top: $spacing-large;
    text-align: center;

    button {
      margin: 0 5px;
      padding: 5px 10px;
      background-color: $background-color;
      border: 1px solid $primary-color;
      border-radius: $border-radius;
      cursor: pointer;
      color: $text-color;

      &.active {
        background-color: $primary-color;
        color: $background-color;
      }
    }
  }
</style>