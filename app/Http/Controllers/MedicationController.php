<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
{
    $query = Medication::query()->with(['category', 'prescriptions'])->where('is_active', true);

    // تصفية حسب الفئة
    if ($categoryId = $request->input('category')) {
        $query->where('category_id', $categoryId);
    }

    // البحث
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('manufacturer', 'like', "%{$search}%");
        });
    }

    // فلتر الوصفة الطبية
    if ($request->has('prescription')) {
        $query->where('requires_prescription', $request->boolean('prescription'));
    }

    // التوفر
    if ($request->has('in_stock')) {
        $query->where('stock', '>', 0);
    }

    $medications = $query->latest()->paginate(12);
    $categories = Category::all();

    return view('medications.index', compact('medications', 'categories'));
}


public function show(Medication $medication)
{
    $relatedMedications = Medication::where('category_id', $medication->category_id)
        ->where('id', '!=', $medication->id)
        ->where('is_active', true)
        ->limit(4)
        ->get();

    return view('medications.show', compact('medication', 'relatedMedications'));
}

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        return Medication::where('requires_prescription', true)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'strength', 'dosage_form')
            ->take(10)
            ->get();
    }

}