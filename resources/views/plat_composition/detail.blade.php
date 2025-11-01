@extends('dashboard.index')

@section('dashboard')
<div class="content-page"> 
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Détail de la composition</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plat: {{ $plat->name }}</h5>
                            <p>Type: {{ $plat->type }}</p>
                            
                            <h6 class="mt-4">Composition:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Unité</th>
                                            <th>Nombre de couverts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Data_LignePlat as $ligne)
                                        <tr>
                                            <td>{{ $ligne->name }}</td>
                                            <td>{{ $ligne->qte }}</td>
                                            <td>{{ $ligne->unite_name }}</td>
                                            <td>{{ $ligne->nombre_couvert }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection