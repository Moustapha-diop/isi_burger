<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ isset($burger)
                        ? route('gestionnaire.burgers.update', $burger)
                        : route('gestionnaire.burgers.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($burger)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom', $burger->nom ?? '') }}" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="prix" class="form-control @error('prix') is-invalid @enderror"
                                   value="{{ old('prix', $burger->prix ?? '') }}" min="0" step="1" required>
                            @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $burger->stock ?? 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                        <select name="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror" required>
                            <option value="">Choisir</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('categorie_id', $burger->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $burger->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Image</label>
                        @if(isset($burger) && $burger->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $burger->image) }}"
                                     height="80" class="rounded border" alt="Image actuelle">
                                <small class="text-muted ms-2">Image actuelle</small>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-isi">
                            <i class="bi bi-check2 me-1"></i>
                            {{ isset($burger) ? 'Enregistrer les modifications' : 'Ajouter le burger' }}
                        </button>
                        <a href="{{ route('gestionnaire.burgers.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
