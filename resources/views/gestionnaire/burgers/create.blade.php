@extends('layouts.app')
@section('title', 'Nouveau burger')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('gestionnaire.burgers.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Ajouter un burger</h4>
</div>

@include('gestionnaire.burgers._form')
@endsection
