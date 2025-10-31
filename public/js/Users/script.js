$(document).ready(function () {

    $(function () {
        if ($.fn.DataTable.isDataTable('.TableUsers')) {
            $('.TableUsers').DataTable().destroy();
        }
        initializeDataTable('.TableUsers', users);
        
        function initializeDataTable(selector, url) {
            var tableUsers = $(selector).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    dataSrc: function (json) {
                        if (json.data.length === 0) {
                            $('.paging_full_numbers').css('display', 'none');
                        }
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error: ' + error + ' ' + thrown);
                        console.log(xhr);
                    }
                },
                columns: [
                    { data: 'matricule', name: 'matricule' },
                    { data: 'prenom', name: 'prenom' }, 
                    { data: 'nom', name: 'nom' },      
                    { data: 'email', name: 'email' },
                    { data: 'telephone', name: 'telephone' },
                    { data: 'fonction', name: 'fonction' },
                    { data: 'roles', name: 'roles', searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    "sInfo": "",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    }
                }
            });
            
            $(selector + ' tbody').on('click', '.editUser', function(e) {
                e.preventDefault();
                console.log("Edit button clicked");
                
                // Reset form and remove any previous hidden ID field
                $('#FormUpdateUser')[0].reset();
                $('#FormUpdateUser input[name="id"]').remove();
                $('.validationUpdateUser').html("").removeClass('alert alert-danger');
                
                var IdUser = $(this).attr('data-id');
                console.log("User ID:", IdUser);
                
                if (!IdUser) {
                    console.error("No user ID found on button");
                    new AWN().alert("Erreur: ID utilisateur manquant", { durations: { alert: 5000 } });
                    return;
                }
                
                // Show modal after getting the ID
                $('#ModalEditUser').modal("show");
                
                // Make an AJAX call to get user details
                $.ajax({
                    type: "GET",
                    url: EditUser + "/" + IdUser,
                    dataType: "json",
                    beforeSend: function() {
                        console.log("Sending request to:", EditUser + "/" + IdUser);
                    },
                    success: function(response) {
                        console.log("Response received:", response);
                        
                        // Handle different response formats
                        var userData = response;
                        
                        // If response is in DataTables format
                        if (response.data && Array.isArray(response.data)) {
                            // Find the matching user in data array
                            userData = response.data.find(function(item) {
                                return item.id == IdUser;
                            });
                            
                            if (!userData) {
                                console.error("User not found in the response data array");
                                return;
                            }
                            
                            console.log("User data extracted from DataTables response:", userData);
                        }
                        
                        // Add hidden ID field to the form
                        $('#FormUpdateUser').append('<input type="hidden" name="id" value="' + IdUser + '">');
                        
                        // Fill form with the user data - Add null checks
                        $('#matricule').val(userData.matricule || '');
                        $('#nom').val(userData.nom || '');
                        $('#prenom').val(userData.prenom || '');
                        $('#email').val(userData.email || '');
                        $('#telephone').val(userData.telephone || '');
                        $('#fonction').val(userData.fonction || '');
                        
                        // Handle roles - check format and populate select
                        if (userData.roles) {
                            // Handle different roles formats
                            if (typeof userData.roles === 'string') {
                                // If roles is a comma-separated string
                                var firstRole = userData.roles.split(',')[0].trim();
                                $('#roles').val(firstRole);
                                console.log("Set role from string:", firstRole);
                            } else if (Array.isArray(userData.roles) && userData.roles.length > 0) {
                                $('#roles').val(userData.roles[0]);
                                console.log("Set role from array:", userData.roles[0]);
                            }
                        }
                        
                        console.log("Form fields after population:");
                        console.log("Matricule:", $('#matricule').val());
                        console.log("Nom:", $('#nom').val());
                        console.log("Prénom:", $('#prenom').val());
                        console.log("Email:", $('#email').val());
                        console.log("Téléphone:", $('#telephone').val());
                        console.log("Fonction:", $('#fonction').val());
                        console.log("Role:", $('#roles').val());
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching user data:", status, error);
                        console.error("Response:", xhr.responseText);
                        
                        try {
                            var errorResponse = JSON.parse(xhr.responseText);
                            console.error("Parsed error:", errorResponse);
                        } catch (e) {
                            console.error("Could not parse error response");
                        }
                        
                        new AWN().alert("Une erreur est survenue lors de la récupération des données.", { durations: { alert: 5000 } });
                    }
                });
            });
        
            $(selector + ' tbody').on('click', '.deleteuser', function(e) {
                e.preventDefault();
                var IdUser = $(this).attr('data-id');
                let notifier = new AWN();
        
                let onOk = () => {
                    $.ajax({
                        type: "post",
                        url: DeleteUser,
                        data: {
                            id: IdUser,
                            _token: csrf_token,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == 200) {
                                new AWN().success(response.message, { durations: { success: 5000 } });
                                $('.TableUsers').DataTable().ajax.reload();
                            } else if (response.status == 404) {
                                new AWN().warning(response.message, { durations: { warning: 5000 } });
                            }
                        },
                        error: function() {
                            new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                        }
                    });
                };
        
                let onCancel = () => {
                    notifier.info('Annulation de la suppression');
                };
        
                notifier.confirm(
                    'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: 'Supprimer',
                            cancel: 'Annuler'
                        }
                    }
                );
            });
        }
    });

    function phoneFormatter() {
        $('#telephone, #telephoneAdd').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, ''); // Remove all non-numeric characters
            $(this).val(number);
        });
    }
    
    $(phoneFormatter); 
     const canvas = document.getElementById('signature-pad');
