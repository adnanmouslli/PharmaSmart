<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class AdminOrderController extends Controller
{
    /**
     * عرض قائمة الطلبات في لوحة التحكم
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // تطبيق البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }
        
        // تطبيق الفلتر حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // الترتيب
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        // التصفح
        $orders = $query->paginate($request->input('per_page', 10));
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.medication', 'prescription']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order, $status)
    {
        if (!in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'حالة الطلب غير صحيحة');
        }
        
        $oldStatus = $order->status;
        $order->status = $status;
        $order->save();
        
        
        
        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * طباعة تفاصيل الطلب
     */
    public function print(Order $order)
    {
        // $order->load(['user', 'items.medication']);
        
        // $pdf = PDF::loadView('admin.orders.print', compact('order'));
        
        // return $pdf->stream('order-'.$order->order_number.'.pdf');
    }

    /**
     * إضافة ملاحظة إلى الطلب
     */
    public function addNote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);
        
        $order->admin_notes = $validated['admin_notes'];
        $order->save();
        
        
        
        return redirect()->back()->with('success', 'تم إضافة الملاحظة بنجاح');
    }

    /**
     * الحصول على تفاصيل الطلب للعرض في النافذة المنبثقة
     */
    public function getDetails(Order $order)
    {
        $order->load(['user', 'items.medication']);
        
        return view('admin.orders.details', compact('order'));
    }

    /**
     * إنشاء طلب من وصفة طبية
     */
    public function createFromPrescription(Prescription $prescription)
    {
        if (!in_array($prescription->status, ['approved', 'partially_approved'])) {
            return redirect()->back()->with('error', 'لا يمكن إنشاء طلب من وصفة غير معتمدة');
        }
        
        $prescription->load(['user', 'medications' => function($query) {
            $query->where('status', 'approved');
        }, 'medications.medication']);
        
        return view('admin.orders.create-from-prescription', compact('prescription'));
    }

    /**
     * تصدير بيانات الطلبات
     */
    public function export()
    {
        $fileName = 'orders_' . date('Y-m-d') . '.csv';
        
        $orders = Order::with(['user', 'items'])->get();
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        
        $columns = [
            'رقم الطلب',
            'العميل',
            'البريد الإلكتروني',
            'المبلغ الإجمالي',
            'الحالة',
            'تاريخ الطلب',
            'عدد المنتجات',
        ];
        
        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($orders as $order) {
                $row = [
                    $order->order_number,
                    $order->user->first_name . ' ' . $order->user->last_name,
                    $order->user->email,
                    $order->total_amount,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->items->count(),
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}