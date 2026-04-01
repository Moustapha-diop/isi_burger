@extends('layouts.app')
@section('title', 'Dashboard – ISI BURGER')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-danger"></i>Dashboard</h4>

{{-- Statistiques du jour --}}
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-white" style="background:var(--isi-red)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $stats['commandes_en_cours'] }}</div>
                    <div>Commandes en cours</div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $stats['commandes_validees'] }}</div>
                    <div>Commandes validées aujourd'hui</div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($stats['recettes_journalieres'], 0, ',', ' ') }}</div>
                    <div>Recettes du jour (FCFA)</div>
                </div>
                
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-bar-chart-line me-2 text-danger"></i>Commandes par mois
            </div>
            <div class="card-body">
                <canvas id="chartCommandes" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-pie-chart me-2 text-danger"></i>Produits par catégorie
            </div>
            <div class="card-body">
                <canvas id="chartCategories" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart 1 : Commandes par mois
const cmdData = @json($commandesParMois);
new Chart(document.getElementById('chartCommandes'), {
    type: 'bar',
    data: {
        labels: cmdData.map(d => d.label),
        datasets: [{
            label: 'Nombre de commandes',
            data: cmdData.map(d => d.total),
            backgroundColor: '#e63946cc',
            borderColor: '#e63946',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Chart 2 : Produits par catégorie
const catData = @json($produitsParCategorie);
const categories = [...new Set(catData.map(d => d.categorie))];
const mois       = [...new Set(catData.map(d => `${d.annee}-${String(d.mois).padStart(2,'0')}`))].sort();
const colors     = ['#e63946','#457b9d','#f4a261','#2a9d8f','#e9c46a'];

new Chart(document.getElementById('chartCategories'), {
    type: 'line',
    data: {
        labels: mois,
        datasets: categories.map((cat, i) => ({
            label: cat,
            data: mois.map(m => {
                const [a, mo] = m.split('-').map(Number);
                const found = catData.find(d => d.categorie === cat && d.annee === a && d.mois === mo);
                return found ? found.quantite : 0;
            }),
            borderColor: colors[i % colors.length],
            backgroundColor: colors[i % colors.length] + '33',
            fill: true,
            tension: 0.3,
        }))
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