const signaturePad = new SignaturePad(canvas);

$('#BtnADDUser').on('click', function(e) {
    e.preventDefault();

    // Create form data
    let formData = new FormData($('#FormAddUser')[0]);
    formData.append('_token', csrf_token);

    $('#BtnADDUser').prop('disabled', true).text('Enregistrement...');

    // Check if signature is empty
    if (signaturePad.isEmpty()) {
        alert('Please provide a signature first.');
        $('#BtnADDUser').prop('disabled', false).text('Sauvegarder');
        return;
    }

    // Get base64 signature image
    const dataUrl = signaturePad.toDataURL();
    formData.append('image', dataUrl); // ✅ append image correctly to FormData

    $.ajax({
        type: "POST",
        url: Adduser,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            $('#BtnADDUser').prop('disabled', false).text('Sauvegarder');

            if (response.status == 200) {
                new AWN().success(response.message, { durations: { success: 5000 } });
                $('#ModalAddUser').modal('hide');
                $('.TableUsers').DataTable().ajax.reload();
                $('#FormAddUser')[0].reset();
                signaturePad.clear(); // ✅ clear signature after save
            }  
            else if (response.status == 404) {
                new AWN().warning(response.message, { durations: { warning: 5000 } });
            }
            else if (response.status == 400) {
                $('.validationAddUser').html("")
                    .addClass('alert alert-danger');

                $.each(response.errors, function(key, list_err) {
                    $('.validationAddUser').append('<li>' + list_err + '</li>');
                });

                setTimeout(() => {
                    $('.validationAddUser').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 5000);
            }  
            else if (response.status == 500) {
                new AWN().alert(response.message, { durations: { alert: 5000 } });
            }
        },
        error: function(xhr, status, error) {
            $('#BtnADDUser').prop('disabled', false).text('Sauvegarder');
            console.error("Error adding user:", status, error);
            console.error("Response:", xhr.responseText);
            
            try {
                var errorResponse = JSON.parse(xhr.responseText);
                console.error("Parsed error:", errorResponse);
            } catch (e) {
                console.error("Could not parse error response");
            }
            
            new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
        }
    });
});


    $(document).on('click', '.toggle-password', function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        var input = $(this).parent().find('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    $('#BtnUpdateUser').on('click', function(e) {
        e.preventDefault();
        console.log("Update button clicked");
        
        // Log form data before submission
        console.log("Form ID value:", $('#FormUpdateUser input[name="id"]').val());
        console.log("Form data before submission:", {
            matricule: $('#matricule').val(),
            nom: $('#nom').val(),
            prenom: $('#prenom').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val(),
            fonction: $('#fonction').val(),
            roles: $('#roles').val()
        });
        
        let formData = new FormData($('#FormUpdateUser')[0]);
        formData.append('_token', csrf_token);
        
        // Double-check ID is in the form data
        var userId = $('#FormUpdateUser input[name="id"]').val();
        if (!userId) {
            console.error("ID missing from form data");
            new AWN().alert("Erreur: ID utilisateur manquant", { durations: { alert: 5000 } });
            return;
        }
        
        $('#BtnUpdateUser').prop('disabled', true).text('Mise à jour...');
    
        $.ajax({
            type: "POST",
            url: UpdateUser,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function() {
                console.log("Sending update request to:", UpdateUser);
                // Log FormData contents
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
            },
            success: function(response) {
                console.log("Update response:", response);
                
                $('#BtnUpdateUser').prop('disabled', false).text('Mettre à jour');
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditUser').modal('hide');
                    $('.TableUsers').DataTable().ajax.reload();
                    // Reset form after successful update
                    $('#FormUpdateUser')[0].reset();
                    $('#FormUpdateUser input[name="id"]').remove();
                }  
                else if (response.status == 404) {
                    new AWN().warning(response.message, {durations: {warning: 5000}});
                }
                else if (response.status == 400) {
                    $('.validationUpdateUser').html("");
                    $('.validationUpdateUser').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationUpdateUser').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.validationUpdateUser').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }  
                else if (response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function(xhr, status, error) {
                console.error("Update error:", status, error);
                console.error("Response:", xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    console.error("Parsed error:", errorResponse);
                } catch (e) {
                    console.error("Could not parse error response");
                }
                
                $('#BtnUpdateUser').prop('disabled', false).text('Mettre à jour');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });
    
    // Import Users
    $(document).on('click', '#BtnImportUsers', function(e) {
        e.preventDefault();
        
        let formData = new FormData($('#FormImportUsers')[0]);
        formData.append('_token', csrf_token);

        // Check if file is selected
        if ($('#import_file_users').val() == '') {
            new AWN().warning('Veuillez sélectionner un fichier.', {durations: {warning: 5000}});
            return;
        }

        $('#BtnImportUsers').prop('disabled', true).text('Importation...');

        $.ajax({
            type: "POST",
            url: ImportUsers,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                $('#BtnImportUsers').prop('disabled', false).text('Importer');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalImportUsers').modal('hide');
                    $('.TableUsers').DataTable().ajax.reload();
                    $('#FormImportUsers')[0].reset();
                } else if(response.status == 400) {
                    $('.validationImportUsers').html("");
                    $('.validationImportUsers').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationImportUsers').append('<li>' + list_err + '</li>');
                    });
                    
                    setTimeout(() => {
                        $('.validationImportUsers').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else if (response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function(xhr, status, error) {
                $('#BtnImportUsers').prop('disabled', false).text('Importer');
                console.error("Import error:", status, error);
                console.error("Response:", xhr.responseText);
                
                if (xhr.status === 403) {
                    new AWN().warning(xhr.responseJSON.message, {durations: {warning: 5000}});
                } else {
                    // Try to parse the error response
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse && errorResponse.message) {
                            new AWN().alert(errorResponse.message, { durations: { alert: 5000 } });
                        } else {
                            new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                        }
                    } catch (e) {
                        console.error("Could not parse error response:", e);
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                    }
                }
            }
        });
    });
});