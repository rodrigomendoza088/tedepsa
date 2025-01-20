<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PizzaIngrediente extends Pivot
{
    use HasFactory;

    protected $table = 'pizza_ingrediente';
    // No es necesario definir $fillable 
}
