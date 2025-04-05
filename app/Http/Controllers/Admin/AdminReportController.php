<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للتقارير
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * تقرير المبيعات
     */
    public function sales(Request $request)
    {
        // معالجة التواريخ
        $startDate = $request->input('start_date') ? new \DateTime($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? new \DateTime($request->input('end_date')) : now()->endOfMonth();
        
        // التجميع حسب
        $groupBy = $request->input('group_by', 'day');
        
        $query = Order::whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
        
        // تجميع البيانات حسب الاختيار
        if ($groupBy === 'day') {
            $salesData = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
            $labels = $salesData->pluck('date')->map(function($date) {
                return date('Y-m-d', strtotime($date));
            });
        } elseif ($groupBy === 'month') {
            $salesData = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
            $labels = $salesData->map(function($item) {
                return date('Y-m', strtotime("{$item->year}-{$item->month}-01"));
            });
        } else { // year
            $salesData = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_amount) as total')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
            $labels = $salesData->pluck('year');
        }
        
        // تجهيز بيانات الرسم البياني
        $counts = $salesData->pluck('count');
        $totals = $salesData->pluck('total');
        
        // إجمالي المبيعات
        $totalSales = $query->sum('total_amount');
        $totalOrders = $query->count();
        
        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'groupBy',
            'salesData',
            'labels',
            'counts',
            'totals',
            'totalSales',
            'totalOrders'
        ));
    }

    /**
     * تقرير الأدوية
     */
    public function medications(Request $request)
    {
        // معالجة التواريخ
        $startDate = $request->input('start_date') ? new \DateTime($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? new \DateTime($request->input('end_date')) : now()->endOfMonth();
        
        // الأدوية الأكثر مبيعًا
        $topMedications = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('medications', 'order_items.medication_id', '=', 'medications.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->select(
                'medications.id',
                'medications.name',
                DB::raw('sum(order_items.quantity) as total_quantity'),
                DB::raw('sum(order_items.total_price) as total_sales')
            )
            ->groupBy('medications.id', 'medications.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        // الأدوية حسب الأقسام
        $medicationsByCategory = DB::table('medications')
            ->join('categories', 'medications.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                DB::raw('count(*) as count')
            )
            ->groupBy('categories.name')
            ->orderBy('count', 'desc')
            ->get();
        
        // حالة المخزون
        $stockStatus = [
            'out_of_stock' => Medication::where('stock', 0)->count(),
            'low_stock' => Medication::where('stock', '>', 0)->where('stock', '<', 10)->count(),
            'sufficient' => Medication::where('stock', '>=', 10)->count(),
        ];
        
        return view('admin.reports.medications', compact(
            'startDate',
            'endDate',
            'topMedications',
            'medicationsByCategory',
            'stockStatus'
        ));
    }

    /**
     * تقرير العملاء
     */
    public function customers(Request $request)
    {
        // معالجة التواريخ
        $startDate = $request->input('start_date') ? new \DateTime($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? new \DateTime($request->input('end_date')) : now()->endOfMonth();
        
        // العملاء الأكثر إنفاقًا
        $topCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('user')
            ->select('user_id', DB::raw('sum(total_amount) as total_spent'), DB::raw('count(*) as order_count'))
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
        
        // عدد العملاء الجدد
        $newCustomers = User::where('is_admin', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // نشاط العملاء
        $customerActivity = [
            'active' => User::where('is_admin', false)
                ->whereHas('orders', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->count(),
            'inactive' => User::where('is_admin', false)
                ->whereDoesntHave('orders', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->count(),
        ];
        
        return view('admin.reports.customers', compact(
            'startDate',
            'endDate',
            'topCustomers',
            'newCustomers',
            'customerActivity'
        ));
    }

    /**
     * تقرير الوصفات الطبية
     */
    public function prescriptions(Request $request)
    {
        // معالجة التواريخ
        $startDate = $request->input('start_date') ? new \DateTime($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? new \DateTime($request->input('end_date')) : now()->endOfMonth();
        
        // إحصائيات الوصفات
        $prescriptionStats = [
            'total' => Prescription::whereBetween('created_at', [$startDate, $endDate])->count(),
            'approved' => Prescription::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'approved')
                ->count(),
            'partially_approved' => Prescription::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'partially_approved')
                ->count(),
            'rejected' => Prescription::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'rejected')
                ->count(),
            'pending' => Prescription::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'under_review'])
                ->count(),
        ];
        
        // وقت المراجعة
        $reviewTime = Prescription::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('reviewed_at')
            ->select(
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours')
            )
            ->first();
        
        // الأدوية الأكثر طلبًا في الوصفات
        $topPrescriptionMedications = DB::table('prescription_medications')
            ->join('prescriptions', 'prescription_medications.prescription_id', '=', 'prescriptions.id')
            ->join('medications', 'prescription_medications.medication_id', '=', 'medications.id')
            ->whereBetween('prescriptions.created_at', [$startDate, $endDate])
            ->where('prescription_medications.status', 'approved')
            ->select(
                'medications.id',
                'medications.name',
                DB::raw('count(*) as count')
            )
            ->groupBy('medications.id', 'medications.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.prescriptions', compact(
            'startDate',
            'endDate',
            'prescriptionStats',
            'reviewTime',
            'topPrescriptionMedications'
        ));
    }

    /**
     * تصدير التقارير
     */
    public function export(Request $request, $type)
    {
        $startDate = $request->input('start_date') ? new \DateTime($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? new \DateTime($request->input('end_date')) : now()->endOfMonth();
        
        $fileName = $type . '_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        
        // تحديد نوع التقرير
        switch ($type) {
            case 'sales':
                return $this->exportSalesReport($startDate, $endDate, $headers);
            case 'medications':
                return $this->exportMedicationsReport($startDate, $endDate, $headers);
            case 'customers':
                return $this->exportCustomersReport($startDate, $endDate, $headers);
            case 'prescriptions':
                return $this->exportPrescriptionsReport($startDate, $endDate, $headers);
            default:
                return redirect()->back()->with('error', 'نوع التقرير غير صحيح');
        }
    }
    
    /**
     * تصدير تقرير المبيعات
     */
    private function exportSalesReport($startDate, $endDate, $headers)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'items.medication'])
            ->get();
        
        $columns = [
            'رقم الطلب',
            'العميل',
            'البريد الإلكتروني',
            'المبلغ الإجمالي',
            'الحالة',
            'تاريخ الطلب',
            'المنتجات',
        ];
        
        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($orders as $order) {
                $products = $order->items->map(function($item) {
                    return $item->medication->name . ' (x' . $item->quantity . ')';
                })->implode(', ');
                
                $row = [
                    $order->order_number,
                    $order->user->first_name . ' ' . $order->user->last_name,
                    $order->user->email,
                    $order->total_amount,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $products,
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * تصدير تقرير الأدوية
     */
    private function exportMedicationsReport($startDate, $endDate, $headers)
    {
        $medications = Medication::with('category')->get();
        
        $columns = [
            'اسم الدواء',
            'القسم',
            'السعر',
            'المخزون الحالي',
            'شكل الجرعة',
            'التركيز',
            'الشركة المصنعة',
            'يتطلب وصفة طبية',
            'نشط',
            'الكمية المباعة',
            'إجمالي المبيعات',
        ];
        
        $callback = function() use($medications, $columns, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($medications as $medication) {
                // حساب الكمية المباعة وإجمالي المبيعات للفترة المحددة
                $sales = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.medication_id', $medication->id)
                    ->where('orders.status', 'completed')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('sum(order_items.quantity) as total_quantity'),
                        DB::raw('sum(order_items.total_price) as total_sales')
                    )
                    ->first();
                
                $row = [
                    $medication->name,
                    $medication->category->name,
                    $medication->price,
                    $medication->stock,
                    $medication->dosage_form,
                    $medication->strength,
                    $medication->manufacturer,
                    $medication->requires_prescription ? 'نعم' : 'لا',
                    $medication->is_active ? 'نعم' : 'لا',
                    $sales->total_quantity ?? 0,
                    $sales->total_sales ?? 0,
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * تصدير تقرير العملاء
     */
    private function exportCustomersReport($startDate, $endDate, $headers)
    {
        $users = User::where('is_admin', false)->get();
        
        $columns = [
            'اسم العميل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'العنوان',
            'تاريخ التسجيل',
            'عدد الطلبات',
            'إجمالي الإنفاق',
            'متوسط قيمة الطلب',
            'آخر طلب',
        ];
        
        $callback = function() use($users, $columns, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($users as $user) {
                // إحصائيات الطلبات للفترة المحددة
                $orderStats = Order::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('count(*) as order_count'),
                        DB::raw('sum(total_amount) as total_spent'),
                        DB::raw('avg(total_amount) as avg_order'),
                        DB::raw('max(created_at) as last_order')
                    )
                    ->first();
                
                $row = [
                    $user->first_name . ' ' . $user->last_name,
                    $user->email,
                    $user->phone,
                    $user->address,
                    $user->created_at->format('Y-m-d'),
                    $orderStats->order_count ?? 0,
                    $orderStats->total_spent ?? 0,
                    $orderStats->avg_order ?? 0,
                    $orderStats->last_order ? date('Y-m-d', strtotime($orderStats->last_order)) : 'لا يوجد',
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * تصدير تقرير الوصفات الطبية
     */
    private function exportPrescriptionsReport($startDate, $endDate, $headers)
    {
        $prescriptions = Prescription::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'medications.medication', 'reviewer'])
            ->get();
        
        $columns = [
            'رقم الوصفة',
            'العميل',
            'البريد الإلكتروني',
            'اسم الطبيب',
            'المستشفى',
            'تاريخ الوصفة',
            'تاريخ الإضافة',
            'الحالة',
            'تاريخ المراجعة',
            'المراجع',
            'سبب الرفض',
            'الأدوية المطلوبة',
            'الأدوية الموافق عليها',
        ];
        
        $callback = function() use($prescriptions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($prescriptions as $prescription) {
                $requestedMeds = $prescription->medications->pluck('medication.name')->implode(', ');
                $approvedMeds = $prescription->medications->where('status', 'approved')
                    ->pluck('medication.name')->implode(', ');
                
                $row = [
                    $prescription->prescription_number,
                    $prescription->user->first_name . ' ' . $prescription->user->last_name,
                    $prescription->user->email,
                    $prescription->doctor_name,
                    $prescription->hospital_name ?? 'غير محدد',
                    $prescription->prescription_date->format('Y-m-d'),
                    $prescription->created_at->format('Y-m-d'),
                    $prescription->status,
                    $prescription->reviewed_at ? date('Y-m-d H:i', strtotime($prescription->reviewed_at)) : 'لم تتم المراجعة',
                    $prescription->reviewer ? ($prescription->reviewer->first_name . ' ' . $prescription->reviewer->last_name) : '-',
                    $prescription->rejection_reason ?? '-',
                    $requestedMeds,
                    $approvedMeds,
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}