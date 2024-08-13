<script lang="ts">
  import { onMount } from 'svelte';
  import type { Pokemon } from '$lib/types';
  
  let pokemonList: Pokemon[] = [];
  let searchQuery = '';
  let currentPage = 1;
  const itemsPerPage = 10;

  onMount(async () => {
    //TODO: Fetch data from API
    pokemonList = [
      { id: 1, name: 'Bulbasaur', type: 'Grass', evolves_from: null, evolves_to: 'Ivysaur', notes: '' },
      { id: 2, name: 'Ivysaur', type: 'Grass', evolves_from: 'Bulbasaur', evolves_to: 'Venusaur', notes: ''  },
      { id: 3, name: 'Venusaur', type: 'Grass', evolves_from: 'Ivysaur', evolves_to: null, notes: ''  },
      { id: 4, name: 'Charmander', type: 'Fire', evolves_from: null, evolves_to: 'Charmeleon', notes: ''  },
      { id: 5, name: 'Charmeleon', type: 'Fire', evolves_from: 'Charmander', evolves_to: 'Charizard', notes: ''  },
      { id: 6, name: 'Charizard', type: 'Fire', evolves_from: 'Charmeleon', evolves_to: null, notes: ''  },
    ];
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
</script>

<main>
  <h1>Welcome to Pokedex</h1>
  
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
      <div class="pokemon-card">
        <div class="pokemon-image">
          <div class="image-placeholder">{pokemon.name[0]}</div>
        </div>
        <p>{pokemon.name}</p>
      </div>
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