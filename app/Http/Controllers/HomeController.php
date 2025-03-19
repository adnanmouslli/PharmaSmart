<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Prescription;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // الإحصائيات
        $activeOrders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $pendingPrescriptions = Prescription::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'under_review'])
            ->count();

        $completedOrders = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        // الطلبات الأخيرة
        $recentOrders = Order::where('user_id', Auth::id())
            ->with('items.medication')
            ->latest()
            ->take(5)
            ->get();

        // الوصفات الطبية الأخيرة مع الأدوية المعتمدة
        $recentPrescriptions = Prescription::where('user_id', Auth::id())
            ->with(['medications' => function($query) {
                $query->where('prescription_medications.status', 'approved');
            }])
            ->latest()
            ->take(5)
            ->get();

        // جمع الأدوية المعتمدة من الوصفات
        $approvedPrescriptionMeds = collect();
        
        $approvedPrescriptions = Prescription::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with(['medications' => function($query) {
                $query->where('medications.is_active', true)
                    ->where('medications.stock', '>', 0)
                    ->where('prescription_medications.status', 'approved')
                    ->select([
                        'medications.*',
                        'prescription_medications.quantity as prescribed_quantity',
                        'prescription_medications.dosage_instructions',
                        'prescription_medications.status as prescription_status'
                    ]);
            }])
            ->get();

        foreach ($approvedPrescriptions as $prescription) {
            foreach ($prescription->medications as $medication) {
                $medication->prescription_data = [
                    'prescription_id' => $prescription->id,
                    'prescription_number' => $prescription->prescription_number,
                    'prescribed_quantity' => $medication->prescribed_quantity,
                    'dosage_instructions' => $medication->dosage_instructions
                ];
                $approvedPrescriptionMeds->push($medication);
            }
        }

        // الأدوية المتوفرة حديثاً
        $recentMedications = Medication::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // تحديد الأدوية التي لديها وصفات معتمدة
        $recentMedications->each(function($medication) use ($approvedPrescriptionMeds) {
            $medication->approved_prescription = $approvedPrescriptionMeds->firstWhere('id', $medication->id);
        });

        return view('users.home', compact(
            'activeOrders',
            'pendingPrescriptions',
            'completedOrders',
            'recentOrders',
            'recentPrescriptions',
            'recentMedications',
            'approvedPrescriptionMeds'
        ));
    }

    public function getStats()
    {
        $stats = [
            'activeOrders' => Order::where('user_id', Auth::user()->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'pendingPrescriptions' => Prescription::where('user_id', Auth::user()->id)
                ->whereIn('status', ['pending', 'under_review'])
                ->count(),
            'completedOrders' => Order::where('user_id', Auth::user()->id)
                ->where('status', 'completed')
                ->count()
        ];

        return response()->json($stats);
    }
}