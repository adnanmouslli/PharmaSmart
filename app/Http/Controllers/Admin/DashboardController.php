<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات السريعة
        $stats = [
            'total_orders' => Order::count(),
            'pending_prescriptions' => Prescription::where('status', 'pending')->count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'orders_increase' => $this->calculateIncrease('orders'),
            'customers_increase' => $this->calculateIncrease('customers'),
            'revenue_increase' => $this->calculateIncrease('revenue'),
        ];

        // آخر الطلبات
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // الوصفات المعلقة
        $pendingPrescriptions = Prescription::with('user')->where('status', 'pending')->latest()->take(5)->get();

        // الأدوية منخفضة المخزون
        $lowStockMedications = Medication::where('stock', '<', 10)->where('is_active', true)->take(5)->get();

        // بيانات الرسم البياني
        $chartData = $this->getChartData();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentOrders',
            'pendingPrescriptions',
            'lowStockMedications',
            'chartData'
        ));
    }

    private function calculateIncrease($type)
    {
        $now = now();
        $currentMonth = $now->month;
        $lastMonth = $now->copy()->subMonth()->month;
        
        if ($type === 'orders') {
            $current = Order::whereMonth('created_at', $currentMonth)->count();
            $last = Order::whereMonth('created_at', $lastMonth)->count();
        } elseif ($type === 'customers') {
            $current = User::where('is_admin', false)->whereMonth('created_at', $currentMonth)->count();
            $last = User::where('is_admin', false)->whereMonth('created_at', $lastMonth)->count();
        } elseif ($type === 'revenue') {
            $current = Order::where('status', 'completed')->whereMonth('created_at', $currentMonth)->sum('total_amount');
            $last = Order::where('status', 'completed')->whereMonth('created_at', $lastMonth)->sum('total_amount');
        }
        
        if ($last == 0) {
            return 100; // إذا كان الشهر السابق صفر، نعتبر الزيادة 100%
        }
        
        return round((($current - $last) / $last) * 100);
    }

    private function getChartData()
    {
        $months = collect([]);
        $data = collect([]);
        
        // الحصول على بيانات المبيعات للأشهر الستة الماضية
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->translatedFormat('F');
            
            $months->push($monthName);
            
            $revenue = Order::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
            
            $data->push($revenue);
        }
        
        return [
            'labels' => $months,
            'data' => $data,
        ];
    }
}