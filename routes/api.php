<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AnalyticsAdminController extends Controller
{
    public function index(Request $request)
    {
        // =========================================================================
        // 1. ORDER STATUS (Doughnut Chart)
        // =========================================================================
        // Mengambil status dan menghitung jumlahnya
        $orderStatusCounts = DB::table('orders')
            ->select(DB::raw('LOWER(status) as status_lower'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('LOWER(status)'))
            ->pluck('count', 'status_lower')
            ->toArray();

        // Helper function untuk mengambil nilai (agar tidak error jika index tidak ada)
        $getCount = fn($key) => $orderStatusCounts[$key] ?? 0;

        // Mapping Data (Sesuaikan string di dalam getCount dengan isi database kamu)
        $pendingCount   = $getCount('pending');
        
        // Gabungkan 'prepared' dan 'processing'
        $preparedCount  = $getCount('prepared') + $getCount('processing') + $getCount('confirmed');
        
        // Gabungkan status pengiriman
        $shippedCount   = $getCount('shipped') + $getCount('shipping') + $getCount('dikirim') + $getCount('on_delivery');
        
        // Gabungkan status selesai
        $completedCount = $getCount('completed') + $getCount('delivered') + $getCount('selesai') + $getCount('done');

        $chartDoughnutDataValues = [$pendingCount, $preparedCount, $shippedCount, $completedCount];
        
        $chartDoughnutColorsFromBadges = [
            'rgba(255, 193, 7, .8)', // Kuning (Pending)
            'rgb(253, 152, 0)',      // Oranye (Prepared)
            'rgb(148, 190, 253)',    // Biru (Shipped)
            'rgb(60, 199, 134)'      // Hijau (Completed)
        ];

        // =========================================================================
        // 2. MONTHLY SALES (Bar Chart)
        // =========================================================================
        $monthlySalesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlySalesData = array_fill(0, 12, 0); // Default 0 semua bulan

        $currentYear = date('Y');

        $salesResult = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month_num'),
                DB::raw('SUM(total_price) as monthly_total_sales')
            )
            ->whereYear('created_at', $currentYear)
            // Hanya hitung order yang valid (bukan cancelled/failed)
            ->whereIn(DB::raw('LOWER(status)'), ['completed', 'selesai', 'shipped', 'dikirim', 'prepared', 'processing', 'delivered'])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month_num')
            ->get();

        foreach ($salesResult as $row) {
            // Index array dimulai dari 0, sedangkan bulan 1-12. Jadi dikurang 1.
            $monthlySalesData[$row->month_num - 1] = (float) $row->monthly_total_sales;
        }

        // =========================================================================
        // 3. WEEKLY NEW USERS (Line Chart)
        // =========================================================================
        $weeklyAccessLabels = [];
        $weeklyAccessData = [];
        $dayNamesShort = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; // Urutan Carbon dayOfWeek

        // Ambil data 7 hari terakhir
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        
        $usersPerDay = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $sevenDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date')
            ->toArray();

        // Loop 7 hari ke belakang agar grafik tetap jalan meski hari itu 0 user
        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dateString = $dateObj->format('Y-m-d');
            
            // Generate Label (Contoh: "Mon")
            $weeklyAccessLabels[] = $dateObj->format('D'); // D = Mon, Tue, etc
            
            // Masukkan data (0 jika tidak ada)
            $weeklyAccessData[] = $usersPerDay[$dateString] ?? 0;
        }

        $totalWeeklyVisitors = array_sum($weeklyAccessData);

        // --- Hitung Persentase Kenaikan (Minggu Ini vs Minggu Lalu) ---
        $lastWeekStart = Carbon::now()->subDays(13)->startOfDay();
        $lastWeekEnd   = Carbon::now()->subDays(7)->endOfDay();
        
        $lastWeekUserCount = DB::table('users')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $percentChange = 0;
        if ($lastWeekUserCount > 0) {
            $percentChange = (($totalWeeklyVisitors - $lastWeekUserCount) / $lastWeekUserCount) * 100;
        } else {
            $percentChange = $totalWeeklyVisitors > 0 ? 100 : 0;
        }

        $weeklyAccessPercentageChange = number_format(abs($percentChange), 1) . "%";
        $weeklyAccessPercentageDirection = $percentChange >= 0 ? "up" : "down";

        // =========================================================================
        // 4. TRENDING POSTS (Pie Chart)
        // =========================================================================
        $trendingPostsLabels = [];
        $trendingPostsData = [];
        $totalLikesTrendingPosts = 0;
        $colorsTrendingPosts = ['#FF99CC', '#4D94FF', '#66CC99', '#FFD700', '#DDA0DD'];

        // Menggunakan LEFT JOIN agar post yg belum ada like tetap terhitung (opsional, di sini pakai JOIN biasa biar yg ada likenya aja)
        $trendingPostsResult = DB::table('posts as p')
            ->join('likes as l', 'p.id_post', '=', 'l.id_post')
            ->select('p.id_post', 'p.caption', DB::raw('COUNT(l.id_like) as like_count'))
            ->groupBy('p.id_post', 'p.caption')
            ->orderByDesc('like_count')
            ->limit(5)
            ->get();

        if ($trendingPostsResult->isEmpty()) {
            $trendingPostsLabels = ['No Data'];
            $trendingPostsData = [0];
        } else {
            foreach ($trendingPostsResult as $row) {
                // Batasi panjang caption agar grafik tidak berantakan
                $cleanCaption = strip_tags($row->caption); // Hapus tag HTML jika ada
                $caption = strlen($cleanCaption) > 15 ? substr($cleanCaption, 0, 15) . '...' : $cleanCaption;
                
                $trendingPostsLabels[] = $caption;
                $trendingPostsData[] = (int) $row->like_count;
                $totalLikesTrendingPosts += (int) $row->like_count;
            }
        }

        // =========================================================================
        // 5. TRENDING PRODUCTS (Bar Chart - Multi Axis)
        // =========================================================================
        // Perbaikan: Hati-hati dengan query LIKE '%man%', karena 'woman' mengandung kata 'man'.
        
        $trendingProductsManData = [];
        $trendingProductsWomanData = [];
        $allTrendingLabels = [];

        // 1. Query Top Produk Pria (Exclude 'woman' agar tidak duplikat)
        $resultMan = DB::table('order_items as od')
            ->join('produk_looksee as p', 'od.id_produk', '=', 'p.id_produk')
            ->select('p.nama_produk', DB::raw('SUM(od.quantity) as total_qty'))
            ->where(DB::raw('LOWER(p.kategori)'), 'LIKE', '%man%')
            ->where(DB::raw('LOWER(p.kategori)'), 'NOT LIKE', '%woman%') // Penting!
            ->groupBy('p.id_produk', 'p.nama_produk') // Group by ID juga biar aman
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 2. Query Top Produk Wanita
        $resultWoman = DB::table('order_items as od')
            ->join('produk_looksee as p', 'od.id_produk', '=', 'p.id_produk')
            ->select('p.nama_produk', DB::raw('SUM(od.quantity) as total_qty'))
            ->where(DB::raw('LOWER(p.kategori)'), 'LIKE', '%woman%')
            ->groupBy('p.id_produk', 'p.nama_produk')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Masukkan data ke array temporary
        foreach ($resultMan as $row) {
            $trendingProductsManData[$row->nama_produk] = (int) $row->total_qty;
            $allTrendingLabels[] = $row->nama_produk;
        }
        foreach ($resultWoman as $row) {
            $trendingProductsWomanData[$row->nama_produk] = (int) $row->total_qty;
            $allTrendingLabels[] = $row->nama_produk;
        }

        // Merapikan Label (Hapus duplikat & re-index)
        $allTrendingLabelsUnique = array_values(array_unique($allTrendingLabels));

        // Mapping ulang data agar sesuai urutan Label Unik
        // Jika produk Man A ada di label index 0, maka array man data index 0 harus ada isinya
        $finalManData = [];
        $finalWomanData = [];

        foreach ($allTrendingLabelsUnique as $label) {
            $finalManData[]   = $trendingProductsManData[$label] ?? 0;
            $finalWomanData[] = $trendingProductsWomanData[$label] ?? 0;
        }

        // =========================================================================
        // RETURN VIEW
        // =========================================================================
        return view('admin.analyticsAdmin.analyticsAdmin', [
            'chartDoughnutDataValues'       => $chartDoughnutDataValues,
            'chartDoughnutColorsFromBadges' => $chartDoughnutColorsFromBadges,
            'pendingCount'   => $pendingCount,
            'preparedCount'  => $preparedCount,
            'shippedCount'   => $shippedCount,
            'completedCount' => $completedCount,
            
            'monthlySalesLabels' => $monthlySalesLabels,
            'monthlySalesData'   => $monthlySalesData,
            
            'weeklyAccessLabels' => $weeklyAccessLabels,
            'weeklyAccessData'   => $weeklyAccessData,
            'totalWeeklyVisitors' => $totalWeeklyVisitors,
            'weeklyAccessPercentageChange' => $weeklyAccessPercentageChange,
            'weeklyAccessPercentageDirection' => $weeklyAccessPercentageDirection,
            
            'trendingPostsLabels'     => $trendingPostsLabels,
            'trendingPostsData'       => $trendingPostsData,
            'totalLikesTrendingPosts' => $totalLikesTrendingPosts,
            'colorsTrendingPosts'     => $colorsTrendingPosts,
            
            'allTrendingLabelsUnique'        => $allTrendingLabelsUnique,
            'chartTrendingProductsManValues' => $finalManData,
            'chartTrendingProductsWomanValues' => $finalWomanData
        ]);
    }
}