@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->order_number)
@section('description', 'عرض معلومات وتفاصيل الطلب')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                </div>
                <div class="mr-3">
                    <p class="font-bold text-green-700 dark:text-green-300">تمت العملية بنجاح</p>
                    <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 dark:text-red-400 text-xl"></i>
                </div>
                <div class="mr-3">
                    <p class="font-bold text-red-700 dark:text-red-300">خطأ</p>
                    <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header with Back Button and Status -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" 
               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-right ml-1"></i>
                العودة للطلبات
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">تفاصيل الطلب #{{ $order->order_number }}</h2>
        </div>

        <div class="flex flex-wrap gap-2">
            <div class="flex items-center ml-4">
                <span class="text-gray-600 dark:text-gray-400 ml-2">الحالة:</span>
                @if($order->status == 'pending')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                        معلق
                    </span>
                @elseif($order->status == 'processing')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                        قيد المعالجة
                    </span>
                @elseif($order->status == 'completed')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                        مكتمل
                    </span>
                @elseif($order->status == 'cancelled')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                        ملغي
                    </span>
                @endif
            </div>
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank" 
               class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fas fa-print ml-1"></i>
                طباعة
            </a>
            @if($order->status == 'pending')
                <form action="{{ route('admin.orders.update-status', [$order, 'processing']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-spinner ml-1"></i>
                        بدء المعالجة
                    </button>
                </form>
            @elseif($order->status == 'processing')
                <form action="{{ route('admin.orders.update-status', [$order, 'completed']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-check ml-1"></i>
                        إكمال الطلب
                    </button>
                </form>
            @endif
            @if($order->status != 'cancelled')
                <form action="{{ route('admin.orders.update-status', [$order, 'cancelled']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" 
                            class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-times ml-1"></i>
                        إلغاء الطلب
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="md:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Order Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات الطلب</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">رقم الطلب</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">تاريخ الطلب</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('Y/m/d h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">المبلغ الإجمالي</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ number_format($order->total_amount, 2) }} ريال</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items Table -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">منتجات الطلب</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        المنتج
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        السعر
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        الكمية
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        الإجمالي
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        الحالة
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($order->items as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    @if($item->medication->image)
                                                        <img class="h-12 w-12 rounded object-cover border border-gray-200 dark:border-gray-600" 
                                                            src="{{ asset('storage/' . $item->medication->image) }}" 
                                                            alt="{{ $item->medication->name }}">
                                                    @else
                                                        <div class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                            <i class="fas fa-pills text-gray-500 dark:text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item->medication->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $item->medication->dosage_form }} - {{ $item->medication->strength }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($item->unit_price, 2) }} ريال
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($item->total_price, 2) }} ريال
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($item->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                    معلق
                                                </span>
                                            @elseif($item->status == 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                    مقبول
                                                </span>
                                            @elseif($item->status == 'rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                                    مرفوض
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">ملاحظات الطلب</h3>
                    @if($order->notes)
                        <p class="text-gray-700 dark:text-gray-300">{{ $order->notes }}</p>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">لا توجد ملاحظات لهذا الطلب.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Customer Information -->
        <div>
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات العميل</h3>
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <img class="h-16 w-16 rounded-full object-cover border border-gray-200 dark:border-gray-600" 
                                 src="{{ $order->user->avatar ? asset('storage/' . $order->user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $order->user->first_name }}">
                        </div>
                        <div class="mr-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $order->user->first_name }} {{ $order->user->last_name }}</h4>
                            <p class="text-gray-500 dark:text-gray-400">عميل منذ {{ $order->user->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-envelope"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $order->user->email }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-phone"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $order->user->phone }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $order->user->address }}</div>
                        </div>
                    </div>
                </div>
                {{-- <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                    <a href="{{ route('admin.users.show', $order->user) }}" 
                       class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 hover:underline flex items-center justify-center transition-colors">
                        <span>عرض الملف الشخصي</span>
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div> --}}
            </div>

            @if($order->prescription_id)
            <!-- Prescription Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات الوصفة الطبية</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">رقم الوصفة</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->prescription->prescription_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">اسم الطبيب</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->prescription->doctor_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">المستشفى</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->prescription->hospital_name ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">تاريخ الوصفة</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->prescription->prescription_date->format('Y/m/d') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">حالة الوصفة</p>
                            @if($order->prescription->status == 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                    معلقة
                                </span>
                            @elseif($order->prescription->status == 'under_review')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                    قيد المراجعة
                                </span>
                            @elseif($order->prescription->status == 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                    مقبولة
                                </span>
                            @elseif($order->prescription->status == 'partially_approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300">
                                    مقبولة جزئياً
                                </span>
                            @elseif($order->prescription->status == 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                    مرفوضة
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="font-medium text-gray-800 dark:text-white mb-3">صورة الوصفة</h4>
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $order->prescription->image) }}"
                             alt="صورة الوصفة الطبية"
                             class="w-full h-auto">
                    </div>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                    <a href="{{ route('admin.prescriptions.show', $order->prescription) }}" 
                       class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 hover:underline flex items-center justify-center transition-colors">
                        <span>عرض تفاصيل الوصفة</span>
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection