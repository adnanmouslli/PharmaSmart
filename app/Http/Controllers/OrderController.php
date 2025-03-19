<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PrescriptionMedication;
use App\Models\Order;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{


    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.medication', 'prescription'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
{
    // جلب الأدوية العادية المتوفرة
    $regularMedications = Medication::where('is_active', true)
        ->where('requires_prescription', false)
        ->where('stock', '>', 0)
        ->with('category')
        ->get();

    // جلب الأدوية المعتمدة من الوصفات الطبية للمستخدم
    $approvedPrescriptionMeds = collect();
    
    $approvedPrescriptions = Prescription::where('user_id', Auth::id())
        ->where('status', 'approved')
        ->with(['medications' => function($query) {
            $query->where('medications.is_active', true)
                ->where('medications.stock', '>', 0)
                ->where('prescription_medications.status', 'approved');
        }])
        ->get();

    foreach ($approvedPrescriptions as $prescription) {
        foreach ($prescription->medications as $medication) {
            // إضافة معلومات الوصفة للدواء
            $medication->prescription_number = $prescription->prescription_number;
            $medication->prescription_id = $prescription->id;
            $medication->prescribed_quantity = $medication->pivot->quantity;
            $medication->dosage_instructions = $medication->pivot->dosage_instructions;
            
            $approvedPrescriptionMeds->push($medication);
        }
    }

    return view('orders.create', compact('regularMedications', 'approvedPrescriptionMeds'));
}

public function store(Request $request)
{
    // $request->validate([
    //     'medications' => 'required|array|min:1',
    //     'medications.*.quantity' => 'required|integer|min:1',
    //     'notes' => 'nullable|string|max:1000',
    // ]);

    try {
        DB::beginTransaction();

        // إنشاء رقم الطلبية
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);

        // إنشاء الطلبية
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'total_amount' => 0, // سيتم تحديثه لاحقاً
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        $totalAmount = 0;
        $prescriptionId = null;

        // معالجة كل دواء في الطلبية
        foreach ($request->medications as $medicationId => $details) {
            if (empty($details['quantity'])) continue;

            $medication = Medication::findOrFail($medicationId);
            $quantity = (int)$details['quantity'];

            // التحقق من المخزون
            if ($medication->stock < $quantity) {
                throw new \Exception("الكمية المطلوبة من {$medication->name} غير متوفرة في المخزون");
            }

            // التحقق من الوصفة الطبية إذا كان الدواء يتطلب وصفة
            if ($medication->requires_prescription) {
                if (empty($details['prescription_id'])) {
                    throw new \Exception("الدواء {$medication->name} يتطلب وصفة طبية");
                }

                $prescriptionMed = PrescriptionMedication::where('prescription_id', $details['prescription_id'])
                    ->where('medication_id', $medicationId)
                    ->whereHas('prescription', function($query) {
                        $query->where('user_id', Auth::id())
                              ->where('status', 'approved');
                    })
                    ->firstOrFail();

                // التحقق من الكمية المطلوبة مقارنة بالكمية المصرح بها
                if ($quantity > $prescriptionMed->quantity) {
                    throw new \Exception("الكمية المطلوبة من {$medication->name} تتجاوز الكمية المصرح بها في الوصفة");
                }

                // حفظ رقم الوصفة في الطلبية
                if (!$prescriptionId) {
                    $prescriptionId = $details['prescription_id'];
                    $order->update(['prescription_id' => $prescriptionId]);
                }
            }

            // إضافة الدواء للطلبية
            $itemTotal = $medication->price * $quantity;
            $order->items()->create([
                'medication_id' => $medicationId,
                'quantity' => $quantity,
                'unit_price' => $medication->price,
                'total_price' => $itemTotal,
                'status' => 'pending'
            ]);

            // تحديث المخزون
            $medication->decrement('stock', $quantity);
            
            $totalAmount += $itemTotal;
        }

        // تحديث إجمالي الطلبية
        $order->update(['total_amount' => $totalAmount]);

        DB::commit();

        return redirect()->route('orders.show', $order)
            ->with('success', 'تم إنشاء الطلبية بنجاح');

    } catch (\Exception $e) {
        DB::rollback();
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.medication']);
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء هذه الطلبية');
        }

        DB::transaction(function () use ($order) {
            // إعادة الكميات للمخزون
            foreach ($order->items as $item) {
                $item->medication->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'تم إلغاء الطلبية بنجاح');
    }

}