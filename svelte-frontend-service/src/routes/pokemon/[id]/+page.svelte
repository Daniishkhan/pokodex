<script lang="ts">
  import { onMount } from 'svelte';
  import { page } from '$app/stores';
  import { goto } from '$app/navigation';
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

  async function handleDelete() {
    if (!pokemon) return;
    if (confirm(`Are you sure you want to delete ${pokemon.name}?`)) {
      try {
        const response = await fetch(`http://localhost:8000/api/pokemon/${pokemon.id}`, {
          method: 'DELETE'
        });
        if (response.ok) {
          goto('/');
        } else {
          error = 'Failed to delete Pokemon';
        }
      } catch (err) {
        error = 'Error deleting Pokemon';
      }
    }
  }

  function handleUpdate() {
    if (pokemon) {
      goto(`/pokemon/${pokemon.id}/edit`);
    }
  }
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
    
    <div class="action-buttons">
      <a href="/" class="btn btn-secondary">Back to list</a>
      <div class="action-group">
        {#if pokemon}
          <button on:click={handleUpdate} class="btn btn-primary">Update</button>
          <button on:click={handleDelete} class="btn btn-danger">Delete</button>
        {/if}
      </div>
    </div>
  </main>
</div>

<style lang="scss">
  @import '../../../styles/pokemon-details.scss';
</style>