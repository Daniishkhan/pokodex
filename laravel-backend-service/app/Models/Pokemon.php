<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'pokedex_number',
        'types',
        'abilities',
        'stats',
        'description',
        'image_url',
    ];
    
    protected $casts = [
        'types' => 'array',
        'abilities' => 'array',
        'stats' => 'array',
    ];
}
