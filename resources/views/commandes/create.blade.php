@extends('layouts.app')
@section('title', 'Passer une commande')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-cart3 me-2 text-danger"></i>Passer une commande</h4>

<form action="{{ route('client.commandes.store') }}" method="POST" id="formCommande">
    @csrf

    <div class="row">
        {{-- Sélection des burgers --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Choisir vos burgers</div>
                <div class="card-body">
                    <div id="lignes">
                        <div class="ligne-commande row g-2 mb-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Burger</label>
                                <select name="lignes[0][burger_id]" class="form-select burger-select" required>
                                    <option value="">Choisir</option>
                                    @foreach($burgers as $burger)
                                        <option value="{{ $burger->id }}"
                                                data-prix="{{ $burger->prix }}"
                                                data-nom="{{ $burger->nom }}">
                                            {{ $burger->nom }} — {{ number_format($burger->prix, 0, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantité</label>
                                <input type="number" name="lignes[0][quantite]" class="form-control quantite-input"
                                       value="1" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sous-total</label>
                                <input type="text" class="form-control soustotal" readonly value="0 FCFA">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger btn-remove-ligne" style="visibility:hidden;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm" id="addLigne">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter un burger
                    </button>
                </div>
            </div>
        </div>

        {{-- Résumé --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:20px">
                <div class="card-header bg-dark text-white fw-bold">Résumé</div>
                <div class="card-body">
                    <div id="resume-lignes" class="mb-3 small"></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span id="total-display" class="text-danger">0 FCFA</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-isi w-100">
                        <i class="bi bi-check2-circle me-1"></i> Confirmer la commande
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let ligneIndex = 1;

function updateTotaux() {
    let total = 0;
    let resumeHtml = '';

    document.querySelectorAll('.ligne-commande').forEach(ligne => {
        const select = ligne.querySelector('.burger-select');
        const qte    = parseInt(ligne.querySelector('.quantite-input').value) || 0;
        const prix   = parseFloat(select.selectedOptions[0]?.dataset.prix || 0);
        const nom    = select.selectedOptions[0]?.dataset.nom || '';
        const sous   = prix * qte;

        ligne.querySelector('.soustotal').value = sous.toLocaleString('fr-FR') + ' FCFA';
        if (nom) {
            total += sous;
            resumeHtml += `<div class="d-flex justify-content-between">
                <span>${nom} x${qte}</span>
                <span>${sous.toLocaleString('fr-FR')} FCFA</span>
            </div>`;
        }
    });

    document.getElementById('total-display').textContent = total.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('resume-lignes').innerHTML = resumeHtml || '<em class="text-muted">Aucun article</em>';
}

document.getElementById('addLigne').addEventListener('click', () => {
    const template = document.querySelector('.ligne-commande').cloneNode(true);
    template.querySelectorAll('select, input[type=number]').forEach(el => el.value = el.tagName === 'SELECT' ? '' : 1);
    template.querySelector('.soustotal').value = '0 FCFA';
    template.querySelector('.btn-remove-ligne').style.visibility = 'visible';

    // Update indexes
    template.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${ligneIndex}]`);
    });
    ligneIndex++;

    template.querySelector('.btn-remove-ligne').addEventListener('click', function() {
        this.closest('.ligne-commande').remove();
        updateTotaux();
    });

    document.getElementById('lignes').appendChild(template);
    template.querySelectorAll('.burger-select, .quantite-input').forEach(el => {
        el.addEventListener('change', updateTotaux);
    });
});

document.querySelectorAll('.burger-select, .quantite-input').forEach(el => {
    el.addEventListener('change', updateTotaux);
});
</script>
@endpush
