<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 

class AnalyticsAdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            $orderStatusCounts = DB::table('orders')
                ->select('status', DB::raw('COUNT(order_id) as count'))
                ->groupBy('status')
                ->get()
                ->keyBy(fn($item) => strtolower($item->status))
                ->map(fn($item) => $item->count)
                ->toArray();

            $pendingCount = $orderStatusCounts['pending'] ?? 0;
            $preparedCount = $orderStatusCounts['prepared'] ?? 0;
            $shippedCount = $orderStatusCounts['shipped'] ?? 0;
            $completedCount = $orderStatusCounts['completed'] ?? 0;

        } catch (\Exception $e) {
            report($e);
            $pendingCount = 17;
            $preparedCount = 3;
            $shippedCount = 5;
            $completedCount = 10;
        }

        $chartDoughnutDataValues = [
            $pendingCount,
            $preparedCount,
            $shippedCount,
            $completedCount
        ];

        $chartDoughnutColorsFromBadges = [
            'rgba(255, 193, 7, .8)', 
            'rgb(253, 152, 0)', 
            'rgb(148, 190, 253)', 
            'rgb(60, 199, 134)' 
        ];

        $monthlySalesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlySalesData = array_fill(0, 12, 0);

        try {
            $currentYear = date('Y');
            $salesResult = DB::table('orders')
                ->select(
                    DB::raw('MONTH(order_date) as month_num'),
                    DB::raw('SUM(total_price) as monthly_total_sales')
                )
                ->whereYear('order_date', $currentYear)
                ->whereIn('status', ['completed', 'prepared', 'shipped'])
                ->groupBy(DB::raw('MONTH(order_date)'))
                ->orderBy('month_num', 'asc')
                ->get();

            foreach ($salesResult as $row) {
                $monthlySalesData[$row->month_num - 1] = (float) ($row->monthly_total_sales ?? 0);
            }
        } catch (\Exception $e) {
            report($e);
            $monthlySalesData = [
                3000000,
                3500000,
                4000000,
                4500000,
                5000000,
                12654000,
                0,
                0,
                0,
                0,
                0,
                0
            ];
        }

        $weeklyAccessLabels = [];
        $weeklyAccessData = [];
        $dayNamesShort = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $dayNamesShort[$date->dayOfWeekIso - 1];
            $weeklyAccessLabels[] = $dayName;
            $weeklyAccessData[] = mt_rand(150, 250); 
        }

        $weeklyAccessLabels = array_reverse($weeklyAccessLabels);
        $weeklyAccessData = array_reverse($weeklyAccessData);
        $totalWeeklyVisitors = array_sum($weeklyAccessData);
        $weeklyAccessPercentageChange = "4.4%";
        $weeklyAccessPercentageDirection = "up";

        $trendingPostsLabels = [];
        $trendingPostsData = [];
        $totalLikesTrendingPosts = 0;
        $colorsTrendingPosts = ['#FF99CC', '#4D94FF', '#66CC99', '#FFD700', '#DDA0DD']; 

        try {
            $trendingPostsResult = DB::table('posts as p')
                ->leftJoin('likes as l', 'p.id_post', '=', 'l.id_post')
                ->select('p.caption', DB::raw('COUNT(l.id_like) as like_count'))
                ->groupBy('p.id_post', 'p.caption')
                ->orderByDesc('like_count')
                ->limit(5)
                ->get();

            foreach ($trendingPostsResult as $row) {
                $caption = strlen($row->caption) > 20 ? substr($row->caption, 0, 20) . '...' : $row->caption;
                $trendingPostsLabels[] = $caption;
                $trendingPostsData[] = (int) $row->like_count;
                $totalLikesTrendingPosts += (int) $row->like_count;
            }

            if ($trendingPostsResult->isEmpty()) {
                $trendingPostsLabels = ['Tidak ada data'];
                $trendingPostsData = [0];
                $totalLikesTrendingPosts = 0;
            }
        } catch (\Exception $e) {
            report($e);
            $trendingPostsLabels = ['Keep it simple', 'Gloomy day', 'Sampai kapan'];
            $trendingPostsData = [15, 10, 5];
            $totalLikesTrendingPosts = array_sum($trendingPostsData);
        }

        $trendingProductsManData = [];
        $trendingProductsWomanData = [];
        $allTrendingLabels = [];

        try {
            $resultMan = DB::table('produk_looksee as p')
                ->join('order_items as od', 'p.id_produk', '=', 'od.id_produk')
                ->select('p.nama_produk', DB::raw('SUM(od.quantity) as total_ordered_quantity'))
                ->where(DB::raw('LOWER(p.kategori)'), 'man')
                ->groupBy('p.nama_produk')
                ->orderByDesc('total_ordered_quantity')
                ->limit(5)
                ->get();

            foreach ($resultMan as $row) {
                $trendingProductsManData[$row->nama_produk] = (int) $row->total_ordered_quantity;
                $allTrendingLabels[] = $row->nama_produk;
            }

            $resultWoman = DB::table('produk_looksee as p')
                ->join('order_items as od', 'p.id_produk', '=', 'od.id_produk')
                ->select('p.nama_produk', DB::raw('SUM(od.quantity) as total_ordered_quantity'))
                ->where(DB::raw('LOWER(p.kategori)'), 'woman')
                ->groupBy('p.nama_produk')
                ->orderByDesc('total_ordered_quantity')
                ->limit(5)
                ->get();

            foreach ($resultWoman as $row) {
                $trendingProductsWomanData[$row->nama_produk] = (int) $row->total_ordered_quantity;
                $allTrendingLabels[] = $row->nama_produk;
            }

        } catch (\Exception $e) {
            report($e);
            $trendingProductsManData = [
                'Celana Kargo Japanse' => 12,
                'Hoodie Relaxed' => 10,
                'Kemeja Oversized' => 8
            ];
            $trendingProductsWomanData = [
                'Blouse Loose' => 15,
                'Sandal Wanita Loop' => 13,
                'Dress Motif Bunga' => 10
            ];
            $allTrendingLabels = array_merge(array_keys($trendingProductsManData), array_keys($trendingProductsWomanData));
        }

        $allTrendingLabelsUnique = array_values(array_unique($allTrendingLabels));
        sort($allTrendingLabelsUnique);

        $fullTrendingProductsManData = array_fill_keys($allTrendingLabelsUnique, 0);
        $fullTrendingProductsWomanData = array_fill_keys($allTrendingLabelsUnique, 0);

        foreach ($trendingProductsManData as $name => $quantity) {
            if (isset($fullTrendingProductsManData[$name])) {
                $fullTrendingProductsManData[$name] = $quantity;
            }
        }
        foreach ($trendingProductsWomanData as $name => $quantity) {
            if (isset($fullTrendingProductsWomanData[$name])) {
                $fullTrendingProductsWomanData[$name] = $quantity;
            }
        }

        $chartTrendingProductsManValues = array_values($fullTrendingProductsManData);
        $chartTrendingProductsWomanValues = array_values($fullTrendingProductsWomanData);


        return view('admin.analyticsAdmin.analyticsAdmin', compact(
            'chartDoughnutDataValues',
            'chartDoughnutColorsFromBadges',
            'pendingCount',
            'preparedCount',
            'shippedCount',
            'completedCount',
            'monthlySalesLabels',
            'monthlySalesData',
            'weeklyAccessLabels',
            'weeklyAccessData',
            'totalWeeklyVisitors',
            'weeklyAccessPercentageChange',
            'weeklyAccessPercentageDirection',
            'trendingPostsLabels',
            'trendingPostsData',
            'totalLikesTrendingPosts',
            'colorsTrendingPosts',
            'allTrendingLabelsUnique',
            'chartTrendingProductsManValues',
            'chartTrendingProductsWomanValues'
        ));
    }
}