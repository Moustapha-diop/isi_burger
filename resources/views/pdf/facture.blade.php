<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; 
        font-size: 12px;
        color: #1d1d1d;
        margin: 0; padding: 20px; 
    }

    .header { display: flex; 
        justify-content: space-between;
        align-items: flex-start; 
        margin-bottom: 30px; 
    }

    .brand { font-size: 26px;
        font-weight: bold; 
        color: #e63946;
     }

    .brand small { font-size: 11px; 
        color: #777; 
        display: block; 
    }

    .facture-info { 
        text-align: right;
     }

    .facture-info h2 {
         font-size: 18px; color: #e63946; margin: 0; 
        }

    hr { 
        border-color: #e63946; 
        margin: 15px 0; 
    }

    .section { 
        margin-bottom: 20px; 
    }

    .section h4 { 
        font-size: 13px; 
        text-transform: uppercase; 
        color: #555; 
        margin-bottom: 8px; 
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
    }

    thead th { 
        background: #1d1d1d; 
        color: #fff; 
        padding: 8px 10px; 
        text-align: left; 
        font-size: 11px; 
    }

    tbody td { 
        padding: 7px 10px;
         border-bottom: 1px solid #eee; 
    }

    tbody tr:last-child td {
         border-bottom: none; 
        }

    .total-row td { 
        background: #f8f8f8; 
        font-weight: bold; 
        font-size: 14px; 
    }

    .footer { 
        margin-top: 40px; 
        text-align: center; 
        color: #999; 
        font-size: 10px; 
    }

    .badge { 
        padding: 3px 10px; 
        border-radius: 12px; 
        font-size: 10px; 
        font-weight: bold; 
    }

    .badge-payee { 
        background: #d4edda; 
        color: #155724; 
     }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="brand"> ISI BURGER
            <small>Restaurant – IsiKM, Dakar, Sénégal</small>
        </div>
    </div>
    <div class="facture-info">
        <h2>FACTURE</h2>
        <p style="margin:2px 0"><strong>N° :</strong> {{ $facture->numero }}</p>
        <p style="margin:2px 0"><strong>Date :</strong> {{ $facture->date->format('d/m/Y') }}</p>
        <span class="badge badge-payee">PAYÉE</span>
    </div>
</div>
<hr>

<div class="section">
    <h4>Client</h4>
    <p style="margin:2px 0"><strong>{{ $commande->client->user->name }}</strong></p>
    <p style="margin:2px 0">{{ $commande->client->user->email }}</p>
    <p style="margin:2px 0">{{ $commande->client->telephone ?? '' }}</p>
    <p style="margin:2px 0">{{ $commande->client->adresse ?? '' }}</p>
</div>

<div class="section">
    <h4>Détails de la commande Numero{{ $commande->id }}</h4>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th style="text-align:center">Qté</th>
                <th style="text-align:right">Prix unitaire</th>
                <th style="text-align:right">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->ligneCommandes as $ligne)
            <tr>
                <td>{{ $ligne->burger->nom }}</td>
                <td style="text-align:center">{{ $ligne->quantite }}</td>
                <td style="text-align:right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td style="text-align:right">{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align:right">TOTAL</td>
                <td style="text-align:right">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>
</div>

@if($commande->paiement)
<div class="section">
    <h4>Paiement</h4>
    <p style="margin:2px 0"><strong>Mode :</strong> Espèces</p>
    <p style="margin:2px 0"><strong>Montant reçu :</strong> {{ number_format($commande->paiement->montant_especes, 0, ',', ' ') }} FCFA</p>
    <p style="margin:2px 0"><strong>Rendu monnaie :</strong> {{ number_format($commande->paiement->montant_especes - $commande->total, 0, ',', ' ') }} FCFA</p>
    <p style="margin:2px 0"><strong>Date de paiement :</strong> {{ $commande->paiement->date_paiement->format('d/m/Y H:i') }}</p>
</div>
@endif

<div class="footer">
    <p>Merci pour votre confiance !  ISI BURGER – IsiKM, Dakar, Sénégal</p>
    <p>Ce document est votre justificatif de paiement.</p>
</div>

</body>
</html>
