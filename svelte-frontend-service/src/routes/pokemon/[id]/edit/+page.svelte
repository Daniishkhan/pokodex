<script lang="ts">
    import { onMount } from 'svelte';
    import { page } from '$app/stores';
    import { goto } from '$app/navigation';
    import type { Pokemon } from '$lib/types';

    let pokemon: Pokemon | null = null;
    let loading = true;
    let error: string | null = null;
    let successMessage: string | null = null;

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

    async function handleSubmit() {
        if (!pokemon) return;

        try {
            const response = await fetch(`http://localhost:8000/api/pokemon/${pokemon.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(pokemon),
            });

            if (response.ok) {
                successMessage = 'Pokemon updated successfully!';
                setTimeout(() => {
                    if (pokemon) goto(`/pokemon/${pokemon.id}`);
                }, 2000);
            } else {
                error = 'Failed to update Pokemon';
            }
        } catch (err) {
            error = 'Error updating Pokemon';
        }
    }
</script>

<svelte:head>
    <title>Edit {pokemon?.name ?? 'Pokemon'} | Pokodex</title>
</svelte:head>

<div class="container">
    <main>
        <h1>Edit Pokemon</h1>
        {#if loading}
            <p class="loading">Loading...</p>
        {:else if error}
            <p class="error">{error}</p>
        {:else if pokemon}
            <form on:submit|preventDefault={handleSubmit} class="edit-form">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" bind:value={pokemon.name} required>
                </div>
                <div class="form-group">
                    <label for="type">Type:</label>
                    <input type="text" id="type" bind:value={pokemon.type} required>
                </div>
                <div class="form-group">
                    <label for="evolves_from">Evolves from:</label>
                    <input type="text" id="evolves_from" bind:value={pokemon.evolves_from} required>
                </div>
                <div class="form-group">
                    <label for="evolves_to">Evolves to:</label>
                    <input type="text" id="evolves_to" bind:value={pokemon.evolves_to} required>
                </div>
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" bind:value={pokemon.notes} required></textarea>
                </div>
                <div class="action-buttons">
                    <a href="/pokemon/{pokemon.id}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        {:else}
            <p class="no-data">No Pokemon data available</p>
        {/if}
        
        {#if successMessage}
            <div class="success-message">{successMessage}</div>
        {/if}
    </main>
</div>

<style lang="scss">
    @import '../../../../styles/pokemon-edit.scss';
</style>