// home.js - Script pour le tableau de bord
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les graphiques
    initSalesChart();
    initStatusCharts();
    
    // Graphique des ventes et achats
    function initSalesChart() {
        fetch('/api/dashboard/chart-data')
            .then(response => response.json())
            .then(data => {
                var options = {
                    series: [{
                        name: 'Commandes',
                        type: 'area',
                        data: data.ventes
                    }, {
                        name: 'Achats',
                        type: 'line',
                        data: data.achats
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: { show: false }
                    },
                    stroke: { width: [2, 2], curve: 'smooth' },
                    fill: {
                        type: ['gradient', 'solid'],
                        opacity: [0.2, 1]
                    },
                    labels: data.labels,
                    markers: { size: 3 },
                    colors: ['#E11D48', '#3B82F6']
                };

                var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
                chart.render();
            })
            .catch(error => console.error('Erreur:', error));
    }
    
    // Graphiques de statut
    function initStatusCharts() {
        fetch('/api/dashboard/status-data')
            .then(response => response.json())
            .then(data => {
                // Graphique des statuts de ventes
                var venteOptions = {
                    series: [
                        data.ventes.creation,
                        data.ventes.validation,
                        data.ventes.livraison,
                        data.ventes.reception
                    ],
                    chart: { type: 'donut', height: 240 },
                    labels: ['Création', 'Validation', 'Livraison', 'Réception'],
                    colors: ['#FBBF24', '#4F46E5', '#10B981', '#16A34A'],
                    legend: { show: false },
                    dataLabels: { enabled: false }
                };

                var venteChart = new ApexCharts(document.querySelector("#vente-status-chart"), venteOptions);
                venteChart.render();
                
                // Graphique des statuts d'achats
                var achatOptions = {
                    series: [
                        data.achats.creation,
                        data.achats.validation,
                        data.achats.livraison,
                        data.achats.reception
                    ],
                    chart: { type: 'donut', height: 240 },
                    labels: ['Création', 'Validation', 'Livraison', 'Réception'],
                    colors: ['#FBBF24', '#4F46E5', '#10B981', '#16A34A'],
                    legend: { show: false },
                    dataLabels: { enabled: false }
                };

                var achatChart = new ApexCharts(document.querySelector("#achat-status-chart"), achatOptions);
                achatChart.render();
            })
            .catch(error => console.error('Erreur:', error));
    }
});