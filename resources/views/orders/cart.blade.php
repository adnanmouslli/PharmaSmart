@extends('layout.user')

@section('title', 'سلة التسوق')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-bold mb-6">سلة التسوق</h2>

    @if(empty($regularItems) && empty($prescriptionItems))
        <div class="text-center py-16">
            <i class="fas fa-shopping-cart text-gray-400 dark:text-gray-500 text-4xl mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400">سلة التسوق فارغة.</p>
            <a href="{{ route('medications.index') }}" class="mt-4 inline-block text-teal-600 dark:text-teal-400 hover:underline">
                تصفح الأدوية
            </a>
        </div>
    @else
        <!-- الأدوية العادية -->
        @if(!empty($regularItems))
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">الأدوية العادية</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="text-right py-3">الدواء</th>
                                <th class="text-right py-3">الكمية</th>
                                <th class="text-right py-3">السعر</th>
                                <th class="text-right py-3">الإجمالي</th>
                                <th class="text-center py-3">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regularItems as $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-4">{{ $item['medication']->name }}</td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button" 
                                                    onclick="updateQuantity('{{ $item['medication']->id }}', 'decrease')"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full border dark:border-gray-600">
                                                -
                                            </button>
                                            <span class="w-12 text-center">{{ $item['quantity'] }}</span>
                                            <button type="button"
                                                    onclick="updateQuantity('{{ $item['medication']->id }}', 'increase')"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full border dark:border-gray-600">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4">{{ number_format($item['medication']->price, 2) }} ر.س</td>
                                    <td class="py-4">{{ number_format($item['total'], 2) }} ر.س</td>
                                    <td class="py-4 text-center">
                                        <form action="{{ route('cart.remove', $item['medication']->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- أدوية الوصفات -->
        @if(!empty($prescriptionItems))
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">أدوية الوصفات الطبية</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="text-right py-3">الدواء</th>
                                <th class="text-right py-3">الكمية</th>
                                <th class="text-right py-3">السعر</th>
                                <th class="text-right py-3">الإجمالي</th>
                                <th class="text-right py-3">رقم الوصفة</th>
                                <th class="text-center py-3">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptionItems as $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-4">{{ $item['medication']->name }}</td>
                                    <td class="py-4">{{ $item['quantity'] }}</td>
                                    <td class="py-4">{{ number_format($item['medication']->price, 2) }} ر.س</td>
                                    <td class="py-4">{{ number_format($item['total'], 2) }} ر.س</td>
                                    <td class="py-4">#{{ $item['prescription_id'] }}</td>
                                    <td class="py-4 text-center">
                                        <form action="{{ route('cart.remove', $item['medication']->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- إجمالي السلة -->
        <div class="flex justify-between items-center mt-8 pt-6 border-t dark:border-gray-700">
            <div>
                <div class="text-xl font-bold mb-2">
                    الإجمالي: {{ number_format($total, 2) }} ر.س
                </div>
                <p class="text-sm text-gray-500">
                    * يشمل السعر ضريبة القيمة المضافة
                </p>
            </div>
            <form action="{{ route('orders.store') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors">
                    <i class="fas fa-check ml-2"></i>
                    إتمام الطلب
                </button>
            </form>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updateQuantity(medicationId, action) {
        fetch(`/cart/update/${medicationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message);
            }
        });
    }
</script>
@endpush
@endsection