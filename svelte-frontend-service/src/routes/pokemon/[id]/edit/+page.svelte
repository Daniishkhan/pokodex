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

<main>
    <h1>Edit Pokemon</h1>
    {#if loading}
        <p class="loading">Loading...</p>
    {:else if error}
        <p class="error">{error}</p>
    {:else if pokemon}
        <div class="edit-card">
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
        </div>
    {:else}
        <p class="no-data">No Pokemon data available</p>
    {/if}
    
    {#if successMessage}
        <div class="success-message">{successMessage}</div>
    {/if}
</main>

<style lang="scss">
    $primary-color: #3d7dca;
    $secondary-color: #ffcb05;
    $background-color: #f0f0f0;
    $card-background: #ffffff;
    $text-color: #333333;
    $error-color: #ff3860;
    $success-color: #23d160;

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
        margin-bottom: $spacing-large;
    }

    .edit-card {
        background-color: $card-background;
        border-radius: $border-radius;
        padding: $spacing-large;
        box-shadow: $box-shadow;
        transition: box-shadow $transition-speed ease;

        &:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    }

    .edit-form {
        .form-group {
            margin-bottom: $spacing-medium;

            label {
                display: block;
                margin-bottom: $spacing-small;
                font-weight: bold;
                color: $primary-color;
            }

            input, textarea {
                width: 100%;
                padding: $spacing-medium;
                font-size: $font-size-base;
                border: 2px solid $primary-color;
                border-radius: $border-radius;
                background-color: $card-background;
                color: $text-color;
                transition: box-shadow $transition-speed ease;

                &:focus {
                    outline: none;
                    box-shadow: 0 0 0 3px rgba($primary-color, 0.3);
                }
            }

            textarea {
                height: 100px;
                resize: vertical;
            }
        }
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: $spacing-large;

        .btn {
            padding: $spacing-medium $spacing-large;
            font-size: $font-size-base;
            border: none;
            border-radius: $border-radius;
            cursor: pointer;
            text-decoration: none;
            color: $text-color;
            transition: background-color $transition-speed ease, transform $transition-speed ease;

            &:hover {
                transform: translateY(-2px);
            }

            &.btn-primary {
                background-color: $primary-color;
                color: $card-background;

                &:hover {
                    background-color: darken($primary-color, 10%);
                }
            }

            &.btn-secondary {
                background-color: $secondary-color;

                &:hover {
                    background-color: darken($secondary-color, 10%);
                }
            }
        }
    }

    .loading, .error, .no-data, .success-message {
        text-align: center;
        font-size: $font-size-base;
        margin: $spacing-medium 0;
        padding: $spacing-medium;
        border-radius: $border-radius;
    }

    .error {
        color: $card-background;
        background-color: $error-color;
    }

    .success-message {
        color: $card-background;
        background-color: $success-color;
    }
</style>