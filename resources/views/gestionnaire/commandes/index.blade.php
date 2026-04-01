@extends('layouts.app')
@section('title', 'Gestion des Commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-danger"></i>Toutes les Commandes</h4>
</div>

{{-- Filtres --}}
<form method="GET" class="row g-2 mb-4 bg-white p-3 rounded shadow-sm">
    <div class="col-md-3">
        <select name="statut" class="form-select">
            <option value="">Tous les statuts</option>
            @foreach(['en_attente','en_preparation','prete','payee','annulee'] as $s)
                <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>
                    {{ str_replace('_', ' ', ucfirst($s)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-isi w-100">Filtrer</button>
    </div>
    <div class="col-md-1">
        <a href="{{ route('gestionnaire.commandes.index') }}" class="btn btn-outline-secondary w-100">
            <i class="bi bi-x"></i>
        </a>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-secondary text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                    <tr>
                        <td class="fw-bold">{{ $commande->id }}</td>
                        <td>{{ $commande->client->user->name }}</td>
                        <td>{{ $commande->date->format('d/m/Y H:i') }}</td>
                        <td>{{ $commande->ligneCommandes->count() }} article(s)</td>
                        <td class="fw-bold text-danger">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge badge-statut-{{ $commande->statut }} px-2 py-1">
                                {{ str_replace('_', ' ', strtoupper($commande->statut)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('gestionnaire.commandes.show', $commande) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucune commande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $commandes->links() }}</div>
@endsection
