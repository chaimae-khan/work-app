/**
 * FICHIER JavaScript (compte.js)
 * ---------------------------------
 * À remplacer entièrement
 */

$(document).ready(function() {
    // Fonctionnalité pour montrer/cacher le mot de passe
    $('.toggle-password').on('click', function() {
        const passwordField = $($(this).data('toggle'));
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        
        // Changer l'icône
        if (type === 'text') {
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Ouvrir le modal d'édition du profil
    $('#BtnEditProfile').on('click', function(e) {
        e.preventDefault();
        
        // Préremplit les champs avec les informations actuelles
        $('#name').val($('#user-name').text().trim());
        $('#email').val($('#user-email').text().trim());
        
        // Afficher toutes les sections
        $('.profile-info-section').show();
        $('.password-only-section').hide();
        
        // Réinitialiser les champs de mot de passe
        $('#current_password, #password, #password_confirmation').val('');
        
        // Changer le titre du modal
        $('#ModalEditProfileLabel').text('Modifier mon profil');
        
        // Afficher le modal
        $('#ModalEditProfile').modal('show');
    });
    
    // Ouvrir le modal uniquement pour changer le mot de passe
    $('#BtnChangePassword').on('click', function(e) {
        e.preventDefault();
        
        // Masquer la section d'informations de profil
        $('.profile-info-section').hide();
        $('.password-only-section').show();
        
        // Réinitialiser les champs de mot de passe
        $('#current_password, #password, #password_confirmation').val('');
        
        // Préserver les valeurs actuelles pour le nom et email (ils seront envoyés mais pas modifiés)
        $('#name').val($('#user-name').text().trim());
        $('#email').val($('#user-email').text().trim());
        
        // Changer le titre du modal
        $('#ModalEditProfileLabel').text('Changer mon mot de passe');
        
        // Afficher le modal
        $('#ModalEditProfile').modal('show');
        
        // Focus sur le champ de mot de passe actuel
        setTimeout(function() {
            $('#current_password').focus();
        }, 500);
    });
    
    // Validation du formulaire de mise à jour du profil
    $('#BtnUpdateProfile').on('click', function(e) {
        e.preventDefault();
        
        // Si le champ mot de passe est rempli, vérifie d'abord le mot de passe actuel
        if ($('#password').val() !== '') {
            // Vérifie que current_password est rempli
            if ($('#current_password').val() === '') {
                $('.validationEditProfile').html('<li>Veuillez saisir votre mot de passe actuel pour confirmer les modifications</li>');
                $('.validationEditProfile').addClass('alert alert-danger');
                
                setTimeout(() => {
                    $('.validationEditProfile').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 5000);
                
                return;
            }
            
            // Vérifie que les deux mots de passe correspondent
            if ($('#password').val() !== $('#password_confirmation').val()) {
                $('.validationEditProfile').html('<li>Les mots de passe ne correspondent pas</li>');
                $('.validationEditProfile').addClass('alert alert-danger');
                
                setTimeout(() => {
                    $('.validationEditProfile').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 5000);
                
                return;
            }
            
            // Vérifie le mot de passe actuel d'abord
            $.ajax({
                type: "POST",
                url: VerifyPasswordUrl,
                data: {
                    _token: csrf_token,
                    current_password: $('#current_password').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        // Le mot de passe est correct, effectue la mise à jour du profil
                        updateProfile();
                    } else if (response.status == 422) {
                        $('.validationEditProfile').html('<li>' + response.message + '</li>');
                        $('.validationEditProfile').addClass('alert alert-danger');
                        
                        setTimeout(() => {
                            $('.validationEditProfile').fadeOut('slow', function() {
                                $(this).html("").removeClass('alert alert-danger').show();
                            });
                        }, 5000);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $('.validationEditProfile').html('');
                        $('.validationEditProfile').addClass('alert alert-danger');
                        $.each(xhr.responseJSON.errors, function(key, list_err) {
                            $('.validationEditProfile').append('<li>' + list_err + '</li>');
                        });
                        
                        setTimeout(() => {
                            $('.validationEditProfile').fadeOut('slow', function() {
                                $(this).html("").removeClass('alert alert-danger').show();
                            });
                        }, 5000);
                    } else {
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                    }
                }
            });
        } else {
            // Pas de changement de mot de passe, mise à jour directe du profil
            updateProfile();
        }
    });
    
    // Fonction de mise à jour du profil
    function updateProfile() {
        let formData = new FormData($('#FormUpdateProfile')[0]);
        formData.append('_token', csrf_token);
        
        $('#BtnUpdateProfile').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: UpdateProfileUrl,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('#BtnUpdateProfile').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditProfile').modal('hide');
                    
                    // Mise à jour des informations affichées sans rechargement
                    $('#user-name').text(formData.get('name'));
                    $('#user-email').text(formData.get('email'));
                    
                    // Réinitialiser le formulaire
                    $('#FormUpdateProfile')[0].reset();
                    
                    // Rafraîchir la page après 1.5 secondes
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else if (response.status == 400) {
                    $('.validationEditProfile').html("");
                    $('.validationEditProfile').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationEditProfile').append('<li>' + list_err + '</li>');
                    });
                    
                    setTimeout(() => {
                        $('.validationEditProfile').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function(xhr) {
                $('#BtnUpdateProfile').prop('disabled', false).text('Mettre à jour');
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $('.validationEditProfile').html('');
                    $('.validationEditProfile').addClass('alert alert-danger');
                    $.each(xhr.responseJSON.errors, function(key, list_err) {
                        $('.validationEditProfile').append('<li>' + list_err + '</li>');
                    });
                    
                    setTimeout(() => {
                        $('.validationEditProfile').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else {
                    new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                }
            }
        });
    }
});