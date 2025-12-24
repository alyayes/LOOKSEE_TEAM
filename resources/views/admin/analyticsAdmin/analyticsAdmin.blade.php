@extends('layouts.mainAdmin') 

@section('title', 'Analytics Dashboard')

@section('content')
{{-- 
    CATATAN: 
    Saya menghapus <div class="page-wrapper"> dan <div class="page-content"> 
    karena biasanya sudah ada di 'layouts.mainAdmin'. 
    Jika setelah ini tampilan malah jadi tertutup sidebar, 
    kembalikan div tersebut tapi HAPUS bagian @section('styles') di bawah.
--}}

<div class="row">
    <div class="col-12 col-lg-8 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div><h6 class="mb-0">Sales Overview ({{ date('Y') }})</h6></div>
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
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container-2">
                    <canvas id="chart2"></canvas>
                </div>
                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">
                        Pending <span class="badge bg-warning text-dark rounded-pill">{{ $pendingCount }}</span>
                    </li>
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        Prepared <span class="badge bg-danger rounded-pill">{{ $preparedCount }}</span>
                    </li>
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        Shipped <span class="badge bg-primary rounded-pill">{{ $shippedCount }}</span>
                    </li>
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        Completed <span class="badge bg-success rounded-pill">{{ $completedCount }}</span>
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
                <p class="font-weight-bold mb-1 text-secondary">Weekly New User</p>
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">{{ number_format($totalWeeklyVisitors) }}</h4>
                    </div>
                    <div class="">
                        <p class="mb-0 align-self-center font-weight-bold text-{{ $weeklyAccessPercentageDirection == 'up' ? 'success' : 'danger' }} ms-2">
                            {{ $weeklyAccessPercentageChange }} 
                            <i class="bx bx-{{ $weeklyAccessPercentageDirection == 'up' ? 'up' : 'down' }}-arrow-alt align-middle"></i>
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
                    <div><h6 class="mb-0">Trending Posts (Top 5)</h6></div>
                </div>
            </div>
            <div class="card-body info-card">
                <h3 class="my-1 text-primary">{{ $totalLikesTrendingPosts }} Likes</h3>
                <div class="chart-container-0" style="height: 150px; width: 150px; margin: 0 auto;">
                    <canvas id="chart4"></canvas>
                </div>
                <div class="mt-3 text-start">
                    @foreach ($trendingPostsLabels as $index => $label)
                        <p class="mb-0 text-secondary" style="font-size: 0.9em;">
                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $colorsTrendingPosts[$index % count($colorsTrendingPosts)] }}; margin-right: 5px;"></span>
                            {{ $label }} : <strong>{{ $trendingPostsData[$index] }}</strong>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // =========================================================
    // MENGAMBIL DATA DARI CONTROLLER KE JAVASCRIPT
    // =========================================================
    
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
    const colorsTrendingPosts = @json($colorsTrendingPosts);

    // =========================================================
    // KONFIGURASI CHART.JS
    // =========================================================

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
                backgroundColor: colorsTrendingPosts,
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
                { label: 'Man', data: chartTrendingProductsManValues, backgroundColor: '#66CC99', borderColor: '#4CAF50', borderWidth: 1 },
                { label: 'Woman', data: chartTrendingProductsWomanValues, backgroundColor: '#FF99CC', borderColor: '#FF66B2', borderWidth: 1 }
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
@endsection

@section('styles')
<style>
    /* HANYA Style Tambahan untuk Chart.
       Saya HAPUS style grid (col-lg, row, page-wrapper) agar tidak bentrok dengan template 
    */
    .chart-container-1, .chart-container-2, .chart-container-0 { position: relative; width: 100%; height: 250px; margin-bottom: 20px; }
    #chart3, #chart4, #chart5 { height: 200px; }
    .badge.bg-warning.text-dark { background-color: rgba(255, 193, 7, .8) !important; color: #fff !important; }
    .badge.bg-danger { background-color: rgb(253, 152, 0) !important; color: #fff !important; }
    .badge.bg-primary { background-color: rgb(148, 190, 253) !important; color: #fff !important; }
    .badge.bg-success { background-color: rgb(60, 199, 134) !important; color: #fff !important; }
</style>
@endsection