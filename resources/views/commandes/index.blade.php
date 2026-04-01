@extends('layouts.app')
@section('title', 'Mes Commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bag me-2 text-danger"></i>Mes Commandes</h4>
    <a href="{{ route('client.commandes.create') }}" class="btn btn-isi btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle commande
    </a>
</div>

@forelse($commandes as $commande)
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-2">
                <span class="fw-bold text-muted">Commande {{ $commande->id }}</span><br>
                <small class="text-muted">{{ $commande->date->format('d/m/Y H:i') }}</small>
            </div>
            <div class="col-md-4">
                @foreach($commande->ligneCommandes->take(2) as $ligne)
                    <small>{{ $ligne->burger->nom }} x{{ $ligne->quantite }}</small><br>
                @endforeach
                @if($commande->ligneCommandes->count() > 2)
                    <small class="text-muted">+{{ $commande->ligneCommandes->count() - 2 }} autres...</small>
                @endif
            </div>
            <div class="col-md-2 text-center">
                <span class="badge badge-statut-{{ $commande->statut }} px-3 py-2">
                    {{ str_replace('_', ' ', strtoupper($commande->statut)) }}
                </span>
            </div>
            <div class="col-md-2 text-end fw-bold text-danger">
                {{ number_format($commande->total, 0, ',', ' ') }} FCFA
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('client.commandes.show', $commande) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i> Détails
                </a>
            </div>
        </div>
    </div>
</div>
@empty
    <div class="alert alert-info">
        Vous n'avez pas encore de commande.
        <a href="{{ route('client.commandes.create') }}" class="alert-link">Passer votre première commande !</a>
    </div>
@endforelse

{{ $commandes->links() }}
@endsection
