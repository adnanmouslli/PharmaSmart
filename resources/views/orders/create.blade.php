@extends('layout.user')

@section('title', 'إنشاء طلبية جديدة')

@section('content')
<div class="container mx-auto px-4 py-8 rtl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">إنشاء طلبية جديدة</h1>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- التبويبات -->
        <div class="mb-6 bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button type="button" 
                            onclick="switchTab('regular')" 
                            id="regular-tab"
                            class="tab-btn active w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        الأدوية العادية
                    </button>
                    <button type="button" 
                            onclick="switchTab('prescription')" 
                            id="prescription-tab"
                            class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        الأدوية بوصفة طبية
                    </button>
                </nav>
            </div>

            <!-- قسم الأدوية العادية -->
            <div id="regular-section" class="tab-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($regularMedications as $medication)
                    <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                        @if($medication->image)
                            <img src="{{ asset('storage/' . $medication->image) }}" 
                                 alt="{{ $medication->name }}" 
                                 class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                                <span class="text-gray-400">لا توجد صورة</span>
                            </div>
                        @endif
                        
                        <h3 class="text-lg font-semibold mb-2">{{ $medication->name }}</h3>
                        @if($medication->strength)
                            <p class="text-gray-600 text-sm mb-1">{{ $medication->strength }}</p>
                        @endif
                        <p class="text-gray-600 text-sm mb-2">{{ $medication->dosage_form }}</p>
                        <p class="text-green-600 font-bold mb-3">{{ $medication->price }} ريال</p>
                        
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <input type="number" 
                                   name="medications[{{ $medication->id }}][quantity]" 
                                   class="form-input w-20 text-center" 
                                   min="0" 
                                   value="0"
                                   onchange="updateTotal()">
                            <span class="text-gray-600">الكمية</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- قسم الأدوية بوصفة -->
            <div id="prescription-section" class="tab-content hidden p-6">
                @if($approvedPrescriptionMeds->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        لا توجد أدوية معتمدة بوصفة طبية
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($approvedPrescriptionMeds as $medication)
                        <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                            @if($medication->image)
                                <img src="{{ asset('storage/' . $medication->image) }}" 
                                     alt="{{ $medication->name }}" 
                                     class="w-full h-40 object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-40 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                                    <span class="text-gray-400">لا توجد صورة</span>
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-semibold mb-2">{{ $medication->name }}</h3>
                            <p class="text-sm text-gray-600 mb-1">{{ $medication->strength }}</p>
                            <p class="text-sm text-gray-600 mb-1">{{ $medication->dosage_form }}</p>
                            <p class="text-green-600 font-bold mb-2">{{ $medication->price }} ريال</p>
                            
                            <div class="text-sm text-gray-600 mb-3">
                                <p>رقم الوصفة: {{ $medication->prescription_number }}</p>
                                <p>الكمية المصرح بها: {{ $medication->prescribed_quantity }}</p>
                            </div>
                            
                            <input type="hidden" 
                                   name="medications[{{ $medication->id }}][prescription_id]" 
                                   value="{{ $medication->prescription_id }}">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <input type="number" 
                                       name="medications[{{ $medication->id }}][quantity]" 
                                       class="form-input w-20 text-center" 
                                       min="0" 
                                       max="{{ $medication->prescribed_quantity }}"
                                       value="0"
                                       onchange="updateTotal()">
                                <span class="text-gray-600">الكمية</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- ملخص الطلبية -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold mb-4">ملخص الطلبية</h2>
            
            <div class="mb-4">
                <label for="notes" class="block text-gray-700 mb-2">ملاحظات</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          class="form-textarea w-full rounded-md"
                          placeholder="أضف أي ملاحظات خاصة بالطلبية"></textarea>
            </div>

            <div class="flex justify-between items-center text-lg font-bold mb-6">
                <span>الإجمالي:</span>
                <span id="total-amount">0 ريال</span>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                تأكيد الطلبية
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function switchTab(tabId) {
    // إخفاء جميع الأقسام
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // إزالة التنشيط من جميع الأزرار
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.remove('border-blue-500');
        btn.classList.remove('text-blue-600');
    });
    
    // إظهار القسم المطلوب
    document.getElementById(tabId + '-section').classList.remove('hidden');
    
    // تنشيط الزر المختار
    const activeBtn = document.getElementById(tabId + '-tab');
    activeBtn.classList.add('active');
    activeBtn.classList.add('border-blue-500');
    activeBtn.classList.add('text-blue-600');
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('input[name^="medications"][name$="[quantity]"]').forEach(input => {
        const medicationId = input.name.match(/medications\[(\d+)\]/)[1];
        const quantity = parseInt(input.value) || 0;
        const priceElement = input.closest('.bg-white').querySelector('.text-green-600');
        const price = parseFloat(priceElement.textContent);
        
        total += price * quantity;
    });
    
    document.getElementById('total-amount').textContent = total.toFixed(2) + ' ريال';
}

// تنشيط التبويب الأول عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    switchTab('regular');
});
</script>
@endpush

@push('styles')
<style>
.tab-btn.active {
    border-bottom-color: #3b82f6;
    color: #2563eb;
}
</style>
@endpush

@endsection