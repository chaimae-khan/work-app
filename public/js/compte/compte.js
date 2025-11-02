$(document).ready(function() {
    let signaturePad = null;
    
    // Fonctionnalité pour montrer/cacher le mot de passe
    $('.toggle-password').on('click', function() {
        const passwordField = $($(this).data('toggle'));
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        
        if (type === 'text') {
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Ouvrir le modal d'édition du profil
    $('#BtnEditProfile').on('click', function(e) {
        e.preventDefault();
        
        // Get the full name and split it
        var fullName = $('#user-name').text().trim();
        var nameParts = fullName.split(' ');
        
        // Assume first part is prenom, rest is nom
        var prenom = nameParts[0] || '';
        var nom = nameParts.slice(1).join(' ') || '';
        
        $('#prenom').val(prenom);
        $('#nom').val(nom);
        $('#email').val($('#user-email').text().trim());
        
        $('.profile-info-section').show();
        $('.password-only-section').hide();
        
        $('#current_password, #password, #password_confirmation').val('');
        
        $('#ModalEditProfileLabel').text('Modifier mon profil');
        
        $('#ModalEditProfile').modal('show');
        
        // Initialize SignaturePad after modal is shown
        setTimeout(function() {
            const canvas = document.getElementById('signature-pad');
            if (canvas && !signaturePad) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
            }
        }, 300);
    });
    
    // Ouvrir le modal uniquement pour changer le mot de passe
    $('#BtnChangePassword').on('click', function(e) {
        e.preventDefault();
        
        $('.profile-info-section').hide();
        $('.password-only-section').show();
        
        $('#current_password, #password, #password_confirmation').val('');
        
        // Get the full name and split it
        var fullName = $('#user-name').text().trim();
        var nameParts = fullName.split(' ');
        
        // Assume first part is prenom, rest is nom
        var prenom = nameParts[0] || '';
        var nom = nameParts.slice(1).join(' ') || '';
        
        $('#prenom').val(prenom);
        $('#nom').val(nom);
        $('#email').val($('#user-email').text().trim());
        
        $('#ModalEditProfileLabel').text('Changer mon mot de passe');
        
        $('#ModalEditProfile').modal('show');
        
        setTimeout(function() {
            $('#current_password').focus();
        }, 500);
    });
    
    // Button to change existing signature
    $('#BtnChangeSignature').on('click', function() {
        $('#signature-display').hide();
        $('#signature-canvas-container').show();
        
        // Initialize SignaturePad if not already initialized
        const canvas = document.getElementById('signature-pad');
        if (canvas && !signaturePad) {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
        }
        
        // Clear the canvas for new signature
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    // Button to clear signature pad
    $('#BtnClearSignature').on('click', function() {
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    // Button to cancel signature editing (only if signature exists)
    $('#BtnCancelSignature').on('click', function() {
        $('#signature-canvas-container').hide();
        $('#signature-display').show();
        if (signaturePad) {
            signaturePad.clear();
        }
    });
    
    // Validation du formulaire de mise à jour du profil
    $('#BtnUpdateProfile').on('click', function(e) {
        e.preventDefault();
        
        // Check if signature is required and empty
        if (signaturePad && !signaturePad.isEmpty()) {
            // Signature exists, continue
        } else if (signaturePad && signaturePad.isEmpty() && !$('#signature-display').is(':visible')) {
            // No existing signature and canvas is empty
            $('.validationEditProfile').html('<li>Veuillez fournir une signature</li>');
            $('.validationEditProfile').addClass('alert alert-danger');
            
            setTimeout(() => {
                $('.validationEditProfile').fadeOut('slow', function() {
                    $(this).html("").removeClass('alert alert-danger').show();
                });
            }, 5000);
            
            return;
        }
            
        if ($('#password').val() !== '') {
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
            
            // Get signature data if canvas is not empty
            let dataUrl = null;
            if (signaturePad && !signaturePad.isEmpty()) {
                dataUrl = signaturePad.toDataURL();
            }
            
            $.ajax({
                type: "POST",
                url: VerifyPasswordUrl,
                data: {
                    _token: csrf_token,
                    current_password: $('#current_password').val(),
                    'image': dataUrl
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
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
            updateProfile();
        }
    });
    
    // Fonction de mise à jour du profil
    function updateProfile() {
        let formData = new FormData($('#FormUpdateProfile')[0]);
        formData.append('_token', csrf_token);
        
        // Add signature if canvas is not empty
        if (signaturePad && !signaturePad.isEmpty()) {
            const dataUrl = signaturePad.toDataURL();
            formData.append('image', dataUrl);
        }
        
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
                    
                    // Update the display with prenom and nom
                    var prenom = formData.get('prenom');
                    var nom = formData.get('nom');
                    $('#user-name').text(prenom + ' ' + nom);
                    $('#user-email').text(formData.get('email'));
                    
                    $('#FormUpdateProfile')[0].reset();
                    
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
    
    // Clean up signature pad when modal is closed
    $('#ModalEditProfile').on('hidden.bs.modal', function() {
        if (signaturePad) {
            signaturePad.clear();
            signaturePad = null;
        }
    });
});