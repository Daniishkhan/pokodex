<script lang="ts">
  import { onMount } from 'svelte';
  import { page } from '$app/stores';
  import { goto } from '$app/navigation';
  import type { Pokemon } from '$lib/types';

  let pokemon: Pokemon | null = null;

  onMount(async () => {
    const id = $page.params.id;
    try {
      const response = await fetch(`http://localhost:8000/api/pokemon/${id}`);
      const result = await response.json();
      if (result.status === 'success') {
        pokemon = result.data;
      } else {
        console.error('Failed to fetch Pokemon data');
      }
    } catch (error) {
      console.error('Error fetching Pokemon data:', error);
    }
  });

  function goBack() {
    window.history.back();
  }

  function editPokemon() {
    if (pokemon) {
      goto(`/pokemon/${pokemon.id}/edit`);
    }
  }

  async function deletePokemon() {
    if (pokemon) {
      if (confirm(`Are you sure you want to delete ${pokemon.name}?`)) {
        try {
          const response = await fetch(`http://localhost:8000/api/pokemon/${pokemon.id}`, {
            method: 'DELETE',
          });
          if (response.ok) {
            alert('Pokemon deleted successfully');
            goto('/');
          } else {
            alert('Failed to delete Pokemon');
          }
        } catch (error) {
          console.error('Error deleting Pokemon:', error);
          alert('Error deleting Pokemon');
        }
      }
    }
  }
</script>

<main>
  {#if pokemon}
    <h1>{pokemon.name}</h1>
    <div class="pokemon-details">
      <div class="pokemon-image">
        <div class="image-placeholder">{pokemon.name[0]}</div>
      </div>
      <div class="pokemon-info">
        <p><strong>Type:</strong> {pokemon.type}</p>
        {#if pokemon.evolves_from}
          <p><strong>Evolves from:</strong> {pokemon.evolves_from}</p>
        {/if}
        {#if pokemon.evolves_to}
          <p><strong>Evolves to:</strong> {pokemon.evolves_to}</p>
        {/if}
        {#if pokemon.notes}
          <p><strong>Notes:</strong> {pokemon.notes}</p>
        {/if}
      </div>
    </div>
    <div class="button-container">
      <button class="button secondary" on:click={goBack}>Back to List</button>
      <button class="button" on:click={editPokemon}>Edit</button>
      <button class="button delete" on:click={deletePokemon}>Delete</button>
    </div>
  {:else}
    <p>Loading...</p>
  {/if}
</main>

<style lang="scss">
  $primary-color: #3d7dca;
  $secondary-color: #ffcb05;
  $background-color: #f0f0f0;
  $card-background: #ffffff;
  $text-color: #333333;
  $delete-color: #ff3860;

  $font-family: 'Arial', sans-serif;
  $font-size-base: 16px;
  $font-size-large: 1.2em;
  $font-size-xlarge: 2em;

  $spacing-small: 10px;
  $spacing-medium: 1rem;
  $spacing-large: 2rem;

  $border-radius: 10px;
  $box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

  main {
    max-width: 800px;
    margin: 0 auto;
    padding: $spacing-large;
    font-family: $font-family;
    background-color: $background-color;
    color: $text-color;
  }

  h1 {
    text-align: center;
    color: $primary-color;
    font-size: $font-size-xlarge;
    margin-bottom: $spacing-medium;
  }

  .pokemon-details {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: $card-background;
    border-radius: $border-radius;
    padding: $spacing-large;
    box-shadow: $box-shadow;
    margin-bottom: $spacing-large;
  }

  .pokemon-image {
    width: 200px;
    height: 200px;
    background-color: lighten($primary-color, 30%);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: $spacing-medium;

    .image-placeholder {
      font-size: $font-size-xlarge;
      color: $primary-color;
    }
  }

  .pokemon-info {
    text-align: center;
    width: 100%;

    p {
      margin: $spacing-small 0;
      font-size: $font-size-base;

      strong {
        color: $primary-color;
      }
    }
  }

  .button-container {
    display: flex;
    justify-content: center;
    gap: $spacing-medium;
  }

  .button {
    background-color: $primary-color;
    color: $card-background;
    border: none;
    border-radius: $border-radius;
    padding: $spacing-medium $spacing-large;
    font-size: $font-size-large;
    cursor: pointer;
    transition: background-color 0.3s ease;

    &:hover {
      background-color: darken($primary-color, 10%);
    }

    &.secondary {
      background-color: $secondary-color;
      color: $text-color;

      &:hover {
        background-color: darken($secondary-color, 10%);
      }
    }

    &.delete {
      background-color: $delete-color;

      &:hover {
        background-color: darken($delete-color, 10%);
      }
    }
  }
</style>