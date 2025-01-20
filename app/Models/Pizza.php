<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    //
    use HasFactory;

    protected $table = 'pizzas';
    protected $fillable = ['nombre', 'precio', 'estado'];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class);
    }
}
