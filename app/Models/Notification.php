<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['commande_id', 'message', 'type', 'lu'];

    const TYPES = ['CONFIRMATION', 'NOUVELLE_COMMANDE', 'FACTURE'];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function envoyer(): void
    {
        $this->save();
    }

    public static function creer(string $type, string $message, ?int $commandeId = null): self
    {
        $notif = new self([
            'type'        => $type,
            'message'     => $message,
            'commande_id' => $commandeId,
            'lu'          => false,
        ]);
        $notif->envoyer();
        return $notif;
    }
}
