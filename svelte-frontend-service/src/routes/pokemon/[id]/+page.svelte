<script lang="ts">
    import { onMount } from 'svelte';
    import { page } from '$app/stores';
    import type { Pokemon } from '$lib/types';

    type Error = string | null;
  
    let pokemon: Pokemon | null = null;
    let loading = true;
    let error: Error = null;
  
    onMount(async () => {
      const id = $page.params.id;
      try {
        const response = await fetch(`http://localhost:8000/api/pokemon/${id}`);
        const result = await response.json();
        if (result.status === 'success') {
          pokemon = result.data;
        } else {
          error = 'Failed to fetch Pokemon data';
        }
      } catch (err) {
        error = 'Error fetching Pokemon data';
      } finally {
        loading = false;
      }
    });
  </script>
  
  <svelte:head>
    <title>{pokemon ? pokemon.name : 'Pokemon Details'} | Pokodex</title>
  </svelte:head>

  <div class="container">
    <main>
      {#if loading}
        <p class="loading">Loading...</p>
      {:else if error}
        <p class="error">Error: {error}</p>
      {:else if pokemon}
        <div class="pokemon-details">
          <h1>{pokemon.name}</h1>
          <div class="info-grid">
            <div class="info-item">
              <span class="label">Type:</span>
              <span class="value">{pokemon.type}</span>
            </div>
            <div class="info-item">
              <span class="label">Evolves from:</span>
              <span class="value">{pokemon.evolves_from || 'None'}</span>
            </div>
            <div class="info-item">
              <span class="label">Evolves to:</span>
              <span class="value">{pokemon.evolves_to || 'None'}</span>
            </div>
          </div>
          <div class="notes">
            <h2>Notes</h2>
            <p>{pokemon.notes}</p>
          </div>
        </div>
      {:else}
        <p class="no-data">No Pokemon data available</p>
      {/if}
      
      <a href="/" class="back-link">Back to list</a>
    </main>
  </div>

  <style lang="scss">
    @import '../../../styles/pokemon-details.scss';
  </style>