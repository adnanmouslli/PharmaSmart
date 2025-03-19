@extends('layout.user')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-6">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-800 dark:text-gray-100">
        تفاصيل الطلب - {{ $order->order_number }}
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            @php
            $statusColors = [
                'pending' => 'bg-yellow-100 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400',
                'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                'completed' => 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400',
                'cancelled' => 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400'
            ];
            @endphp

            <h3 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">
                <i class="fas fa-info-circle text-teal-500"></i> المعلومات الأساسية
            </h3>
            <ul class="text-gray-600 dark:text-gray-400 space-y-2">
                <li><strong>التاريخ:</strong> {{ $order->created_at->format('Y/m/d') }}</li>
                <li><strong>الإجمالي:</strong> {{ number_format($order->total_amount, 2) }} ر.س</li>
                <li><strong>الحالة:</strong>
                    <span class="inline-block px-4 py-1 text-sm rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                        {{ $order->status_text }}
                    </span>
                </li>
            </ul>
        </div>

        @if($order->prescription)
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">
                <i class="fas fa-prescription-bottle-alt text-indigo-500"></i> الوصفة الطبية
            </h3>
            <p>
                <a href="{{ route('prescriptions.show', $order->prescription) }}" 
                   class="text-teal-600 dark:text-teal-400 hover:underline font-medium">
                   {{ $order->prescription->prescription_number }}
                </a>
            </p>
        </div>
        @endif
    </div>

    <h3 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">
        <i class="fas fa-pills text-purple-500"></i> الأدوية المطلوبة
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse rounded-lg shadow-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="text-right py-3 px-4">الدواء</th>
                    <th class="text-right py-3 px-4">الكمية</th>
                    <th class="text-right py-3 px-4">السعر</th>
                    <th class="text-right py-3 px-4">الإجمالي</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @foreach($order->items as $item)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="py-3 px-4">{{ $item->medication->name }}</td>
                    <td class="py-3 px-4">{{ $item->quantity }}</td>
                    <td class="py-3 px-4">{{ number_format($item->unit_price, 2) }} ر.س</td>
                    <td class="py-3 px-4">{{ number_format($item->total_price, 2) }} ر.س</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection