<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPrescriptionController extends Controller
{
    /**
     * عرض قائمة الوصفات الطبية في لوحة التحكم
     */
    public function index(Request $request)
    {
        $query = Prescription::with('user');
        
        // تطبيق البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('prescription_number', 'like', "%{$search}%")
                  ->orWhere('doctor_name', 'like', "%{$search}%")
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
        $prescriptions = $query->paginate($request->input('per_page', 10));
        
        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    /**
     * عرض تفاصيل وصفة طبية محددة
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['user', 'medications', 'reviewer']);
        
        return view('admin.prescriptions.show', compact('prescription'));
    }

    /**
     * تحديث حالة الوصفة الطبية
     */
    public function updateStatus(Request $request, Prescription $prescription, $status)
    {
        $validStatuses = ['pending', 'under_review', 'approved', 'partially_approved', 'rejected'];
        
        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'حالة الوصفة غير صحيحة');
        }
        
        $oldStatus = $prescription->status;
        $prescription->status = $status;

        // في حالة الرفض، تخزين سبب الرفض
        if ($status === 'rejected' && $request->has('rejection_reason')) {
            $prescription->rejection_reason = $request->rejection_reason;
        }
        
        // في حالة الموافقة، تسجيل المراجع والوقت
        if (in_array($status, ['approved', 'partially_approved', 'rejected'])) {
            $prescription->reviewed_by = Auth::id();
            $prescription->reviewed_at = now();
        }
        
        // حفظ التغييرات
        $prescription->save();
        
     
        
        $statusMessages = [
            'under_review' => 'تم تحويل الوصفة للمراجعة',
            'approved' => 'تمت الموافقة على الوصفة بنجاح',
            'partially_approved' => 'تمت الموافقة الجزئية على الوصفة بنجاح',
            'rejected' => 'تم رفض الوصفة'
        ];
        
        return redirect()->back()->with('success', $statusMessages[$status] ?? 'تم تحديث حالة الوصفة بنجاح');
    }

    /**
     * الموافقة على دواء في الوصفة
     */
    public function approveMedication(Prescription $prescription, PrescriptionMedication $medication)
    {
        // التأكد من أن الوصفة قيد المراجعة وأن الدواء ينتمي لها
        if ($prescription->status !== 'under_review' || $medication->prescription_id !== $prescription->id) {
            return redirect()->back()->with('error', 'لا يمكن تعديل هذا الدواء');
        }
        
        $medication->status = 'approved';
        $medication->save();

        
        return redirect()->back()->with('success', 'تمت الموافقة على الدواء بنجاح');
    }

    /**
     * رفض دواء في الوصفة
     */
    public function rejectMedication(Prescription $prescription, PrescriptionMedication $medication)
    {
        // التأكد من أن الوصفة قيد المراجعة وأن الدواء ينتمي لها
        if ($prescription->status !== 'under_review' || $medication->prescription_id !== $prescription->id) {
            return redirect()->back()->with('error', 'لا يمكن تعديل هذا الدواء');
        }
        
        $medication->status = 'rejected';
        $medication->save();

        
        return redirect()->back()->with('success', 'تم رفض الدواء بنجاح');
    }

    /**
     * الموافقة على جميع الأدوية في الوصفة
     */
    public function approveAllMedications(Prescription $prescription)
    {
        // التأكد من أن الوصفة قيد المراجعة
        if ($prescription->status !== 'under_review') {
            return response()->json(['success' => false, 'message' => 'لا يمكن تعديل هذه الوصفة'], 400);
        }
        
        // تحديث جميع الأدوية
        $prescription->medications()->update(['status' => 'approved']);
        
     
        
        return response()->json(['success' => true, 'message' => 'تمت الموافقة على جميع الأدوية بنجاح']);
    }

    /**
     * إكمال مراجعة الوصفة
     */
    public function completeReview(Request $request, Prescription $prescription)
    {
        // التأكد من أن الوصفة قيد المراجعة
        if ($prescription->status !== 'under_review') {
            return redirect()->back()->with('error', 'لا يمكن إكمال مراجعة هذه الوصفة');
        }
        
        // تحديد الحالة النهائية بناءً على حالة الأدوية
        $prescription->load('medications');
        
        $approvedCount = $prescription->medications->where('status', 'approved')->count();
        $rejectedCount = $prescription->medications->where('status', 'rejected')->count();
        $totalCount = $prescription->medications->count();
        
        if ($approvedCount === 0) {
            $status = 'rejected';
            $prescription->rejection_reason = $request->review_notes ?? 'جميع الأدوية مرفوضة';
        } elseif ($approvedCount === $totalCount) {
            $status = 'approved';
        } else {
            $status = 'partially_approved';
        }
        
        // تحديث حالة الوصفة
        $prescription->status = $status;
        $prescription->reviewed_by = Auth::id();
        $prescription->reviewed_at = now();
        $prescription->notes = $request->review_notes;
        $prescription->save();
        
    
        
        $statusMessages = [
            'approved' => 'تمت الموافقة على الوصفة بنجاح',
            'partially_approved' => 'تمت الموافقة الجزئية على الوصفة بنجاح',
            'rejected' => 'تم رفض الوصفة'
        ];
        
        return redirect()->back()->with('success', $statusMessages[$status] ?? 'تم إكمال مراجعة الوصفة بنجاح');
    }
}