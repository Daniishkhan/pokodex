export type Pokemon = {
    id: number;
    name: string;
    type: string;
    evolves_from: string | null;
    evolves_to: string | null;
    notes: string;
};