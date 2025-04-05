<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMedicationController extends Controller
{
    /**
     * عرض قائمة الأدوية في لوحة التحكم
     */
    public function index(Request $request)
    {
        $query = Medication::with('category');
        
        // تطبيق البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // تطبيق الفلتر حسب القسم
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // تطبيق الفلتر حسب الحالة
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // تطبيق الفلتر حسب الوصفة الطبية
        if ($request->has('prescription')) {
            $query->where('requires_prescription', $request->prescription === 'required');
        }
        
        // تطبيق الفلتر حسب المخزون
        if ($request->has('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<', 10);
            } elseif ($request->stock === 'out') {
                $query->where('stock', 0);
            }
        }
        
        // الترتيب
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);
        
        // التصفح
        $medications = $query->paginate($request->input('per_page', 10));
        $categories = Category::all();
        
        return view('admin.medications.index', compact('medications', 'categories'));
    }

    /**
     * عرض نموذج إضافة دواء جديد
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.medications.create', compact('categories'));
    }

    /**
     * تخزين دواء جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:255',
            'dosage_form' => 'required|string|max:255',
            'strength' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // معالجة الحقول المنطقية من مربعات الاختيار
        $validated['requires_prescription'] = $request->has('requires_prescription');
        $validated['is_active'] = $request->has('is_active');
        
        // رفع الصورة إذا وجدت
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medications', 'public');
            $validated['image'] = $imagePath;
        }
        
        $medication = Medication::create($validated);
        
        
        return redirect()->route('admin.medications.index')
            ->with('success', 'تم إضافة الدواء بنجاح');
    }

    /**
     * عرض تفاصيل دواء محدد
     */
    public function show(Medication $medication)
    {
        $medication->load('category');
        
        return view('admin.medications.show', compact('medication'));
    }

    /**
     * عرض نموذج تعديل دواء
     */
    public function edit(Medication $medication)
    {
        $categories = Category::all();
        return view('admin.medications.edit', compact('medication', 'categories'));
    }

    /**
     * تحديث دواء موجود
     */
    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:255',
            'dosage_form' => 'required|string|max:255',
            'strength' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // معالجة الحقول المنطقية من مربعات الاختيار
        $validated['requires_prescription'] = $request->has('requires_prescription');
        $validated['is_active'] = $request->has('is_active');
        
        // رفع الصورة الجديدة إذا وجدت
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($medication->image) {
                Storage::disk('public')->delete($medication->image);
            }
            
            $imagePath = $request->file('image')->store('medications', 'public');
            $validated['image'] = $imagePath;
        }
        
        $medication->update($validated);
        

        
        return redirect()->route('admin.medications.show', $medication)
            ->with('success', 'تم تحديث الدواء بنجاح');
    }

    /**
     * حذف دواء
     */
    public function destroy(Medication $medication)
    {
        try {
            // حذف الصورة إذا وجدت
            if ($medication->image) {
                Storage::disk('public')->delete($medication->image);
            }
            
            $medication->delete();
            
         
            
            return redirect()->route('admin.medications.index')
                ->with('success', 'تم حذف الدواء بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.medications.index')
                ->with('error', 'لا يمكن حذف الدواء نظراً لوجود بيانات مرتبطة به');
        }
    }
    
    /**
     * تحديث مخزون الدواء
     */
    public function updateStock(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);
        
        $oldStock = $medication->stock;
        $medication->stock = $validated['stock'];
        $medication->save();
        

        
        return redirect()->back()
            ->with('success', 'تم تحديث المخزون بنجاح');
    }
    
    /**
     * تبديل حالة الدواء (نشط/غير نشط)
     */
    public function toggleActive(Medication $medication)
    {
        $medication->is_active = !$medication->is_active;
        $medication->save();
        
        $status = $medication->is_active ? 'تفعيل' : 'تعطيل';
        
        
        return redirect()->back()
            ->with('success', "تم {$status} الدواء بنجاح");
    }
    
    /**
     * تصدير بيانات الأدوية
     */
    public function export()
    {
        $fileName = 'medications_' . date('Y-m-d') . '.csv';
        
        $medications = Medication::with('category')->get();
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        
        $columns = [
            'رقم التعريف',
            'اسم الدواء',
            'القسم',
            'السعر',
            'المخزون',
            'شكل الجرعة',
            'التركيز',
            'الشركة المصنعة',
            'يتطلب وصفة طبية',
            'نشط',
            'تاريخ الإضافة',
        ];
        
        $callback = function() use($medications, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($medications as $medication) {
                $row = [
                    $medication->id,
                    $medication->name,
                    $medication->category->name,
                    $medication->price,
                    $medication->stock,
                    $medication->dosage_form,
                    $medication->strength,
                    $medication->manufacturer,
                    $medication->requires_prescription ? 'نعم' : 'لا',
                    $medication->is_active ? 'نعم' : 'لا',
                    $medication->created_at->format('Y-m-d'),
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}