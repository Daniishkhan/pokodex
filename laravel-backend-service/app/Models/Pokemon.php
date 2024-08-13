<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
