Certainly! Here's a detailed README for the Pokodex application, including folder structure and routes:

# Pokodex Application

Pokodex is a full-stack application that allows users to search for and view details about Pokémon. It consists of three main components: a Laravel backend, a Svelte frontend, and a Python AI service for data scraping.

## Folder Structure

```
pokodex/
├── laravel-backend-service/
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       └── PokemonController.php
│   │   └── Models/
│   │       ├── Pokemon.php
│   │       └── User.php
│   ├── config/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── DatabaseSeeder.php
│   │       └── PokemonSeeder.php
│   ├── routes/
│   │   ├── api.php
│   │   └── web.php
│   ├── storage/
│   ├── tests/
│   └── .env
├── svelte-frontend-service/
│   ├── src/
│   │   ├── lib/
│   │   ├── routes/
│   │   └── app.html
│   ├── static/
│   └── svelte.config.js
├── python-ai-service/
│   ├── Scraper/
│   │   ├── __init__.py
│   │   └── wikiScraper.py
│   ├── main.py
│   └── requirements.txt
└── docker-compose.yml
```

## Components

### 1. Laravel Backend Service

The Laravel backend handles data storage, retrieval, and API endpoints for the Pokémon data.

#### Key Files:
- `app/Http/Controllers/PokemonController.php`: Handles CRUD operations for Pokémon data.
- `app/Models/Pokemon.php`: Defines the Pokemon model.
- `database/migrations/2024_08_13_105302_create_pokemons_table.php`: Defines the schema for the Pokémon table.
- `database/seeders/PokemonSeeder.php`: Seeds the database with initial Pokémon data.

#### Routes:
Defined in `routes/api.php`:
- `GET /api/pokemon`: Retrieve all Pokémon
- `GET /api/pokemon/{id}`: Retrieve a specific Pokémon
- `POST /api/pokemon`: Create a new Pokémon
- `PUT /api/pokemon/{id}`: Update a Pokémon
- `DELETE /api/pokemon/{id}`: Delete a Pokémon

### 2. Svelte Frontend Service

The Svelte frontend provides the user interface for interacting with the Pokodex.

#### Key Files:
- `src/routes/+page.svelte`: The main page component.
- `src/app.html`: The main HTML template.

#### Routes:
- `/`: Home page (defined in `src/routes/+page.svelte`)

### 3. Python AI Service

The Python service is responsible for scraping Pokémon data from Wikipedia.

#### Key Files:
- `main.py`: Defines the FastAPI application and routes.
- `Scraper/wikiScraper.py`: Contains the PokemonScraper class for web scraping.

#### Routes:
- `GET /pokemond-data`: Retrieves scraped Pokémon data

## Setup and Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   cd pokodex
   ```

2. Set up environment variables:
   - Copy `.env.example` to `.env` in the Laravel backend and configure database settings.

3. Install dependencies:
   - For Laravel: `composer install`
   - For Svelte: `npm install`
   - For Python: `pip install -r requirements.txt`

4. Run migrations and seed the database:
   ```
   php artisan migrate
   php artisan db:seed
   ```

5. Start the services using Docker:
   ```
   docker-compose up
   ```

## Usage

- Access the Svelte frontend at `http://localhost:3000`
- The Laravel API is available at `http://localhost:8000/api`
- The Python scraping service can be accessed at `http://localhost:8001`


## Data Model

The Pokodex application uses a relational database to store Pokémon information. The primary data model is the `Pokemon` entity, which is defined in the Laravel backend.

### Pokemon Model

The Pokemon model is defined in `laravel-backend-service/app/Models/Pokemon.php`:

```php:laravel-backend-service/app/Models/Pokemon.php
class Pokemon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'evolves_from',
        'evolves_to',
        'notes',
    ];
}
```

The corresponding database schema is defined in the migration file `laravel-backend-service/database/migrations/2024_08_13_111842_update_pokemon_table.php`:

```php:laravel-backend-service/database/migrations/2024_08_13_111842_update_pokemon_table.php
public function up()
{
    Schema::create('pokemon', function (Blueprint $table) {
        $table->id();
        $table->string('name', 1000);
        $table->string('type', 1000)->nullable();
        $table->string('evolves_from', 1000)->nullable();
        $table->string('evolves_to', 1000)->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}
```

### Data Fields

- `id`: Unique identifier for each Pokémon (auto-incrementing integer)
- `name`: The name of the Pokémon (string, up to 1000 characters)
- `type`: The type(s) of the Pokémon (string, up to 1000 characters, nullable)
- `evolves_from`: The Pokémon this one evolves from (string, up to 1000 characters, nullable)
- `evolves_to`: The Pokémon this one evolves into (string, up to 1000 characters, nullable)
- `notes`: Additional notes about the Pokémon (text, nullable)
- `created_at` and `updated_at`: Timestamps for record creation and last update

## API Endpoints

The Laravel backend provides the following API endpoints for interacting with the Pokémon data, as defined in `laravel-backend-service/app/Http/Controllers/PokemonController.php`:

- `GET /api/pokemon`: Retrieve all Pokémon
- `GET /api/pokemon/{pokemon}`: Retrieve a specific Pokémon
- `POST /api/pokemon`: Create a new Pokémon
- `PUT /api/pokemon/{pokemon}`: Update a Pokémon
- `DELETE /api/pokemon/{pokemon}`: Delete a Pokémon

### Controller Methods

1. `index()`: Retrieves all Pokémon
2. `show(Pokemon $pokemon)`: Retrieves a specific Pokémon
3. `store(Request $request)`: Creates a new Pokémon
4. `update(Request $request, Pokemon $pokemon)`: Updates an existing Pokémon
5. `destroy(Pokemon $pokemon)`: Deletes a Pokémon

Each method includes error handling and logging.

## Data Flow

1. **Data Scraping**: The Python AI service (`python-ai-service/Scraper/wikiScraper.py`) scrapes Pokémon data from Wikipedia.

2. **Data Seeding**: The scraped data is used to seed the database using the `PokemonSeeder` class in `laravel-backend-service/database/seeders/PokemonSeeder.php`.

3. **Data Storage and Retrieval**: The Laravel backend handles CRUD operations for Pokémon data through the `PokemonController`.

4. **Frontend Interaction**: The Svelte frontend interacts with the Laravel backend API to display and manage Pokémon data.

# Run migrations
docker-compose exec laravel-backend php artisan migrate

# Run the seeding command 
docker-compose exec laravel-backend php artisan db:seed