<script lang="ts">
  import { onMount } from 'svelte';
  import { page } from '$app/stores';
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
  {:else}
    <p>Loading...</p>
  {/if}
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
    align-items: center;
    background-color: $card-background;
    border-radius: $border-radius;
    padding: $spacing-medium;
    color: $background-color;
  }

  .pokemon-image {
    width: 200px;
    height: 200px;
    background-color: $background-color;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: $spacing-large;

    .image-placeholder {
      font-size: $font-size-xlarge;
      color: $primary-color;
    }
  }

  .pokemon-info {
    flex: 1;

    p {
      margin: $spacing-small 0;
      font-size: $font-size-base;

      strong {
        color: $secondary-color;
      }
    }
  }
</style>