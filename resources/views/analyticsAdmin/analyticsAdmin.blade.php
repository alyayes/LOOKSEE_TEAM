@extends('layouts.mainAdmin') 

@section('title', 'Products List')

@section('styles')
@php
    
    $pendingCount = 17;
    $preparedCount = 3;
    $shippedCount = 5;
    $completedCount = 10;
    $chartDoughnutDataValues = [$pendingCount, $preparedCount, $shippedCount, $completedCount];
    $chartDoughnutColorsFromBadges = [
        'rgba(255, 193, 7, .8)',    // Pending
        'rgb(253, 152, 0)',         // Prepared
        'rgb(148, 190, 253)',       // Shipped
        'rgb(60, 199, 134)'         // Completed
    ];

    // Data Sales Overview (Chart 1)
    $monthlySalesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $monthlySalesData = [3000000, 3500000, 4000000, 4500000, 5000000, 12654000, 6000000, 7000000, 0, 0, 0, 0];

    // Data Weekly Access (Chart 3)
    $weeklyAccessLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $weeklyAccessData = [180, 210, 250, 190, 220, 240, 160];
    $totalWeeklyVisitors = array_sum($weeklyAccessData);
    $weeklyAccessPercentageChange = "4.4%";
    $weeklyAccessPercentageDirection = "up";

    // Data Trending Posts (Chart 4)
    $trendingPostsLabels = ['Keep it simple', 'Gloomy day', 'Sampai kapan'];
    $trendingPostsData = [15, 10, 5];
    $totalLikesTrendingPosts = array_sum($trendingPostsData);
    $colorsTrendingPosts = ['#FF99CC', '#4D94FF', '#66CC99', '#FFD700', '#DDA0DD'];

    // Data Trending Products (Chart 5)
    $allTrendingLabelsUnique = ['Celana Kargo Japanse', 'Hoodie Relaxed', 'Kemeja Oversized', 'Blouse Loose', 'Sandal Wanita Loop', 'Dress Motif Bunga'];
    $chartTrendingProductsManValues = [12, 10, 8, 0, 0, 0];
    $chartTrendingProductsWomanValues = [0, 0, 0, 15, 13, 10];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; }
        .page-wrapper { padding: 20px; }
        .page-content { padding: 20px; }
        .row { display: flex; flex-wrap: wrap; margin-left: -10px; margin-right: -10px; }
        .col { flex: 1 0 0%; padding-left: 10px; padding-right: 10px; max-width: 100%; }
        .col-12 { width: 100%; }
        .col-lg-4 { flex: 0 0 auto; width: 33.33333333%; }
        .col-lg-6 { flex: 0 0 auto; width: 50%; }
        .col-lg-8 { flex: 0 0 auto; width: 66.66666667%; }
        .d-flex { display: flex; }
        .w-100 { width: 100%; }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }
        .card-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: flex-start; }
        .align-items-center { align-items: center; }
        .ms-auto { margin-left: auto; }
        h3.my-1 { font-size: 2em; }
        p.mb-0 { font-size: 0.9em; }
        .text-danger { color: #dc3545 !important; }
        .text-success { color: #198754 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-primary { color: #007bff !important; }
        .chart-container-1, .chart-container-2, .chart-container-0 { position: relative; width: 100%; height: 250px; margin-bottom: 20px; }
        #chart3 { height: 200px; }
        #chart4 { height: 200px; }
        #chart5 { height: 200px; }
        .card.info-card { justify-content: center; align-items: center; text-align: center; }
        .list-group-item .badge { min-width: 40px; }
        .badge.bg-warning.text-dark { background-color: rgba(255, 193, 7, .8) !important; color: #fff !important; }
        .badge.bg-danger { background-color: rgb(253, 152, 0) !important; color: #fff !important; }
        .badge.bg-primary { background-color: rgb(148, 190, 253) !important; color: #fff !important; }
        .badge.bg-success { background-color: rgb(60, 199, 134) !important; color: #fff !important; }
        .card-header h6 { font-weight: 600; font-size: 1rem; }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-12 col-lg-8 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div><h6 class="mb-0">Sales Overview</h6></div>
                                {{-- Dropdown Action Placeholder --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container-1">
                                <canvas id="chart1"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div><h6 class="mb-0">Order Status Distribution</h6></div>
                                {{-- Dropdown Action Placeholder --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container-2">
                                <canvas id="chart2"></canvas>
                            </div>
                            <ul class="list-group list-group-flush mt-3">
                                <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">
                                    Pending<span class="badge bg-warning text-dark rounded-pill">{{ $pendingCount }}</span>
                                </li>
                                <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                    Prepared<span class="badge bg-danger rounded-pill">{{ $preparedCount }}</span>
                                </li>
                                <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                    Shipped<span class="badge bg-primary rounded-pill">{{ $shippedCount }}</span>
                                </li>
                                <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                    Completed<span class="badge bg-success rounded-pill">{{ $completedCount }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-4 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <p class="font-weight-bold mb-1 text-secondary">Weekly Access</p>
                            <div class="d-flex align-items-center mb-4">
                                <div>
                                    <h4 class="mb-0">{{ number_format($totalWeeklyVisitors) }}</h4>
                                </div>
                                <div class="">
                                    <p class="mb-0 align-self-center font-weight-bold text-success ms-2">
                                        {{ $weeklyAccessPercentageChange }} <i class="bx bx-{{ $weeklyAccessPercentageDirection == 'up' ? 'up' : 'down' }}-arrow-alt align-middle"></i>
                                    </p>
                                </div>
                            </div>
                            <div class="chart-container-0 mt-5">
                                <canvas id="chart3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div><h6 class="mb-0">Trending Posts</h6></div>
                                {{-- Dropdown Action Placeholder --}}
                            </div>
                        </div>
                        <div class="card-body info-card">
                            <h3 class="my-1 text-primary">{{ $totalLikesTrendingPosts }} Likes</h3>
                            <div class="chart-container-0" style="height: 150px; width: 150px;">
                                <canvas id="chart4"></canvas>
                            </div>
                            <div class="mt-3">
                                @foreach ($trendingPostsLabels as $index => $label)
                                    <p class="mb-0 text-secondary" style="font-size: 0.9em;">
                                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $colorsTrendingPosts[$index % count($colorsTrendingPosts)] }}; margin-right: 5px;"></span>
                                        {{ $label }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div><h6 class="mb-0">Top Trending Products</h6></div>
                                {{-- Dropdown Action Placeholder --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container-0">
                                <canvas id="chart5"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data PHP dari Blade @php block di-encode ke JavaScript
        const chartDoughnutDataValues = @json($chartDoughnutDataValues);
        const chartDoughnutColorsFromBadges = @json($chartDoughnutColorsFromBadges);

        const monthlySalesLabels = @json($monthlySalesLabels);
        const monthlySalesData = @json($monthlySalesData);

        const allTrendingLabels = @json($allTrendingLabelsUnique);
        const chartTrendingProductsManValues = @json($chartTrendingProductsManValues);
        const chartTrendingProductsWomanValues = @json($chartTrendingProductsWomanValues);

        const weeklyAccessLabels = @json($weeklyAccessLabels);
        const weeklyAccessData = @json($weeklyAccessData);

        const trendingPostsLabels = @json($trendingPostsLabels);
        const trendingPostsData = @json($trendingPostsData);
        // totalLikesTrendingPosts tidak perlu di-json karena sudah ada di HTML

    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
    // --- Chart.js Configurations (Sama dengan kode asli Anda) ---

    // CHART 1: Sales Overview (Bar Chart)
    const ctx1 = document.getElementById('chart1').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: monthlySalesLabels,
            datasets: [{
                label: 'Total Sales (IDR)',
                data: monthlySalesData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { callback: (value) => 'Rp ' + value.toLocaleString('id-ID') } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID') } } }
        }
    });

    // CHART 2: Order Status Distribution (Doughnut Chart)
    const ctx2 = document.getElementById('chart2').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Prepared', 'Shipped', 'Completed'],
            datasets: [{ label: 'Order Status', data: chartDoughnutDataValues, backgroundColor: chartDoughnutColorsFromBadges, hoverOffset: 4 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { boxWidth: 20, padding: 15 } }, tooltip: { callbacks: { label: (context) => context.label + ': ' + context.parsed } } }
        }
    });

    // CHART 3: Weekly Access (Line Chart)
    const ctx3 = document.getElementById('chart3').getContext('2d');
    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: weeklyAccessLabels,
            datasets: [{
                label: 'Weekly Visitors',
                data: weeklyAccessData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } },
            plugins: { legend: { display: false } },
            elements: { point: { radius: 3, backgroundColor: 'rgba(75, 192, 192, 1)' } }
        }
    });

    // CHART 4: Trending Posts (Pie Chart)
    const ctx4 = document.getElementById('chart4').getContext('2d');
    new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: trendingPostsLabels,
            datasets: [{
                label: 'Likes',
                data: trendingPostsData,
                backgroundColor: ['#FF99CC', '#4D94FF', '#66CC99', '#FFD700', '#DDA0DD'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => context.label + ': ' + context.parsed + ' Likes' } } }
        }
    });

    // CHART 5: Top Trending Products (Grouped Bar Chart)
    const ctx5 = document.getElementById('chart5').getContext('2d');
    new Chart(ctx5, {
        type: 'bar',
        data: {
            labels: allTrendingLabels,
            datasets: [
                { label: 'Trending Man', data: chartTrendingProductsManValues, backgroundColor: '#66CC99', borderColor: '#4CAF50', borderWidth: 1 },
                { label: 'Trending Woman', data: chartTrendingProductsWomanValues, backgroundColor: '#FF99CC', borderColor: '#FF66B2', borderWidth: 1 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { x: { stacked: false, grid: { display: false } }, y: { beginAtZero: true, stacked: false, grid: { display: false } } },
            plugins: { legend: { display: true, position: 'top', labels: { boxWidth: 10, padding: 10 } } }
        }
    });
    </script>
</body>
</html>