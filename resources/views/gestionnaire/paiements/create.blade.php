@extends('layouts.app')
@section('title', 'Enregistrer un paiement')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('gestionnaire.commandes.show', $commande) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Paiement – Commande Numéro {{ $commande->id }}</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Enregistrer le paiement en espèces</div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <strong>Montant à payer :</strong>
                    {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                </div>

                <form action="{{ route('gestionnaire.paiements.store', $commande) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Montant reçu en espèces (FCFA)</label>
                        <input type="number" name="montant_especes" class="form-control form-control-lg
                               @error('montant_especes') is-invalid @enderror"
                               min="{{ $commande->total }}" step="1" required
                               placeholder="{{ $commande->total }}">
                        @error('montant_especes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Doit être ≥ {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded" id="rendu-monnaie" style="display:none">
                        <strong>Rendu monnaie :</strong>
                        <span id="rendu-value" class="text-success fw-bold fs-5"></span> FCFA
                    </div>

                    <button type="submit" class="btn btn-success w-100 btn-lg">
                        <i class="bi bi-check2-circle me-2"></i>
                        Valider le paiement et générer la facture
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const total = {{ $commande->total }};
document.querySelector('[name=montant_especes]').addEventListener('input', function() {
    const recu = parseFloat(this.value) || 0;
    const rendu = recu - total;
    const div = document.getElementById('rendu-monnaie');
    if (recu >= total) {
        div.style.display = 'block';
        document.getElementById('rendu-value').textContent = rendu.toLocaleString('fr-FR');
    } else {
        div.style.display = 'none';
    }
});
</script>
@endpush
