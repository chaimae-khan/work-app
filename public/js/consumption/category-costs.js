/**
 * Category Costs JS - Optimized for Daily Food Category Reports
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepicker to current date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('consumption_date').value = today;
    
    // Toggle visibility of type_menu based on type_operation
    const typeOperationSelect = document.getElementById('type_operation');
    const typeMenuContainer = document.getElementById('type_menu_container');
    const typeCommandeContainer = document.getElementById('type_commande_container');
    
    function toggleMenuVisibility() {
        if (typeOperationSelect.value === 'entree') {
            typeMenuContainer.style.display = 'none';
            typeCommandeContainer.style.display = 'none';
        } else {
            typeMenuContainer.style.display = 'block';
            typeCommandeContainer.style.display = 'block';
            
            // Check if type_commande is set to a non-menu value
            const typeCommandeSelect = document.getElementById('type_commande');
            if (typeCommandeSelect.value === 'Fournitures et matériels' || typeCommandeSelect.value === 'Non Alimentaire') {
                typeMenuContainer.style.display = 'none';
            } else {
                typeMenuContainer.style.display = 'block';
            }
        }
    }
    
    // Initial visibility
    toggleMenuVisibility();
    
    // Add change event listener
    typeOperationSelect.addEventListener('change', toggleMenuVisibility);
    
    // Type commande change handler
    document.getElementById('type_commande').addEventListener('change', function() {
        if (typeOperationSelect.value === 'sortie') {
            if (this.value === 'Fournitures et matériels' || this.value === 'Non Alimentaire') {
                typeMenuContainer.style.display = 'none';
                document.getElementById('type_menu').value = '';
            } else {
                typeMenuContainer.style.display = 'block';
            }
        }
    });
    
    // Search button click handler
    document.getElementById('btnSearch').addEventListener('click', function() {
        const date = document.getElementById('consumption_date').value;
        const typeOperation = document.getElementById('type_operation').value;
        const typeCommande = document.getElementById('type_commande').value;
        const typeMenu = document.getElementById('type_menu').value;
        
        if (!date) {
            alert('Veuillez sélectionner une date');
            return;
        }
        
        // Show loading animation
        this.disabled = true;
        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Chargement...';
        
        // Prepare request data
        const requestData = {
            date: date,
            type_operation: typeOperation,
            type_commande: typeCommande !== 'all' ? typeCommande : '',
            type_menu: typeMenu,
            filter_category: 'DENREES ALIMENTAIRES'
        };
        
        // Make AJAX request
        fetch(getConsumptionWithCategoryCosts_url + '?' + new URLSearchParams(requestData))
            .then(response => response.json())
            .then(response => {
                // Reset button state
                this.disabled = false;
                this.innerHTML = '<i class="fa fa-search"></i> Rechercher';
                
                if (response.status === 200) {
                    displayCategoryCostsData(response.data);
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('ModalCategoryCosts'));
                    modal.show();
                } else {
                    alert(response.message || 'Aucune donnée trouvée');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Erreur lors de la récupération des données");
                
                // Reset button state
                this.disabled = false;
                this.innerHTML = '<i class="fa fa-search"></i> Rechercher';
            });
    });
    
    // Print button handler
    document.getElementById('btnPrint').addEventListener('click', function() {
        window.print();
    });
    
    // PDF Export button handler
    document.getElementById('btnExportPDF').addEventListener('click', function() {
        const date = document.getElementById('consumption_date').value;
        const typeOperation = document.getElementById('type_operation').value;
        const typeCommande = document.getElementById('type_commande').value;
        const typeMenu = document.getElementById('type_menu').value;
        
        if (!date) {
            alert('Veuillez sélectionner une date');
            return;
        }
        
        // Create form for PDF export
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = exportCategoryCostsPDF_url;
        
        // Create hidden fields
        const fields = {
            date: date,
            type_operation: typeOperation,
            type_commande: typeCommande !== 'all' ? typeCommande : '',
            type_menu: typeMenu,
            filter_category: 'DENREES ALIMENTAIRES'
        };
        
        for (const [key, value] of Object.entries(fields)) {
            if (value !== undefined && value !== null) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = key;
                hidden.value = value;
                form.appendChild(hidden);
            }
        }
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
});

// Function to display category costs data
function displayCategoryCostsData(data) {
    // Parse and format the date
    const dateParts = data.date.split('/');
    const date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
    const formattedDate = date.toLocaleDateString('fr-FR', { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    });
    
    let html = '';
    
    // Date and title
    html += `
        <div class="text-center mb-4">
            <h4>CONSOMMATION PAR CATÉGORIE ALIMENTAIRE</h4>
            <h5>${formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1)}</h5>
        </div>
    `;
    
    // If we have menu data that includes "Menu eleves"
    let menuEleves = null;
    
    if (data.menus && data.menus.length > 0) {
        // Find the "Menu eleves" if it exists
        menuEleves = data.menus.find(menu => 
            menu.type_menu && menu.type_menu.toLowerCase().includes('eleve')
        );
    }
    
    // Create main reporting table similar to the image
    html += `
        <h5 class="mt-4">Coûts par catégorie alimentaire</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Journée du</th>
                    <th>Prix Moyen Menu Elèves</th>
    `;
    
    // Get all categories from the global_category_costs
    const categories = [];
    if (data.global_category_costs && data.global_category_costs.length > 0) {
        data.global_category_costs.forEach(category => {
            categories.push({
                id: category.id,
                name: category.name,
                cost: category.total_cost
            });
        });
    }
    
    // Sort categories by name
    categories.sort((a, b) => a.name.localeCompare(b.name));
    
    // Add category headers
    categories.forEach(category => {
        html += `<th>${category.name}</th>`;
    });
    
    // Finish the header row
    html += `
                    <th>Prix de Revient de la Journée</th>
                    <th>Effectif</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    // Add the data row
    html += `
                <tr>
                    <td>${formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1)}</td>
                    <td>${menuEleves && menuEleves.total_people > 0 ? 
                        (menuEleves.total_cost / menuEleves.total_people).toFixed(2) : 
                        '-'}</td>
    `;
    
    // Add category costs
    categories.forEach(category => {
        html += `<td>${category.cost.toFixed(2)}</td>`;
    });
    
    // Add total cost and effectif
    html += `
                    <td>${data.grand_totals.total_cost.toFixed(2)}</td>
                    <td>${data.grand_totals.total_people}</td>
                </tr>
            </tbody>
        </table>
    `;
    
    // Add summary section
    html += `
        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Total Effectif:</strong> ${data.grand_totals.total_people}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Prix Moyen par Personne:</strong> ${data.grand_totals.total_people > 0 ? 
                        (data.grand_totals.total_cost / data.grand_totals.total_people).toFixed(2) : 
                        '0.00'} DH</p>
                </div>
            </div>
            <p><strong>Prix de Revient de la Journée:</strong> ${data.grand_totals.total_cost.toFixed(2)} DH</p>
        </div>
    `;
    
    // Add menu breakdown
    if (data.menus && data.menus.length > 0) {
        html += `
            <h5 class="mt-4">Détail par menu</h5>
            <div class="row">
        `;
        
        data.menus.forEach(menu => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">${menu.type_menu}</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Effectif:</strong> ${menu.total_people}</p>
                            <p><strong>Prix de Revient:</strong> ${menu.total_cost.toFixed(2)} DH</p>
                            <p><strong>Prix Moyen:</strong> ${menu.total_people > 0 ? 
                                (menu.total_cost / menu.total_people).toFixed(2) : 
                                '0.00'} DH</p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `</div>`;
    }
    
    // Set the HTML content
    document.getElementById('categoryTableData').innerHTML = html;
}