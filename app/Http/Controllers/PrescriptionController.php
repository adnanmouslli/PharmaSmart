<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::where('user_id', Auth::user()->id)
            ->with(['medications', 'reviewer'])
            ->latest()
            ->paginate(10);

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $medications = Medication::where('requires_prescription', true)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        return view('prescriptions.create', compact('medications'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'doctor_name' => 'required|string|max:255',
    //         'hospital_name' => 'nullable|string|max:255',
    //         'prescription_date' => 'required|date|before_or_equal:today',
    //         'image' => 'required|image|max:5048',
    //         'notes' => 'nullable|string',
    //         'medications' => 'sometimes|array',
    //         'medications.*.id' => 'required|exists:medications,id',
    //         'medications.*.quantity' => 'required|integer|min:1',
    //         'medications.*.dosage_instructions' => 'nullable|string'
    //     ]);

    //     // إنشاء رقم فريد للوصفة
    //     $prescriptionNumber = 'RX-' . date('Y') . '-' . Str::padLeft(mt_rand(1, 999999), 6, '0');

    //     // معالجة الصورة
    //     $imagePath = $request->file('image')->store('prescriptions', 'public');

    //     // إنشاء الوصفة
    //     $prescription = Prescription::create([
    //         'prescription_number' => $prescriptionNumber,
    //         'user_id' => Auth::user()->id,
    //         'doctor_name' => $validated['doctor_name'],
    //         'hospital_name' => $validated['hospital_name'],
    //         'prescription_date' => $validated['prescription_date'],
    //         'image' => $imagePath,
    //         'notes' => $validated['notes']
    //     ]);

    //     // إضافة الأدوية للوصفة
    //     if (isset($validated['medications'])) {
    //         foreach ($validated['medications'] as $med) {
    //             $prescription->medications()->attach($med['id'], [
    //                 'quantity' => $med['quantity'],
    //                 'dosage_instructions' => $med['dosage_instructions'] ?? null
    //             ]);
    //         }
    //     }

    //     return redirect()->route('prescriptions.show', $prescription)
    //         ->with('success', 'تم رفع الوصفة الطبية بنجاح!');
    // }


    public function store(Request $request)
    {
        // تحسين التحقق من صحة البيانات
        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'hospital_name' => 'nullable|string|max:255',
            'prescription_date' => 'required|date|before_or_equal:today',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
            'notes' => 'nullable|string',
            'medications' => 'required|array|min:1', // يجب اختيار دواء واحد على الأقل
            'medications.*.id' => 'required|exists:medications,id,requires_prescription,1,is_active,1',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.dosage_instructions' => 'nullable|string|max:255'
        ]);
    
        try {
            DB::beginTransaction();
            
            // إنشاء رقم فريد للوصفة
            $prescriptionNumber = 'RX-' . date('Y') . '-' . 
                Str::padLeft(Prescription::whereYear('created_at', date('Y'))->count() + 1, 6, '0');
    
            // معالجة الصورة
            $imagePath = $request->file('image')->store('prescriptions/' . date('Y/m'), 'public');
    
            // إنشاء الوصفة
            $prescription = Prescription::create([
                'prescription_number' => $prescriptionNumber,
                'user_id' => Auth::id(),
                'doctor_name' => $validated['doctor_name'],
                'hospital_name' => $validated['hospital_name'],
                'prescription_date' => $validated['prescription_date'],
                'image' => $imagePath,
                'notes' => $validated['notes']
            ]);
    
            // إضافة الأدوية للوصفة مع التحقق من توفرها
            foreach ($validated['medications'] as $med) {
                $medication = Medication::findOrFail($med['id']);
                
                // التحقق من توفر الكمية المطلوبة
                if ($medication->stock < $med['quantity']) {
                    throw new \Exception("الكمية المطلوبة من {$medication->name} غير متوفرة");
                }
    
                $prescription->medications()->attach($med['id'], [
                    'quantity' => $med['quantity'],
                    'dosage_instructions' => $med['dosage_instructions'] ?? null
                ]);
            }
    
            DB::commit();
    
            return redirect()->route('prescriptions.show', $prescription)
                ->with('success', 'تم رفع الوصفة الطبية بنجاح!');
    
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء رفع الوصفة: ' . $e->getMessage());
        }
    }


    public function show(Prescription $prescription)
    {
        // التحقق من ملكية الوصفة
        if ($prescription->user_id !== Auth::user()->id) {
            abort(403, 'غير مصرح بالوصول');
        }

        $prescription->load(['medications', 'reviewer']);

        return view('prescriptions.show', compact('prescription'));
    }

    public function destroy(Prescription $prescription)
    {
        // التحقق من ملكية الوصفة وحالتها
        if ($prescription->user_id !== Auth::user()->id) {
            abort(403, 'غير مصرح بالوصول');
        }

        if ($prescription->status !== 'pending') {
            return back()->with('error', 'لا يمكن حذف الوصفة بعد بدء مراجعتها');
        }

        $prescription->delete();

        return redirect()->route('prescriptions.index')
            ->with('success', 'تم حذف الوصفة الطبية بنجاح');
    }
}