export interface Pokemon {
    id: number;
    name: string;
    type: string;
    evolves_from: string | null;
    evolves_to: string | null;
    notes: string;
    created_at: string;
    updated_at: string;
  }