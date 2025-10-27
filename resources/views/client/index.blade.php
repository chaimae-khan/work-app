@extends('dashboard.index')

@section('dashboard')
<script>
    var csrf_token        = "{{ csrf_token() }}";
    var AddClient         = "{{ url('addClient') }}";
    var clients           = "{{ url('client') }}";
    var EditClient        = "{{ url('editClient') }}";
    var UpdateClient      = "{{ url('updateClient') }}";
    var DeleteClient      = "{{ url('DeleteClient') }}";
    var GetFonctions      = "{{ url('client/fonctions') }}";
</script>
<script src="{{ asset('js/client/script.js') }}"></script>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des demandeurs</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Demandeur</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                @can('Formateurs-ajoute')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAddClient">
                                    Ajouter Demandeur 
                                </button>
                                @endcan
                            </div>

                            <div class="table-responsive">
                                <table class="table datatable-table TableClients">
                                    <thead>
                                        <tr>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Téléphone</th>
                                            <th>Email</th>
                                            <th>Matricule</th>
                                            <th>Fonction</th>
                                            <th>Créé par</th>
                                            <th>Date Création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- DataTables will populate this -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('Formateurs-ajoute')
    <!-- Add Client Modal -->
    <div class="modal fade" id="ModalAddClient" tabindex="-1" aria-labelledby="ModalAddClientLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddClientLabel">Ajouter un Demandeur </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="FormAddClient" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="text" name="Telephone" id="phone_client" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="Email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" name="Matricule" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Fonction</label>
                                    <select name="Fonction" id="Fonction" class="form-control">
                                        <option value="">Sélectionner une fonction</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="validationAddClient text-danger"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnAddClient">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    @can('Formateurs-modifier')
    <!-- Edit Client Modal -->
    <div class="modal fade" id="ModalEditClient" tabindex="-1" aria-labelledby="ModalEditClientLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditClientLabel">Modifier Demandeur </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="FormUpdateClient" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="text" id="Telephone" name="Telephone" class="form-control phone_client_edit" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" id="Email" name="Email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" id="Matricule" name="Matricule" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Fonction</label>
                                    <select id="EditFonction" name="Fonction" class="form-control">
                                        <option value="">Sélectionner une fonction</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="validationEditClient text-danger"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnUpdateClient">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection