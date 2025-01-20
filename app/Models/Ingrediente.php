<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;
    protected $table = 'ingredientes';
    protected $fillable = ['nombre', 'categoria'];

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class);
    }
}
