# Pokodex Application

Pokodex is a full-stack application that allows users to search for and view details about Pokémon. It consists of three main components: a Laravel backend, a Svelte frontend, and a Python AI service for data scraping.

## Prerequisites

- Docker and Docker Compose
- Git

## Quick Start

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/pokodex.git
   cd pokodex
   ```

2. Set up environment variables:
   ```
   cp laravel-backend-service/.env.example laravel-backend-service/.env
   ```
   Edit `laravel-backend-service/.env` and configure your database settings.

3. Build and start the Docker containers:
   ```
   docker-compose up --build -d
   ```

4. Install dependencies:
   ```
   docker-compose exec laravel-backend composer install
   docker-compose exec svelte-frontend npm install
   docker-compose exec python-ai-service pip install -r requirements.txt
   ```

5. Run migrations and seed the database:
   ```
   docker-compose exec laravel-backend php artisan migrate
   docker-compose exec laravel-backend php artisan db:seed
   ```

6. Access the application:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000/api
   - Python service: http://localhost:8001

## Detailed Setup

### Laravel Backend Service

1. Navigate to the Laravel backend directory:
   ```
   cd laravel-backend-service
   ```

2. Install PHP dependencies:
   ```
   docker-compose exec laravel-backend composer install
   ```

3. Generate application key:
   ```
   docker-compose exec laravel-backend php artisan key:generate
   ```

4. Run migrations:
   ```
   docker-compose exec laravel-backend php artisan migrate
   ```

5. Seed the database:
   ```
   docker-compose exec laravel-backend php artisan db:seed
   ```

### Svelte Frontend Service

1. Navigate to the Svelte frontend directory:
   ```
   cd svelte-frontend-service
   ```

2. Install Node.js dependencies:
   ```
   docker-compose exec svelte-frontend npm install
   ```

3. Build the frontend:
   ```
   docker-compose exec svelte-frontend npm run build
   ```

### Python AI Service

1. Navigate to the Python AI service directory:
   ```
   cd python-ai-service
   ```

2. Install Python dependencies:
   ```
   docker-compose exec python-ai-service pip install -r requirements.txt
   ```

## Usage

After setting up all services, you can use the Pokodex application:

1. Open your web browser and go to `http://localhost:3000` to access the Svelte frontend.
2. Use the search functionality to find Pokémon.
3. Click on a Pokémon to view its details.

## Development

To work on the project in development mode:

1. For the Laravel backend:
   ```
   docker-compose exec laravel-backend php artisan serve
   ```

2. For the Svelte frontend:
   ```
   docker-compose exec svelte-frontend npm run dev
   ```

3. For the Python AI service:
   ```
   docker-compose exec python-ai-service python main.py
   ```

## Troubleshooting

- If you encounter any issues with database connections, ensure your `.env` file in the Laravel backend is correctly configured.
- For permission issues, you may need to run Docker commands with `sudo` (on Linux/macOS).
- If the Python service fails to scrape data, check your internet connection and ensure the Wikipedia page structure hasn't changed.

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

# Docker command that you can use to setup the project

## Run migrations
docker-compose exec laravel-backend php artisan migrate

## Run the seeding command 
docker-compose exec laravel-backend php artisan db:seed