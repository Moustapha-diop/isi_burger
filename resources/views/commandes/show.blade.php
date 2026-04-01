@extends('layouts.app')
@section('title', 'Commande #' . $commande->id)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('client.commandes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Commande {{ $commande->id }}</h4>
    <span class="badge badge-statut-{{ $commande->statut }} px-3 py-2">
        {{ str_replace('_', ' ', strtoupper($commande->statut)) }}
    </span>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Détail des articles</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Burger</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commande->ligneCommandes as $ligne)
                        <tr>
                            <td>{{ $ligne->burger->nom }}</td>
                            <td class="text-center">{{ $ligne->quantite }}</td>
                            <td class="text-end">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="text-end fw-bold">{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="text-end fw-bold fs-5">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">Informations</div>
            <div class="card-body">
                <p class="mb-1"><strong>Date :</strong> {{ $commande->date->format('d/m/Y à H:i') }}</p>
                <p class="mb-0"><strong>Statut :</strong>
                    <span class="badge badge-statut-{{ $commande->statut }}">
                        {{ str_replace('_', ' ', strtoupper($commande->statut)) }}
                    </span>
                </p>
            </div>
        </div>

        @if($commande->paiement)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold text-success">
                <i class="bi bi-check-circle me-1"></i>Paiement
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Montant :</strong> {{ number_format($commande->paiement->montant, 0, ',', ' ') }} FCFA</p>
                <p class="mb-0"><strong>Date :</strong> {{ $commande->paiement->date_paiement->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        @endif

        @if(isset($commande->paiement->facture) && $commande->paiement->facture->fichier_pdf)
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <a href="{{ asset('storage/' . $commande->paiement->facture->fichier_pdf) }}"
                   class="btn btn-outline-primary w-100" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-2 fs-5"></i>
                    Télécharger la facture<br>
                    <small class="text-muted">{{ $commande->paiement->facture->numero }}</small>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
