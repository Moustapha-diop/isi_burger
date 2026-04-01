@extends('layouts.app')
@section('title', 'Gestion des Burgers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-grid me-2 text-danger"></i>Gestion des Burgers</h4>
    <a href="{{ route('gestionnaire.burgers.create') }}" class="btn btn-isi">
        <i class="bi bi-plus-circle me-1"></i> Nouveau burger
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-secondary text-uppercase">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($burgers as $burger)
                    <tr class="{{ $burger->archive ? 'table-secondary opacity-75' : '' }}">
                        <td>
                            @if($burger->image)
                                <img src="{{ asset('storage/'. $burger->image) }}"
                                     width="50" height="50" style="object-fit:cover;border-radius:8px;"
                                     alt="{{ $burger->nom }}">
                            @else
                                <span style="font-size:2rem">🍔</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $burger->nom }}</td>
                        <td>
                            <span class="text-dark ">{{ $burger->categorie->libelle }}</span>
                        </td>
                        <td class="text-danger fw-bold">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($burger->stock > 5)
                                <span class="text-dark">{{ $burger->stock }}</span>
                            @elseif($burger->stock > 0)
                                <span class="badge bg-warning text-dark">{{ $burger->stock }}</span>
                            @else
                                <span class="badge bg-danger">Rupture</span>
                            @endif
                        </td>
                        <td>
                            @if($burger->archive)
                                <span class="badge bg-secondary">Archivé</span>
                            @else
                                <span class="badge bg-success">Actif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('gestionnaire.burgers.edit', $burger) }}"
                                   class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!$burger->archive)
                                <form action="{{ route('gestionnaire.burgers.archiver', $burger) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Archiver"
                                            onclick="return confirm('Archiver ce burger ?')">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('gestionnaire.burgers.destroy', $burger) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                            onclick="return confirm('Supprimer définitivement ce burger ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Aucun burger enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $burgers->links() }}</div>
@endsection
