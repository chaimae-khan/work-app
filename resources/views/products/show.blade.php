@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Product Information
                </div>
                <div class="float-end">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->name }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Code Article:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->code_article }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Purchase Price:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->price_achat }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Selling Price:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->price_vente }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Category:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->category ? $product->category->name : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Subcategory:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->subcategory ? $product->subcategory->name : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Local:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->local ? $product->local->name : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Rayon:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->rayon ? $product->rayon->name : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Emplacement:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->emplacement }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>TVA:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->tva ? $product->tva->value.'%' : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Unite:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->unite ? $product->unite->name : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Quantity:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->stock ? $product->stock->quantite : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Threshold:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->stock ? $product->stock->seuil : 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Barcode:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->code_barre ?: 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Created At:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->created_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-end text-start"><strong>Updated At:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $product->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
    
@endsection