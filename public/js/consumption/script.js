$(document).ready(function() {

  $('#type_operation').on('change', function() {
    const isEntree = $(this).val() === 'entree';
    
    // Existing code for showing/hiding filters
    if (isEntree) {
        $('#type_commande_container').hide();
        $('#type_menu_container').hide();
        $('#type_commande').val('');
        $('#type_menu').val('');
        
        // Hide print and export buttons in the modal
        $('#btnPrint, #btnExportPDF').hide();
    } else {
        $('#type_commande_container').show();
        if ($('#type_commande').val() === 'Fournitures et matériels' || $('#type_commande').val() === 'Non Alimentaire') {
            $('#type_menu_container').hide();
        } else {
            $('#type_menu_container').show();
        }
        
        // Show print and export buttons in the modal
        $('#btnPrint, #btnExportPDF').show();
    }
});

    $('#type_commande').on('change', function() {
        if ($('#type_operation').val() === 'sortie') {
            if ($(this).val() === 'Fournitures et matériels' || $(this).val() === 'Non Alimentaire') {
                $('#type_menu_container').hide();
                $('#type_menu').val('');
            } else {
                $('#type_menu_container').show();
            }
        }
    });

    // Initialize datepicker to current date
    const today = new Date().toISOString().split('T')[0];
    $('#consumption_date').val(today);

$('#btnSearch').on('click', function(e) {
    e.preventDefault();
    
    const date = $('#consumption_date').val();
    const typeOperation = $('#type_operation').val();
    const typeCommande = $('#type_commande').val();
    const typeMenu = $('#type_menu').val();
    
    if (!date) {
        new AWN().alert('Veuillez sélectionner une date');
        return;
    }
    
    console.log('Sending request with:', {
        date: date,
        type_operation: typeOperation,
        type_commande: typeCommande,
        type_menu: typeMenu
    });
    
    // Before sending the AJAX request, set the visibility of buttons
    const isEntree = typeOperation === 'entree';
    if (isEntree) {
        $('#btnPrint, #btnExportPDF').hide();
    } else {
        $('#btnPrint, #btnExportPDF').show();
    }
    
    $.ajax({
        type: "GET",
        url: getConsumptionData_url,
        data: {
            date: date,
            type_operation: typeOperation,
            type_commande: typeCommande,
            type_menu: typeMenu
        },
        dataType: "json",
        beforeSend: function() {
            $('#btnSearch').prop('disabled', true);
            $('#btnSearch').html('<i class="fa fa-spinner fa-spin"></i> Chargement...');
        },
        success: function(response) {
            console.log('Full response:', response);
            console.log('Response status:', response.status);
            
            if (response.status == 200) {
                console.log('Data received:', response.data);
                console.log('Data structure:', JSON.stringify(response.data, null, 2));
                
                displayConsumptionData(response.data);
                $('#ModalConsumption').modal('show');
            } else {
                console.log('Error response:', response);
                new AWN().warning(response.message || 'Aucune donnée trouvée');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            console.error("XHR:", xhr);
            console.error("Status:", status);
            console.error("Response text:", xhr.responseText);
            new AWN().alert("Erreur lors de la récupération des données");
        },
        complete: function() {
            $('#btnSearch').prop('disabled', false);
            $('#btnSearch').html('<i class="fa fa-search"></i> Rechercher');
        }
    });
});
    
    // PDF Export functionality
    $('#btnExportPDF').on('click', function(e) {
        e.preventDefault();
        
        const date = $('#consumption_date').val();
        const typeOperation = $('#type_operation').val();
        const typeCommande = $('#type_commande').val();
        const typeMenu = $('#type_menu').val();
        
        if (!date) {
            new AWN().alert('Veuillez sélectionner une date');
            return;
        }
        
        // Create a form and submit it for PDF download
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = exportPDF_url;
        
        // Create hidden fields
        const fields = {
            date: date,
            type_operation: typeOperation,
            type_commande: typeCommande,
            type_menu: typeMenu
        };
        
        for (const [key, value] of Object.entries(fields)) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = key;
            hidden.value = value;
            form.appendChild(hidden);
        }
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
function displayConsumptionData(data) {
    let html = '';
    
    if (data.type_operation === 'entree') {
        // For entree (achat)
        html = `
            <h4 class="text-center">ENTRÉES</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Désignation des Articles</th>
                        <th>Qté</th>
                        <th>P.U</th>
                        <th>P.T</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        // Group products by category
        let productsByCategory = {};
        
        data.consumptions.forEach((achat) => {
            achat.products.forEach((product) => {
                const categoryName = product.category_name || 'Non catégorisé';
                
                if (!productsByCategory[categoryName]) {
                    productsByCategory[categoryName] = [];
                }
                
                productsByCategory[categoryName].push(product);
            });
        });
        
        // Sort categories and display products
        const sortedCategories = Object.keys(productsByCategory).sort();
        
        sortedCategories.forEach(category => {
            // Add category header
            html += `
                <tr class="category-header">
                    <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                </tr>
            `;
            
            // Sort products by name within category
            const productsInCategory = productsByCategory[category].sort((a, b) => 
                a.name.localeCompare(b.name)
            );
            
            // Add products in this category
            productsInCategory.forEach((product, index) => {
                html += `
                    <tr>
                        <td>${index + 1}- ${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                        <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                    </tr>
                `;
            });
        });
        
        html += `
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
    } else {
        // For sortie (menu)
        if (data.type_commande === 'Fournitures et matériels' || data.type_commande === 'Non Alimentaire') {
            // Display Fournitures et matériels or Non Alimentaire without menu structure
            html = `
                <h4 class="text-center">${data.type_commande}</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Désignation des Articles</th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Get the first consumption item (should only be one for Fournitures or Non Alimentaire)
            if (data.consumptions && data.consumptions.length > 0) {
                const firstConsumption = data.consumptions[0];
                
                // Group products by category
                let productsByCategory = {};
                
                firstConsumption.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
                
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
            }
            
            html += `
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                        </tr>
                    </tbody>
                </table>
            `;
            
            // Price summary for Fournitures et matériels or Non Alimentaire
            html += `
                <div class="mt-4">
                    <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                </div>
            `;
        } else {
            // Regular menu logic
            data.consumptions.forEach((menu) => {
                html += `
                    <h4 class="text-center">${menu.type_menu}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Désignation des Articles</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                // Group products by category
                let productsByCategory = {};
                
                menu.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
                
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
                
                html += `
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                <td><strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                `;
            });
            
            // Add effectif section for sortie only (excluding Fournitures et matériels and Non Alimentaire)
            if (data.grand_totals && data.grand_totals.total_people > 0) {
                // Get all unique menu types to create the subcolumns
                let menuTypes = [];
                data.consumptions.forEach(menu => {
                    const menuType = menu.type_menu || 'Sans Menu';
                    if (!menuTypes.includes(menuType)) {
                        menuTypes.push(menuType);
                    }
                });
                
                html += `
                    <h4 class="text-center mt-4">EFFECTIF</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Petit déjeuner</th>
                                <th colspan="${menuTypes.length}">Lunch</th>
                                <th>Dîner</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Élèves</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.eleves || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Personnel</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.personnel || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Invités</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.invites || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Divers</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.divers || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                `;
                
                // Show Prix de Revient for each menu
                data.consumptions.forEach((menu) => {
                    html += `
                        <p>Prix de Revient de la journée ${menu.type_menu} : <strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></p>
                    `;
                    
                    // Only show Prix Moyen if total_people is greater than 0
                    if (menu.total_people > 0) {
                        html += `
                            <p>Prix Moyen ${menu.type_menu} : <strong>${(menu.total_cost / menu.total_people).toFixed(2)}</strong></p>
                        `;
                    }
                });
                
                // Calculate overall average of menu prices (prix moyen général)
                let totalAverages = 0;
                let countMenus = 0;
                data.consumptions.forEach((menu) => {
                    if (menu.total_people > 0) {
                        totalAverages += menu.total_cost / menu.total_people;
                        countMenus++;
                    }
                });
                
                if (countMenus > 0) {
                    html += `
                        <p>Prix Moyen Général : <strong>${(totalAverages / countMenus).toFixed(2)}</strong></p>
                    `;
                }
                
                html += `</div>`;
            } else {
                html += `
                    <div class="mt-4">
                        <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                    </div>
                `;
            }
        }
    }
    
    $('#consumptionTableData').html(html);
    
    // Add some CSS for better styling
    $('<style>')
        .text(`
            .category-header {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .table-totals {
                background-color: #e9ecef;
                font-weight: bold;
            }
            .table thead th {
                vertical-align: middle;
                text-align: center;
            }
            .table-bordered td:nth-child(n+2) {
                text-align: right;
            }
            .table-bordered td:first-child {
                text-align: left;
            }
        `)
        .appendTo('head');
}
// Add this function right after the displayConsumptionData function in your existing JavaScript

function displayMenuAttributes(menu) {
    let menuAttributesHtml = '';
    
    // Check if any menu attributes exist
    if (menu.entree || menu.plat_principal || menu.accompagnement || menu.dessert) {
        menuAttributesHtml = `
            <div class="menu-attributes mb-3" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <div class="row">
        `;
        
        if (menu.entree) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Entrée:</strong><br>
                    <span>${menu.entree}</span>
                </div>
            `;
        }
        
        if (menu.plat_principal) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Plat Principal:</strong><br>
                    <span>${menu.plat_principal}</span>
                </div>
            `;
        }
        
        if (menu.accompagnement) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Accompagnement:</strong><br>
                    <span>${menu.accompagnement}</span>
                </div>
            `;
        }
        
        if (menu.dessert) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Dessert:</strong><br>
                    <span>${menu.dessert}</span>
                </div>
            `;
        }
        
        menuAttributesHtml += `
                </div>
            </div>
        `;
    }
    
    return menuAttributesHtml;
}

// Update the regular menu logic section in your displayConsumptionData function
// Replace the existing regular menu logic with this updated version:

// Regular menu logic - UPDATED VERSION
data.consumptions.forEach((menu) => {
    // Format menu name for display
    const displayMenuName = window.formatMenuName(menu.type_menu);
    
    html += `
        <h4 class="text-center">${displayMenuName}</h4>
        ${displayMenuAttributes(menu)}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Désignation des Articles</th>
                    <th>Qté</th>
                    <th>P.U</th>
                    <th>P.T</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    // Group products by category
    let productsByCategory = {};
    
    menu.products.forEach((product) => {
        const categoryName = product.category_name || 'Non catégorisé';
        
        if (!productsByCategory[categoryName]) {
            productsByCategory[categoryName] = [];
        }
        
        productsByCategory[categoryName].push(product);
    });
    
    // Sort categories and display products
    const sortedCategories = Object.keys(productsByCategory).sort();
    
    sortedCategories.forEach(category => {
        // Add category header
        html += `
            <tr class="category-header">
                <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
            </tr>
        `;
        
        // Sort products by name within category
        const productsInCategory = productsByCategory[category].sort((a, b) => 
            a.name.localeCompare(b.name)
        );
        
        // Add products in this category
        productsInCategory.forEach((product, index) => {
            html += `
                <tr>
                    <td>${index + 1}- ${product.name}</td>
                    <td>${product.quantity}</td>
                    <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                    <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                </tr>
            `;
        });
    });
    
    html += `
                <tr>
                    <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                    <td><strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></td>
                </tr>
            </tbody>
        </table>
    `;
});
function displayConsumptionData(data) {
    let html = '';
    
    if (data.type_operation === 'entree') {
        // For entree (achat)
        html = `
            <h4 class="text-center">ENTRÉES</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Désignation des Articles</th>
                        <th>Qté</th>
                        <th>P.U</th>
                        <th>P.T</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        // Group products by category
        let productsByCategory = {};
        
        data.consumptions.forEach((achat) => {
            achat.products.forEach((product) => {
                const categoryName = product.category_name || 'Non catégorisé';
                
                if (!productsByCategory[categoryName]) {
                    productsByCategory[categoryName] = [];
                }
                
                productsByCategory[categoryName].push(product);
            });
        });
        
        // Sort categories and display products
        const sortedCategories = Object.keys(productsByCategory).sort();
        
        sortedCategories.forEach(category => {
            // Add category header
            html += `
                <tr class="category-header">
                    <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                </tr>
            `;
            
            // Sort products by name within category
            const productsInCategory = productsByCategory[category].sort((a, b) => 
                a.name.localeCompare(b.name)
            );
            
            // Add products in this category
            productsInCategory.forEach((product, index) => {
                html += `
                    <tr>
                        <td>${index + 1}- ${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                        <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                    </tr>
                `;
            });
        });
        
        html += `
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
    } else {
        // For sortie (menu)
        if (data.type_commande === 'Fournitures et matériels' || data.type_commande === 'Non Alimentaire') {
            // Display Fournitures et matériels or Non Alimentaire without menu structure
            html = `
                <h4 class="text-center">${data.type_commande}</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Désignation des Articles</th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Get the first consumption item (should only be one for Fournitures or Non Alimentaire)
            if (data.consumptions && data.consumptions.length > 0) {
                const firstConsumption = data.consumptions[0];
                
                // Group products by category
                let productsByCategory = {};
                
                firstConsumption.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
                
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
            }
            
            html += `
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                        </tr>
                    </tbody>
                </table>
            `;
            
            // Price summary for Fournitures et matériels or Non Alimentaire
            html += `
                <div class="mt-4">
                    <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                </div>
            `;
        } else {
            // Regular menu logic
            data.consumptions.forEach((menu) => {
                html += `
                    <h4 class="text-center">${menu.type_menu}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Désignation des Articles</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                // Group products by category
                let productsByCategory = {};
                
                menu.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
                
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
                
                html += `
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                <td><strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                `;
            });
            
            // Add effectif section for sortie only (excluding Fournitures et matériels and Non Alimentaire)
            if (data.grand_totals && data.grand_totals.total_people > 0) {
                html += `
                    <h4 class="text-center mt-4">EFFECTIF</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Petit déjeuner</th>
                                <th>Lunch</th>
                                <th>Dîner</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Élèves</td>
                                <td></td>
                                <td>${data.consumptions.reduce((sum, menu) => sum + (menu.eleves || 0), 0)}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Personnel</td>
                                <td></td>
                                <td>${data.consumptions.reduce((sum, menu) => sum + (menu.personnel || 0), 0)}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Invités</td>
                                <td></td>
                                <td>${data.consumptions.reduce((sum, menu) => sum + (menu.invites || 0), 0)}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Divers</td>
                                <td></td>
                                <td>${data.consumptions.reduce((sum, menu) => sum + (menu.divers || 0), 0)}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                `;
                
                // Show Prix de Revient for each menu
                data.consumptions.forEach((menu) => {
                    html += `
                        <p>Prix de Revient de la journée ${menu.type_menu} : <strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></p>
                    `;
                    
                    // Only show Prix Moyen if total_people is greater than 0
                    if (menu.total_people > 0) {
                        html += `
                            <p>Prix Moyen ${menu.type_menu} : <strong>${(menu.total_cost / menu.total_people).toFixed(2)}</strong></p>
                        `;
                    }
                });
                
                // Calculate overall average of menu prices (prix moyen général)
                let totalAverages = 0;
                let countMenus = 0;
                data.consumptions.forEach((menu) => {
                    if (menu.total_people > 0) {
                        totalAverages += menu.total_cost / menu.total_people;
                        countMenus++;
                    }
                });
                
                if (countMenus > 0) {
                    html += `
                        <p>Prix Moyen Général : <strong>${(totalAverages / countMenus).toFixed(2)}</strong></p>
                    `;
                }
                
                html += `</div>`;
            } else {
                html += `
                    <div class="mt-4">
                        <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                    </div>
                `;
            }
        }
    }
    
    $('#consumptionTableData').html(html);
    
    // Add some CSS for better styling
    $('<style>')
        .text(`
            .category-header {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .table-totals {
                background-color: #e9ecef;
                font-weight: bold;
            }
        `)
        .appendTo('head');
}
});