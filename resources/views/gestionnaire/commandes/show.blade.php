@extends('layouts.app')
@section('title', 'Commande #' . $commande->id)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('gestionnaire.commandes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Commande numéro {{ $commande->id }}</h4>
    <span class="badge badge-statut-{{ $commande->statut }} px-3 py-2">
        {{ str_replace('_', ' ', strtoupper($commande->statut)) }}
    </span>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Lignes de commande --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Articles commandés</div>
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
                        <tr class="table-dark text-uppercase">
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="text-end fw-bold fs-5">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Changer statut --}}
        @if(!in_array($commande->statut, ['payee', 'annulee']))
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Modifier le statut</div>
            <div class="card-body">
                {{-- Statut --}}
                <form action="{{ route('gestionnaire.commandes.statut', $commande) }}" method="POST"
                      class="row g-2 align-items-end mb-3">
                    @csrf @method('PATCH')
                    <div class="col-md-7">
                        <select name="statut" class="form-select">
                            @foreach(['en_attente','en_preparation','prete'] as $s)
                                <option value="{{ $s }}" {{ $commande->statut == $s ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', ucfirst($s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-isi w-100">Mettre à jour</button>
                    </div>
                </form>
                
                <form action="{{ route('gestionnaire.commandes.annuler', $commande) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger w-100"
                            onclick="return confirm('Annuler cette commande ?')">
                        <i class="bi bi-x-circle me-1"></i> Annuler la commande
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

 {{-- Infos client --}}
    <div class="col-md-4">
       
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Informations client</div>
            <div class="card-body">
                <p class="mb-1"><strong>Nom :</strong> {{ $commande->client->user->name }}</p>
                <p class="mb-1"><strong>Email :</strong> {{ $commande->client->user->email }}</p>
                <p class="mb-1"><strong>Tél :</strong> {{ $commande->client->telephone ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Adresse :</strong> {{ $commande->client->adresse ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Paiement --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Paiement</div>
            <div class="card-body">
                @if($commande->paiement)
                    <p class="mb-1 text-success fw-bold"><i class="bi bi-check-circle me-1"></i>Payée</p>
                    <p class="mb-1"><strong>Montant :</strong> {{ number_format($commande->paiement->montant, 0, ',', ' ') }} FCFA</p>
                    <p class="mb-1"><strong>Date :</strong> {{ $commande->paiement->date_paiement->format('d/m/Y H:i') }}</p>
                    @if($commande->paiement->facture)
                        <a href="{{ asset('storage/' . $commande->paiement->facture->fichier_pdf) }}"
                           class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                            <i class="bi bi-file-pdf me-1"></i> Voir la facture
                        </a>
                    @endif
                @elseif($commande->statut === 'prete')
                    <a href="{{ route('gestionnaire.paiements.create', $commande) }}"
                       class="btn btn-success w-100">
                        <i class="bi bi-cash me-1"></i> Enregistrer le paiement
                    </a>
                @else
                    <p class="text-muted small mb-0">En attente que la commande soit prête.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
