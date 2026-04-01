<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    protected $fillable = [
        'nom', 'prix', 'description', 'image',
        'stock', 'archive', 'categorie_id',
    ];

    protected $casts = [
        'archive' => 'boolean',
        'prix'    => 'float',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function isDisponible(): bool
    {
        return !$this->archive && $this->stock > 0;
    }

    public function archiver(): void
    {
        $this->update(['archive' => true]);
    }

    // Scope : non archivés
    public function scopeDisponible($query)
    {
        return $query->where('archive', false)->where('stock', '>', 0);
    }
}
