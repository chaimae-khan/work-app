@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Product
                </div>
                <div class="float-end">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="code_article" class="col-md-4 col-form-label text-md-end text-start">Code Article</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" id="code_article" value="{{ $product->code_article }}" disabled>
                          <small class="text-muted">Format: category (3) + family (3) + sequential number</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="price_achat" class="col-md-4 col-form-label text-md-end text-start">Purchase Price</label>
                        <div class="col-md-6">
                          <input type="number" step="0.01" class="form-control @error('price_achat') is-invalid @enderror" id="price_achat" name="price_achat" value="{{ old('price_achat', $product->price_achat) }}">
                            @error('price_achat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="price_vente" class="col-md-4 col-form-label text-md-end text-start">Selling Price</label>
                        <div class="col-md-6">
                          <input type="number" step="0.01" class="form-control @error('price_vente') is-invalid @enderror" id="price_vente" name="price_vente" value="{{ old('price_vente', $product->price_vente) }}">
                            @error('price_vente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id_categorie" class="col-md-4 col-form-label text-md-end text-start">Category</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_categorie') is-invalid @enderror" id="id_categorie" name="id_categorie">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('id_categorie', $product->id_categorie) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                          </select>
                            @error('id_categorie')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id_subcategorie" class="col-md-4 col-form-label text-md-end text-start">Subcategory</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_subcategorie') is-invalid @enderror" id="id_subcategorie" name="id_subcategorie">
                            <option value="">Select a subcategory</option>
                            <!-- Will be populated dynamically via JS -->
                          </select>
                            @error('id_subcategorie')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id_local" class="col-md-4 col-form-label text-md-end text-start">Local</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_local') is-invalid @enderror" id="id_local" name="id_local">
                            <option value="">Select a local</option>
                            @foreach($locals as $local)
                                <option value="{{ $local->id }}" {{ (old('id_local', $product->id_local) == $local->id) ? 'selected' : '' }}>{{ $local->name }}</option>
                            @endforeach
                          </select>
                            @error('id_local')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id_rayon" class="col-md-4 col-form-label text-md-end text-start">Rayon</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_rayon') is-invalid @enderror" id="id_rayon" name="id_rayon">
                            <option value="">Select a rayon</option>
                            <!-- Will be populated dynamically via JS -->
                          </select>
                            @error('id_rayon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- TVA field -->
                    <div class="mb-3 row">
                        <label for="id_tva" class="col-md-4 col-form-label text-md-end text-start">TVA</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_tva') is-invalid @enderror" id="id_tva" name="id_tva">
                            <option value="">Select TVA</option>
                            @foreach($tvas as $tva)
                                <option value="{{ $tva->id }}" {{ (old('id_tva', $product->id_tva) == $tva->id) ? 'selected' : '' }}>{{ $tva->value }}%</option>
                            @endforeach
                          </select>
                            @error('id_tva')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Unite field -->
                    <div class="mb-3 row">
                        <label for="id_unite" class="col-md-4 col-form-label text-md-end text-start">Unite</label>
                        <div class="col-md-6">
                          <select class="form-control @error('id_unite') is-invalid @enderror" id="id_unite" name="id_unite">
                            <option value="">Select Unit</option>
                            @foreach($unites as $unite)
                                <option value="{{ $unite->id }}" {{ (old('id_unite', $product->id_unite) == $unite->id) ? 'selected' : '' }}>{{ $unite->name }}</option>
                            @endforeach
                          </select>
                            @error('id_unite')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="quantite" class="col-md-4 col-form-label text-md-end text-start">Quantity</label>
                        <div class="col-md-6">
                          <input type="number" step="0.01" class="form-control @error('quantite') is-invalid @enderror" id="quantite" name="quantite" value="{{ old('quantite', $product->stock ? $product->stock->quantite : '') }}">
                            @error('quantite')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="seuil" class="col-md-4 col-form-label text-md-end text-start">Threshold</label>
                        <div class="col-md-6">
                          <input type="number" step="0.01" class="form-control @error('seuil') is-invalid @enderror" id="seuil" name="seuil" value="{{ old('seuil', $product->stock ? $product->stock->seuil : '') }}">
                            @error('seuil')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="code_barre" class="col-md-4 col-form-label text-md-end text-start">Barcode</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('code_barre') is-invalid @enderror" id="code_barre" name="code_barre" value="{{ old('code_barre', $product->code_barre) }}">
                            @error('code_barre')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to load subcategories
        function loadSubcategories(categoryId, selectedSubcategoryId = null) {
            var subcategorySelect = document.getElementById('id_subcategorie');
            
            // Reset subcategory dropdown
            subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
            
            if (!categoryId) {
                return;
            }

            fetch('{{ url("getSubcategories") }}/' + categoryId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200 && data.subcategories.length > 0) {
                        data.subcategories.forEach(function(subcategory) {
                            var option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            
                            if (selectedSubcategoryId && subcategory.id == selectedSubcategoryId) {
                                option.selected = true;
                            }
                            
                            subcategorySelect.appendChild(option);
                        });
                    } else {
                        console.warn('No subcategories found');
                    }
                })
                .catch(error => {
                    console.error("Error loading subcategories:", error);
                });
        }

        // Function to load rayons
        function loadRayons(localId, selectedRayonId = null) {
            var rayonSelect = document.getElementById('id_rayon');
            
            // Reset rayon dropdown
            rayonSelect.innerHTML = '<option value="">Select a rayon</option>';
            
            if (!localId) {
                return;
            }

            fetch('{{ url("getRayons") }}/' + localId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200 && data.rayons.length > 0) {
                        data.rayons.forEach(function(rayon) {
                            var option = document.createElement('option');
                            option.value = rayon.id;
                            option.textContent = rayon.name;
                            
                            if (selectedRayonId && rayon.id == selectedRayonId) {
                                option.selected = true;
                            }
                            
                            rayonSelect.appendChild(option);
                        });
                    } else {
                        console.warn('No rayons found');
                    }
                })
                .catch(error => {
                    console.error("Error loading rayons:", error);
                });
        }

        // Category change - load subcategories
        document.getElementById('id_categorie').addEventListener('change', function() {
            loadSubcategories(this.value);
        });
        
        // Local change - load rayons
        document.getElementById('id_local').addEventListener('change', function() {
            loadRayons(this.value);
        });

        // Initial population with pre-selected values
        var initialCategoryId = document.getElementById('id_categorie').value;
        var initialSubcategoryId = '{{ old('id_subcategorie', $product->id_subcategorie) }}';
        var initialLocalId = document.getElementById('id_local').value;
        var initialRayonId = '{{ old('id_rayon', $product->id_rayon) }}';
        
        if (initialCategoryId) {
            loadSubcategories(initialCategoryId, initialSubcategoryId);
        }
        
        if (initialLocalId) {
            loadRayons(initialLocalId, initialRayonId);
        }
    });
</script>
    
@endsection