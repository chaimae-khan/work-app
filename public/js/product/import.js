/**
 * Product Import JavaScript
 * 
 * This script handles the product import functionality 
 * in the products module.
 */

$(document).ready(function () {
    // Add Import button next to Add button
    if ($('.btn-primary[data-bs-toggle="modal"][data-bs-target="#ModalAddProduct"]').length) {
        const importBtn = $(`<button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#ModalImportProducts">
            <i class="fa-solid fa-file-import"></i> Importer des produits
        </button>`);
        
        $('.btn-primary[data-bs-toggle="modal"][data-bs-target="#ModalAddProduct"]')
            .after(importBtn);
    }

    // Initialize dropdowns for import form
    initializeImportDropdowns();

    // Download template button
    $('#btn-download-template').on('click', function(e) {
        e.preventDefault();
        window.location.href = '/download-template';
    });

    // Reset form and results when modal is hidden
    $('#ModalImportProducts').on('hidden.bs.modal', function() {
        resetImportForm();
    });

    // "New import" button after successful import
    $('#btn-new-import').on('click', function() {
        resetImportForm();
    });

    // Import Products Handler
    $('#BtnImportProducts').on('click', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!$('#FormImportProducts')[0].checkValidity()) {
            $('#FormImportProducts')[0].reportValidity();
            return;
        }
        
        let formData = new FormData($('#FormImportProducts')[0]);
        formData.append('_token', csrf_token);

        // Show loading state
        $('#BtnImportProducts').prop('disabled', true)
            .html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Importation...');
        $('.validationImport').hide().html('').removeClass('alert alert-danger');
        $('#import-results').hide();

        $.ajax({
            type: "POST",
            url: processImport_url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                // Reset button state
                $('#BtnImportProducts').prop('disabled', false)
                    .html('<i class="fa-solid fa-upload me-1"></i> Importer');
                
                if(response.status == 200) {
                    // Show success result
                    $('#import-success-message').text(response.success_count + ' produit(s) importé(s) avec succès.');
                    
                    // Show duplicates if any
                    if (response.duplicates && response.duplicates.length > 0) {
                        $('#import-duplicates').html('');
                        $.each(response.duplicates, function(i, duplicate) {
                            $('#import-duplicates').append(`<li>${duplicate}</li>`);
                        });
                        $('#import-duplicates-container').show();
                    } else {
                        $('#import-duplicates-container').hide();
                    }
                    
                    // Show errors if any
                    if (response.errors && response.errors.length > 0) {
                        $('#import-errors').html('');
                        $.each(response.errors, function(i, error) {
                            $('#import-errors').append(`<li>${error}</li>`);
                        });
                        $('#import-errors-container').show();
                    } else {
                        $('#import-errors-container').hide();
                    }
                    
                    // Show results
                    $('#import-results').show();
                    
                    // Hide form if import was successful
                    if (response.success_count > 0) {
                        $('#FormImportProducts').hide();
                        $('.modal-footer').hide();
                    }
                    
                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('.TableProducts')) {
                        $('.TableProducts').DataTable().ajax.reload();
                    }
                } else if(response.status == 400) {
                    // Display validation errors
                    $('.validationImport').html('').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationImport').append('<li>' + list_err + '</li>');
                    });
                    $('.validationImport').show();
                } else {
                    // Show general error
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function(xhr) {
                // Reset button state
                $('#BtnImportProducts').prop('disabled', false)
                    .html('<i class="fa-solid fa-upload me-1"></i> Importer');
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
                } else {
                    new AWN().alert("Une erreur est survenue lors de l'importation", { durations: { alert: 5000 } });
                }
            }
        });
    });
});

// Initialize Import Dropdowns
function initializeImportDropdowns() {
    // Category change - load subcategories
    $('#import_id_categorie').on('change', function() {
        loadSubcategories('#import_id_categorie', '#import_id_subcategorie');
    });
    
    // Local change - load rayons
    $('#import_id_local').on('change', function() {
        loadRayons('#import_id_local', '#import_id_rayon');
    });
}

// Reset Import Form
function resetImportForm() {
    // Reset form
    $('#FormImportProducts')[0].reset();
    $('#FormImportProducts').show();
    $('.modal-footer').show();
    
    // Clear dropdowns
    $('#import_id_subcategorie').empty().append('<option value="">Sélectionner une famille</option>');
    $('#import_id_rayon').empty().append('<option value="">Sélectionner un rayon</option>');
    
    // Hide results
    $('#import-results').hide();
    $('.validationImport').hide().html('').removeClass('alert alert-danger');
    
    // Reset button state
    $('#BtnImportProducts').prop('disabled', false)
        .html('<i class="fa-solid fa-upload me-1"></i> Importer');
}