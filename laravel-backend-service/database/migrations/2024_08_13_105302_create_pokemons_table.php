<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('pokemons')) {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('evolves_from')->nullable();
            $table->string('evolves_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
}
    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
