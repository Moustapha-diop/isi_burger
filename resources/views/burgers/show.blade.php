@extends('layouts.app')
@section('title', $burger->nom)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('client.catalogue') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">{{ $burger->nom }}</h4>
</div>

<div class="row">
    <div class="col-md-5">
        @if($burger->image)
            <img src="{{ asset('storage/' . $burger->image) }}"
                 class="img-fluid rounded-3 shadow" alt="{{ $burger->nom }}"
                 style="width:100%;max-height:380px;object-fit:cover;">
        @else
            <div class="d-flex align-items-center justify-content-center bg-light rounded-3 shadow"
                 style="height:380px;font-size:8rem;">🍔</div>
        @endif
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex flex-column">
                <div class="mb-2">
                    <span class="badge bg-light text-dark border me-2">{{ $burger->categorie->libelle }}</span>
                    @if($burger->isDisponible())
                        <span class="badge bg-success">Disponible</span>
                    @else
                        <span class="badge bg-danger">Indisponible</span>
                    @endif
                </div>

                <h2 class="fw-bold mb-1">{{ $burger->nom }}</h2>
                <div class="fs-3 fw-bold text-danger mb-3">
                    {{ number_format($burger->prix, 0, ',', ' ') }} FCFA
                </div>

                <p class="text-muted flex-grow-1" style="line-height:1.7">
                    {{ $burger->description ?? 'Aucune description disponible.' }}
                </p>

                <div class="text-muted small mb-4">
                    <i class="bi bi-box-seam me-1"></i> Stock disponible : <strong>{{ $burger->stock }}</strong>
                </div>

                @if($burger->isDisponible())
                    <a href="{{ route('client.commandes.create') }}" class="btn btn-isi btn-lg">
                        <i class="bi bi-cart-plus me-2"></i> Commander maintenant
                    </a>
                @else
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-x-circle me-2"></i> Indisponible
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
