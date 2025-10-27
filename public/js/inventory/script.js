$(document).ready(function () {
    // Initialize variables
    let selectedProductId = '';
    let selectedYear = $('#year_selector').val();
    let currentMonth = new Date().getMonth() + 1; // Current month (1-12)
    
    // Initialize event handlers
    initializeEventHandlers();
    
    /**
     * Initialize all event handlers
     */
    function initializeEventHandlers() {
        // Product selector change - remains exactly the same
        $('#product_selector').on('change', function() {
            selectedProductId = $(this).val();
            if (selectedProductId) {
                // Explicitly fetch product price when product changes
                getProductAveragePrice(selectedProductId);
                
                loadInventoryData();
                $('#inventory-alert').hide();
                $('#inventory-content').show();
                
                // Scroll table to show current month (if in current year)
                if (parseInt(selectedYear) === new Date().getFullYear()) {
                    setTimeout(scrollToCurrentMonth, 500);
                }
                
                // Load annual balance data
                setTimeout(function() {
                    loadAnnualBalanceData(selectedProductId, selectedYear);
                }, 500);
            } else {
                $('#inventory-alert').show();
                $('#inventory-content').hide();
                // Reset price display when no product is selected
                $('#unit-price').text('0.00');
                // Reset annual balance table
                $('.annual-entree, .annual-sortie').text('-');
                $('#annual-total-entree, #annual-total-sortie').text('-');
            }
        });
        
        // Year selector change - modified to remove the change event as year is now hidden
        selectedYear = $('#year_selector').val();
        
        // Scroll to current month button
        $('#scroll-to-current').on('click', function() {
            scrollToCurrentMonth();
        });
        
        // Export PDF button
        $('#export-pdf').on('click', function() {
            if (!selectedProductId) {
                alert('Veuillez d\'abord sélectionner un produit');
                return;
            }
            
            const productName = $('#product_selector option:selected').text();
            
            window.location.href = `${generateMultiMonthPdf}?product_id=${selectedProductId}&year=${selectedYear}&product_name=${encodeURIComponent(productName)}`;
        });
        
        // Print button
        $('#print-btn').on('click', function() {
            window.print();
        });
        
        // Annual balance print button
        $('#print-annual-btn').on('click', function() {
            // Create a print-only version with just the annual balance table
            const printContent = `
                <html>
                    <head>
                        <title>Balance Annuelle - ${$('.product-name').text()}</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                            th:first-child, td:first-child { text-align: left; }
                            th { background-color: #f8f9fa; }
                            .title { text-align: center; margin-bottom: 20px; }
                            tfoot { font-weight: bold; border-top: 2px solid #000; }
                            tfoot td { background-color: #f8f9fa; }
                        </style>
                    </head>
                    <body>
                        <h1 class="title">BALANCE ANNUELLE</h1>
                        <h3 class="title">Produit: ${$('.product-name').text()} | Année: ${$('.year-value').text()}</h3>
                        ${$('#annual-balance-table').prop('outerHTML')}
                    </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            setTimeout(function() { printWindow.close(); }, 1000);
        });
        
        // Export annual balance PDF button
        $('#export-annual-pdf').on('click', function() {
            if (!selectedProductId) {
                alert('Veuillez d\'abord sélectionner un produit');
                return;
            }
            
            const productName = $('#product_selector option:selected').text();
            
            window.location.href = `${generateAnnualBalancePdf}?product_id=${selectedProductId}&year=${selectedYear}&product_name=${encodeURIComponent(productName)}`;
        });
    }
    
    // All other functions remain exactly the same as in your original script
    
    /**
     * Scroll table horizontally to show current month
     */
    function scrollToCurrentMonth() {
        const tableContainer = $('.table-responsive');
        const monthIndex = currentMonth * 3 - 1; // Each month has 3 columns, adjusting for offset
        const headerCell = $(`th.month-column-header[data-month="${currentMonth}"]`);
        
        if (headerCell.length) {
            // Highlight current month columns
            $('th[data-month], td[data-day][data-month]').removeClass('current-month-column');
            $(`th[data-month="${currentMonth}"], td[data-day][data-month="${currentMonth}"]`).addClass('current-month-column');
            
            // Calculate scroll position to center current month
            const cellPosition = headerCell.position().left;
            const containerWidth = tableContainer.width();
            const scrollTo = cellPosition - (containerWidth / 3);
            
            // Smooth scroll to position
            tableContainer.animate({
                scrollLeft: Math.max(0, scrollTo)
            }, 300);
        }
    }
    
    /**
     * Load inventory data for all 12 months
     */
    function loadInventoryData() {
        // Show loading indicator
        $('#inventory-body').html('<tr><td colspan="37" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement des données...</td></tr>');
        
        // Update title information
        const productName = $('#product_selector option:selected').text();
        $('.product-name').text(productName);
        $('.year-value').text(selectedYear);
        
        // Reset all cells and totals
        resetTableData();
        
        // Prepare the table rows for days (clearing previous data)
        prepareTableRows();
        
        // Get all 12 months
        const allMonths = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        
        // Keep track of loaded months
        let loadedMonths = 0;
        let loadingCleared = false;
        
        // For each month, load data separately
        allMonths.forEach(function(month) {
            $.ajax({
                type: "GET",
                url: getProductInventory,
                data: {
                    product_id: selectedProductId,
                    year: selectedYear,
                    month: month
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 200) {
                        // Clear loading message after first successful response
                        if (!loadingCleared) {
                            $('#inventory-body tr:first').remove();
                            loadingCleared = true;
                        }
                        
                        // Process daily data
                        populateMonthData(month, response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading data for month " + month + ":", error);
                },
                complete: function() {
                    // Increment counter regardless of success/failure
                    loadedMonths++;
                    if (loadedMonths === 12) {
                        if (!loadingCleared) {
                            // If we've loaded all months but haven't cleared the loading indicator
                            // (meaning no successful responses), show error
                            $('#inventory-body').html('<tr><td colspan="37" class="text-center text-danger">Erreur lors du chargement des données</td></tr>');
                        } else {
                            // Highlight current month after all data is loaded
                            const currentMonth = new Date().getMonth() + 1;
                            if (parseInt(selectedYear) === new Date().getFullYear()) {
                                $(`th[data-month="${currentMonth}"], td[data-day][data-month="${currentMonth}"]`).addClass('current-month-column');
                            }
                            
                            // We don't calculate yearly average price here anymore
                            // to avoid overwriting the value from getProductAveragePrice()
                            console.log("All months loaded");
                        }
                    }
                }
            });
        });
    }
    
    function getProductAveragePrice(productId) {
        // Clear any previous value
        $('#unit-price').text('0.00');
        
        if (!productId) {
            return;
        }
        
        $.ajax({
            type: "GET",
            url: getProductAveragePriceUrl,  // Changed from getProductAveragePrice to getProductAveragePriceUrl
            data: {
                product_id: productId,
                year: selectedYear
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 200 && response.average_price !== undefined) {
                    const formattedPrice = parseFloat(response.average_price).toFixed(2);
                    $('#unit-price').text(formattedPrice);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading average product price:", error);
                $('#unit-price').text('0.00');
            }
        });
    }
    
    /**
     * Prepare the table rows with proper days for each month
     */
    function prepareTableRows() {
        // Clear existing rows
        $('#inventory-body').empty();
        
        // Create rows for all 31 possible days
        for (let day = 1; day <= 31; day++) {
            const row = $('<tr>');
            row.append(`<td class="day-col">${day}</td>`);
            
            // Add cells for each month
            for (let month = 1; month <= 12; month++) {
                // Determine if this day exists in this month for the selected year
                const daysInMonth = new Date(selectedYear, month, 0).getDate();
                const dayExists = day <= daysInMonth;
                const cellClass = dayExists ? '' : 'non-existent-day';
                
                // Create cells with appropriate classes
                row.append(`<td class="entree-cell ${cellClass}" data-day="${day}" data-month="${month}"></td>`);
                row.append(`<td class="sortie-cell ${cellClass}" data-day="${day}" data-month="${month}"></td>`);
                row.append(`<td class="reste-cell ${cellClass}" data-day="${day}" data-month="${month}"></td>`);
            }
            
            $('#inventory-body').append(row);
        }
    }
    
    /**
     * Reset all table cells and totals
     */
    function resetTableData() {
        // Clear all data cells
        $('.entree-cell, .sortie-cell, .reste-cell').empty();
        
        // Reset totals
        $('.month-total-entree, .month-total-sortie, .month-final-reste').text('0.00');
        
        // Remove highlighting
        $('th, td').removeClass('current-month-column');
    }
    
    /**
     * Populate data for a specific month
     */
    function populateMonthData(month, data) {
        // Populate monthly totals for entries and exits
        const totalEntree = parseFloat(data.month_total?.total_entrees || 0).toFixed(2);
        const totalSortie = parseFloat(data.month_total?.total_sorties || 0).toFixed(2);
        const finalReste = parseFloat(data.month_total?.end_stock || 0).toFixed(2);
        
        // Get the average price for this month (already in your data)
        const averagePrice = parseFloat(data.month_total?.average_price || 0).toFixed(2);
        
        // Only show totals if there's any activity in the month
        if (parseFloat(totalEntree) > 0 || parseFloat(totalSortie) > 0) {
            // Month has activity, show totals
            $(`.month-total-entree[data-month="${month}"]`).text(totalEntree > 0 ? totalEntree : '');
            $(`.month-total-sortie[data-month="${month}"]`).text(totalSortie > 0 ? totalSortie : '');
            $(`.month-final-reste[data-month="${month}"]`).text(finalReste);
        } else {
            // No activity in this month, clear totals
            $(`.month-total-entree[data-month="${month}"]`).text('');
            $(`.month-total-sortie[data-month="${month}"]`).text('');
            $(`.month-final-reste[data-month="${month}"]`).text('');
        }
        
        // Display the average price (add this to your existing code)
        $(`.month-average-price[data-month="${month}"]`).text(averagePrice > 0 ? averagePrice : '');
        
        // Determine number of days in the month
        const daysInMonth = new Date(selectedYear, month, 0).getDate();
        
        // Populate daily data
        for (let day = 1; day <= daysInMonth; day++) {
            const dayData = data.days[day] || {
                date: `${selectedYear}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`,
                entree: 0,
                sortie: 0,
                reste: 0
            };
            
            // Check if there's any activity on this day
            const hasEntree = parseFloat(dayData.entree) > 0;
            const hasSortie = parseFloat(dayData.sortie) > 0;
            const hasActivity = hasEntree || hasSortie;
            
            // Only show values for entries and exits if they're greater than 0
            if (hasEntree) {
                $(`.entree-cell[data-day="${day}"][data-month="${month}"]`).text(
                    parseFloat(dayData.entree).toFixed(2)
                );
            }
            
            if (hasSortie) {
                $(`.sortie-cell[data-day="${day}"][data-month="${month}"]`).text(
                    parseFloat(dayData.sortie).toFixed(2)
                );
            }
            
            // Only show reste if there's activity on this day (entrée or sortie)
            if (hasActivity) {
                $(`.reste-cell[data-day="${day}"][data-month="${month}"]`).text(
                    parseFloat(dayData.reste).toFixed(2)
                );
            } else {
                $(`.reste-cell[data-day="${day}"][data-month="${month}"]`).text('');
            }
        }
    }
    
    /**
     * Load annual balance data
     */
    function loadAnnualBalanceData(productId, year) {
        // Reset the annual balance table
        $('.annual-entree, .annual-sortie').text('-');
        $('#annual-total-entree, #annual-total-sortie').text('-');
        
        // Show loading state
        $('#annual-balance-table tbody tr td:not(:first-child)').html('<i class="fa fa-spinner fa-spin"></i>');
        
        // Use the existing getMonthlyReport endpoint to get the data
        $.ajax({
            type: "GET",
            url: getMonthlyReport,
            data: {
                product_id: productId,
                year: year
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 200 && response.data && response.data.length > 0) {
                    // Reset loading state
                    $('.annual-entree, .annual-sortie').text('0.00');
                    
                    // We expect data for the selected product only
                    const productData = response.data.find(item => item.product_id == productId);
                    
                    if (productData) {
                        // Variables to track annual totals
                        let annualEntrees = 0;
                        let annualSorties = 0;
                        
                        // Populate data for each month
                        for (let month = 1; month <= 12; month++) {
                            const monthData = productData.months[month] || { entrees: 0, sorties: 0 };
                            const entrees = parseFloat(monthData.entrees || 0);
                            const sorties = parseFloat(monthData.sorties || 0);
                            
                            // Format and display the values - always show 0.00 for months without data
                            // This ensures yearly data is always displayed even without data for all months
                            $(`.annual-entree[data-month="${month}"]`).text(entrees.toFixed(2));
                            $(`.annual-sortie[data-month="${month}"]`).text(sorties.toFixed(2));
                            
                            // Add to annual totals
                            annualEntrees += entrees;
                            annualSorties += sorties;
                        }
                        
                        // Display annual totals - always show totals even if they're zero
                        $('#annual-total-entree').text(annualEntrees.toFixed(2));
                        $('#annual-total-sortie').text(annualSorties.toFixed(2));
                    } else {
                        // If no product data found, show zeros for all months
                        for (let month = 1; month <= 12; month++) {
                            $(`.annual-entree[data-month="${month}"]`).text('0.00');
                            $(`.annual-sortie[data-month="${month}"]`).text('0.00');
                        }
                        $('#annual-total-entree').text('0.00');
                        $('#annual-total-sortie').text('0.00');
                    }
                } else {
                    // If no data found, show zeros for all months
                    for (let month = 1; month <= 12; month++) {
                        $(`.annual-entree[data-month="${month}"]`).text('0.00');
                        $(`.annual-sortie[data-month="${month}"]`).text('0.00');
                    }
                    $('#annual-total-entree').text('0.00');
                    $('#annual-total-sortie').text('0.00');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading annual balance data:", error);
                // On error, show zeros instead of dashes
                for (let month = 1; month <= 12; month++) {
                    $(`.annual-entree[data-month="${month}"]`).text('0.00');
                    $(`.annual-sortie[data-month="${month}"]`).text('0.00');
                }
                $('#annual-total-entree').text('0.00');
                $('#annual-total-sortie').text('0.00');
            }
        });
    }
    
    // CARDEX Print button handler
    $('#print-cardex-btn').on('click', function() {
        // Get the selected product
        const productId = $('#product_selector').val();
        
        if (!productId) {
            alert('Veuillez sélectionner un produit avant d\'imprimer le CARDEX');
            return;
        }
        
        const productName = $('#product_selector option:selected').text();
        const unitPrice = $('#unit-price').text();
        const year = $('#year_selector').val() || new Date().getFullYear();
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Generate HTML for the CARDEX document
        const cardexHtml = generateCardexHtml(productName, unitPrice, year);
        
        // Write content to the new window
        printWindow.document.write(cardexHtml);
        printWindow.document.close();
        
        // Populate data from your inventory tables
        populateCardexData(printWindow, productId, year);
        
        // Trigger print after the document is loaded
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
            }, 500);
        };
    });

    // Function to generate CARDEX HTML structure
    function generateCardexHtml(productName, unitPrice, year) {
        return `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>CARDEX - ${productName}</title>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 1cm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        font-size: 10px;
                    }
                    .header {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 15px;
                    }
                    .header-left {
                        text-align: left;
                        width: 33%;
                    }
                    .header-center {
                        text-align: center;
                        width: 33%;
                    }
                    .header-right {
                        text-align: right;
                        width: 33%;
                    }
                    .product-box {
                        border: 1px solid #000;
                        padding: 5px;
                        margin-top: 5px;
                    }
                    .cardex-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }
                    .cardex-table th, .cardex-table td {
                        border: 1px solid #000;
                        padding: 2px;
                        text-align: center;
                        height: 16px;
                        font-size: 9px;
                    }
                    .cardex-table th {
                        background-color: #f8f9fa;
                        font-weight: bold;
                        text-transform: uppercase;
                    }
                    .annual-balance {
                        float: right;
                        width: 40%;
                        margin-top: 20px;
                    }
                    .annual-balance-title {
                        text-align: center;
                        font-weight: bold;
                        margin-bottom: 5px;
                    }
                    .annual-table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .annual-table th, .annual-table td {
                        border: 1px solid #000;
                        padding: 3px;
                        text-align: center;
                    }
                    .page-break {
                        page-break-before: always;
                    }
                </style>
            </head>
            <body>
                <!-- Page 1: January-July -->
                <div class="page">
                    <div class="header">
                        <div class="header-left">
                            <div>ROYAUME DU MAROC</div>
                            <div>MINISTÈRE DU TOURISME</div>
                            <div style="margin-bottom: 5px;"></div>
                            <div>Centre de Qualification Professionnelle</div>
                            <div>Hôtelière et Touristique</div>
                            <div>de Touarga</div>
                        </div>
                        <div class="header-center">
                            <div>CARDEX :</div>
                            <div style="margin-top: 10px;">ARTICLES :</div>
                            <div class="product-box">
                                ${productName}
                            </div>
                        </div>
                        <div class="header-right">
                            <div>Prix Unitaire</div>
                            <div style="margin-top: 5px; font-weight: bold;">${unitPrice} DH</div>
                        </div>
                    </div>
                    
                    <table class="cardex-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Dates</th>
                                <th colspan="3">JANVIER</th>
                                <th colspan="3">FEVRIER</th>
                                <th colspan="3">MARS</th>
                                <th colspan="3">AVRIL</th>
                                <th colspan="3">MAI</th>
                                <th colspan="3">JUIN</th>
                                <th colspan="3">JUILLET</th>
                            </tr>
                            <tr>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                                <th>Entrées</th>
                                <th>Sorties</th>
                                <th>Reste en magasin</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generateDaysRows(1, 7)}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>TOTAUX</th>
                                ${generateTotalsColumns(1, 7)}
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Page 2: August-December with Annual Balance -->
                <div class="page-break"></div>
                <div class="page">
                    <div class="header">
                        <div class="header-left">
                            <div>ROYAUME DU MAROC</div>
                            <div>MINISTÈRE DU TOURISME</div>
                            <div style="margin-bottom: 5px;"></div>
                            <div>Centre de Qualification Professionnelle</div>
                            <div>Hôtelière et Touristique</div>
                            <div>de Touarga</div>
                        </div>
                        <div class="header-center">
                            <div>CARDEX :</div>
                            <div style="margin-top: 10px;">ARTICLES :</div>
                            <div class="product-box">
                                ${productName}
                            </div>
                        </div>
                        <div class="header-right">
                            <div>Prix Unitaire</div>
                            <div style="margin-top: 5px; font-weight: bold;">${unitPrice} DH</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="width: 60%;">
                            <table class="cardex-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Dates</th>
                                        <th colspan="3">AOUT</th>
                                        <th colspan="3">SEPTEMBRE</th>
                                        <th colspan="3">OCTOBRE</th>
                                        <th colspan="3">NOVEMBRE</th>
                                        <th colspan="3">DECEMBRE</th>
                                    </tr>
                                    <tr>
                                        <th>Entrées</th>
                                        <th>Sorties</th>
                                        <th>Reste en magasin</th>
                                        <th>Entrées</th>
                                        <th>Sorties</th>
                                        <th>Reste en magasin</th>
                                        <th>Entrées</th>
                                        <th>Sorties</th>
                                        <th>Reste en magasin</th>
                                        <th>Entrées</th>
                                        <th>Sorties</th>
                                        <th>Reste en magasin</th>
                                        <th>Entrées</th>
                                        <th>Sorties</th>
                                        <th>Reste en magasin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${generateDaysRows(8, 12)}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>TOTAUX</th>
                                        ${generateTotalsColumns(8, 12)}
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div style="width: 40%;">
                            <div class="annual-balance">
                                <div class="annual-balance-title">BALANCE ANNUELLE</div>
                                <table class="annual-table">
                                    <thead>
                                        <tr>
                                            <th>Mois</th>
                                            <th>Entrées</th>
                                            <th>Sorties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Janvier</td><td id="annual-entree-1">-</td><td id="annual-sortie-1">-</td></tr>
                                        <tr><td>Février</td><td id="annual-entree-2">-</td><td id="annual-sortie-2">-</td></tr>
                                        <tr><td>Mars</td><td id="annual-entree-3">-</td><td id="annual-sortie-3">-</td></tr>
                                        <tr><td>Avril</td><td id="annual-entree-4">-</td><td id="annual-sortie-4">-</td></tr>
                                        <tr><td>Mai</td><td id="annual-entree-5">-</td><td id="annual-sortie-5">-</td></tr>
                                        <tr><td>Juin</td><td id="annual-entree-6">-</td><td id="annual-sortie-6">-</td></tr>
                                        <tr><td>Juillet</td><td id="annual-entree-7">-</td><td id="annual-sortie-7">-</td></tr>
                                        <tr><td>Août</td><td id="annual-entree-8">-</td><td id="annual-sortie-8">-</td></tr>
                                        <tr><td>Septembre</td><td id="annual-entree-9">-</td><td id="annual-sortie-9">-</td></tr>
                                        <tr><td>Octobre</td><td id="annual-entree-10">-</td><td id="annual-sortie-10">-</td></tr>
                                        <tr><td>Novembre</td><td id="annual-entree-11">-</td><td id="annual-sortie-11">-</td></tr>
                                        <tr><td>Décembre</td><td id="annual-entree-12">-</td><td id="annual-sortie-12">-</td></tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Totaux de l'année</td>
                                            <td id="annual-total-entree">-</td>
                                            <td id="annual-total-sortie">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        `;
    }

    // Function to generate table rows for days
    function generateDaysRows(startMonth, endMonth) {
        let html = '';
        
        for (let day = 1; day <= 31; day++) {
            html += `<tr>
                <td>${day}</td>`;
            
            for (let month = startMonth; month <= endMonth; month++) {
                html += `
                    <td class="entree-cell" data-day="${day}" data-month="${month}"></td>
                    <td class="sortie-cell" data-day="${day}" data-month="${month}"></td>
                    <td class="reste-cell" data-day="${day}" data-month="${month}"></td>
                `;
            }
            
            html += `</tr>`;
        }
        
        return html;
    }

    // Function to generate totals columns
    function generateTotalsColumns(startMonth, endMonth) {
        let html = '';
        
        for (let month = startMonth; month <= endMonth; month++) {
            html += `
                <th class="month-total-entree" data-month="${month}"></th>
                <th class="month-total-sortie" data-month="${month}"></th>
                <th class="month-final-reste" data-month="${month}"></th>
            `;
        }
        
        return html;
    }

    // Function to populate data in the CARDEX
    function populateCardexData(printWindow, productId, year) {
        // Find all month total data from your existing page
        const annualEntrees = {};
        const annualSorties = {};
        let annualTotalEntree = 0;
        let annualTotalSortie = 0;
        
        // Get data from your inventory page
        for (let month = 1; month <= 12; month++) {
            // Get monthly totals
            const monthTotalEntree = $(`.month-total-entree[data-month="${month}"]`).text();
            const monthTotalSortie = $(`.month-total-sortie[data-month="${month}"]`).text();
            const monthFinalReste = $(`.month-final-reste[data-month="${month}"]`).text();
            
            // Copy to print window
            if (printWindow.document) {
                const doc = printWindow.document;
                
                // Populate totals
                $(doc).find(`.month-total-entree[data-month="${month}"]`).text(monthTotalEntree);
                $(doc).find(`.month-total-sortie[data-month="${month}"]`).text(monthTotalSortie);
                $(doc).find(`.month-final-reste[data-month="${month}"]`).text(monthFinalReste);
                
                // Populate daily data
                for (let day = 1; day <= 31; day++) {
                    const entreeValue = $(`.entree-cell[data-day="${day}"][data-month="${month}"]`).text();
                    const sortieValue = $(`.sortie-cell[data-day="${day}"][data-month="${month}"]`).text();
                    const resteValue = $(`.reste-cell[data-day="${day}"][data-month="${month}"]`).text();
                    
                    $(doc).find(`.entree-cell[data-day="${day}"][data-month="${month}"]`).text(entreeValue);
                    $(doc).find(`.sortie-cell[data-day="${day}"][data-month="${month}"]`).text(sortieValue);
                    $(doc).find(`.reste-cell[data-day="${day}"][data-month="${month}"]`).text(resteValue);
                }
                
                // Annual balance values
                const entreeValue = parseFloat(monthTotalEntree || 0);
                const sortieValue = parseFloat(monthTotalSortie || 0);
                
                // Always show values in annual balance, even if they're zero
                annualEntrees[month] = entreeValue;
                annualTotalEntree += entreeValue;
                $(doc).find(`#annual-entree-${month}`).text(entreeValue.toFixed(2));
                
                annualSorties[month] = sortieValue;
                annualTotalSortie += sortieValue;
                $(doc).find(`#annual-sortie-${month}`).text(sortieValue.toFixed(2));
            }
        }
        
        // Update annual totals
        if (printWindow.document) {
            $(printWindow.document).find('#annual-total-entree').text(annualTotalEntree.toFixed(2));
            $(printWindow.document).find('#annual-total-sortie').text(annualTotalSortie.toFixed(2));
        }
    }

    // CARDEX print button handler for direct URL approach
    $('#print-cardex-btn').on('click', function() {
        // Get the selected product ID
        var productId = $('#product_selector').val();
        
        if (!productId) {
            alert('Veuillez sélectionner un produit avant d\'imprimer le CARDEX');
            return;
        }
        
        // Get the selected year
        var year = $('#year_selector').val() || new Date().getFullYear();
        
        // Create the cardex URL
        var cardexUrl = "{{ url('cardex') }}?product_id=" + productId + "&year=" + year;
        
        // Open in a new window
        window.open(cardexUrl, '_blank');
    });
});