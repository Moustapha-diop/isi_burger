@extends('layouts.app')
@section('title', 'Catalogue – ISI BURGER')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-shop me-2 text-danger"></i>Catalogue des Burgers</h4>
    <a href="{{ route('client.commandes.create') }}" class="btn btn-isi">
        <i class="bi bi-cart-plus me-1"></i> Passer une commande
    </a>
</div>

{{-- Filtres --}}
<form method="GET" class="row g-2 mb-4 bg-white p-3 rounded shadow-sm">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Rechercher..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="categorie_id" class="form-select">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->libelle }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="prix_min" class="form-control" placeholder="Prix min (FCFA)"
               value="{{ request('prix_min') }}">
    </div>
    <div class="col-md-2">
        <input type="number" name="prix_max" class="form-control" placeholder="Prix max (FCFA)"
               value="{{ request('prix_max') }}">
    </div>
    <div class="col-md-2 d-flex gap-1">
        <button type="submit" class="btn btn-isi flex-fill">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('client.catalogue') }}" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-x"></i>
        </a>
    </div>
</form>

{{-- Grille burgers --}}
<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse($burgers as $burger)
        <div class="col">
            <div class="card h-100 shadow-sm border-0 {{ !$burger->isDisponible() ? 'opacity-50' : '' }}">
                @if($burger->image)
                    <img src="{{ asset('storage/' . $burger->image) }}"
                         class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $burger->nom }}">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                         style="height:180px;font-size:4rem;">🍔</div>
                @endif
                <div class="card-body">
                    <h6 class="card-title fw-bold mb-1">{{ $burger->nom }}</h6>
                    <small class="text-muted">{{ $burger->categorie->libelle }}</small>
                    <p class="card-text small mt-1 text-muted" style="line-height:1.3">
                        {{ Str::limit($burger->description, 60) }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold" style="color:#ea7312;">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</span>
                        @if($burger->isDisponible())
                            <span class="badge bg-success">Stock: {{ $burger->stock }}</span>
                        @else
                            <span class="badge bg-danger">Indisponible</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('client.burgers.show', $burger) }}"
                       class="btn btn-sm btn-outline-danger w-100">Voir détails</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Aucun burger disponible pour le moment.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $burgers->links() }}</div>
@endsection
