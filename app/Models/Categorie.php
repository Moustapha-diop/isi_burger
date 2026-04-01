<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['libelle'];

    public function burgers()
    {
        return $this->hasMany(Burger::class);
    }

    public function getBurgers()
    {
        return $this->burgers()->where('archive', false)->get();
    }
}
