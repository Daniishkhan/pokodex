<script lang="ts">
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';
  import type { Pokemon } from '$lib/types';
  
  let pokemonList: Pokemon[] = [];
  let searchQuery = '';
  let currentPage = 1;
  const itemsPerPage = 20; // 5 items per row * 4 rows
  const itemsPerRow = 5;

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

  <div class="pokemon-grid" style="--items-per-row: {itemsPerRow};">
    {#each paginatedPokemon as pokemon (pokemon.id)}
      <button class="pokemon-card" on:click={() => goToPokemonDetails(pokemon.id)}>
        <div class="pokemon-image">
          <div class="image-placeholder">{pokemon.name[0].toUpperCase()}</div>
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
  $primary-color: #3d7dca;
  $secondary-color: #ffcb05;
  $background-color: #f0f0f0;
  $card-background: #ffffff;
  $text-color: #333333;
  $accent-color: #ff5722;

  $font-family: 'Arial', sans-serif;
  $font-size-base: 16px;
  $font-size-large: 1.2em;
  $font-size-xlarge: 2em;

  $spacing-small: 10px;
  $spacing-medium: 1rem;
  $spacing-large: 2rem;

  $border-radius: 10px;
  $box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

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
    text-align: center;
    font-size: 1.1em;
    margin-bottom: $spacing-large;
    color: darken($text-color, 10%);
  }

  .pokemon-logo {
    display: block;
    width: 200px;
    margin: 0 auto $spacing-large;
  }

  .search-container {
    margin-bottom: $spacing-large;
    
    input {
      width: 100%;
      padding: $spacing-medium;
      font-size: $font-size-base;
      border: 2px solid $primary-color;
      border-radius: $border-radius;
      background-color: $card-background;
      color: $text-color;
      box-shadow: $box-shadow;
      transition: box-shadow $transition-speed ease;

      &:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba($primary-color, 0.3);
      }
    }
  }

  .pokemon-grid {
    display: grid;
    grid-template-columns: repeat(var(--items-per-row), 1fr);
    gap: $spacing-medium;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
  }

  .pokemon-card {
    background-color: $card-background;
    border-radius: $border-radius;
    padding: $spacing-medium;
    text-align: center;
    transition: transform $transition-speed ease, box-shadow $transition-speed ease;
    cursor: pointer;
    box-shadow: $box-shadow;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    height: 180px; 

    &:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .pokemon-image {
      width: 80px;
      height: 80px;
      margin-bottom: $spacing-small;
      background-color: lighten($primary-color, 30%);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;

      .image-placeholder {
        font-size: $font-size-large;
        color: $primary-color;
        font-weight: bold;
      }
    }

    p {
      margin: 0;
      font-weight: bold;
      color: $text-color;
      font-size: 0.9em;
      word-break: break-word;
      max-width: 100%;
    }
  }

  .pagination {
    margin-top: $spacing-large;
    text-align: center;

    button {
      margin: 0 5px;
      padding: $spacing-small $spacing-medium;
      background-color: $card-background;
      border: 1px solid $primary-color;
      border-radius: $border-radius;
      cursor: pointer;
      color: $primary-color;
      transition: background-color $transition-speed ease, color $transition-speed ease;

      &:hover {
        background-color: $primary-color;
        color: $card-background;
      }

      &.active {
        background-color: $primary-color;
        color: $card-background;
      }

      &:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
    }
  }
</style>