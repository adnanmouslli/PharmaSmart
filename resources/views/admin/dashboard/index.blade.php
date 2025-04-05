@extends('admin.layouts.app')

@section('title', 'لوحة المعلومات')
@section('description', 'نظرة عامة على إحصائيات النظام والمعلومات الرئيسية')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">إجمالي الطلبات</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 rounded-full p-3">
                        <i class="fas fa-shopping-basket text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 dark:text-green-400 flex items-center">
                        <i class="fas fa-arrow-up ml-1"></i> {{ $stats['orders_increase'] }}%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 mr-2">مقارنةً بالأسبوع الماضي</span>
                </div>
            </div>
        </div>

        <!-- Pending Prescriptions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">وصفات طبية معلقة</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['pending_prescriptions'] }}</h3>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 rounded-full p-3">
                        <i class="fas fa-file-medical text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.prescriptions.index', ['status' => 'pending']) }}" class="text-teal-600 dark:text-teal-400 text-sm hover:underline">
                        عرض الوصفات المعلقة
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">إجمالي العملاء</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_customers'] }}</h3>
                    </div>
                    <div class="bg-green-50 dark:bg-green-500/10 text-green-500 dark:text-green-400 rounded-full p-3">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 dark:text-green-400 flex items-center">
                        <i class="fas fa-arrow-up ml-1"></i> {{ $stats['customers_increase'] }}%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 mr-2">مقارنةً بالشهر الماضي</span>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">إجمالي الإيرادات</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_revenue'], 2) }} ريال</h3>
                    </div>
                    <div class="bg-teal-50 dark:bg-teal-500/10 text-teal-500 dark:text-teal-400 rounded-full p-3">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 dark:text-green-400 flex items-center">
                        <i class="fas fa-arrow-up ml-1"></i> {{ $stats['revenue_increase'] }}%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 mr-2">مقارنةً بالشهر الماضي</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders and Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">أحدث الطلبات</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-teal-600 dark:text-teal-400 text-sm hover:underline flex items-center">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                رقم الطلب
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                العميل
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                المبلغ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                التاريخ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($order->total_amount, 2) }} ريال</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                            معلق
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                            قيد المعالجة
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                            مكتمل
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                            ملغي
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('Y/m/d') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">المبيعات الشهرية</h3>
            </div>
            <div class="p-5">
                <canvas id="salesChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Second Row Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Prescription Queue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">قائمة انتظار الوصفات الطبية</h3>
                    <a href="{{ route('admin.prescriptions.index') }}" class="text-teal-600 dark:text-teal-400 text-sm hover:underline flex items-center">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-5">
                @if(count($pendingPrescriptions) > 0)
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($pendingPrescriptions as $prescription)
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $prescription->prescription_number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $prescription->user->first_name }} {{ $prescription->user->last_name }}</p>
                                    </div>
                                    <a href="{{ route('admin.prescriptions.show', $prescription) }}" class="bg-teal-500 hover:bg-teal-600 dark:bg-teal-600 dark:hover:bg-teal-700 text-white py-1 px-3 rounded-lg text-xs transition-colors">
                                        مراجعة
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-check-circle text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">لا توجد وصفات طبية معلقة</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Medications -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">أدوية منخفضة المخزون</h3>
                    <a href="{{ route('admin.medications.index', ['stock' => 'low']) }}" class="text-teal-600 dark:text-teal-400 text-sm hover:underline flex items-center">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-5">
                @if(count($lowStockMedications) > 0)
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($lowStockMedications as $medication)
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($medication->image)
                                            <img src="{{ asset('storage/' . $medication->image) }}" alt="{{ $medication->name }}" class="w-10 h-10 object-cover rounded-lg">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <i class="fas fa-pills text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="mr-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $medication->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">المخزون: <span class="text-red-500 dark:text-red-400">{{ $medication->stock }}</span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($medication->price, 2) }} ريال</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-check-circle text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">جميع الأدوية متوفرة بمخزون كافي</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    // Sales Chart with dark mode support
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Check if dark mode is active
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    // Set colors based on theme
    const textColor = isDarkMode ? '#CBD5E1' : '#1F2937';
    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'المبيعات الشهرية',
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: isDarkMode ? 'rgba(20, 184, 166, 0.2)' : 'rgba(13, 148, 136, 0.2)',
                borderColor: isDarkMode ? 'rgba(20, 184, 166, 1)' : 'rgba(13, 148, 136, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: isDarkMode ? '#1F2937' : '#FFFFFF',
                    titleColor: isDarkMode ? '#F9FAFB' : '#111827',
                    bodyColor: isDarkMode ? '#E5E7EB' : '#374151',
                    borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            return 'المبيعات: ' + context.parsed.y + ' ريال';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return value + ' ريال';
                        }
                    }
                },
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                }
            }
        }
    });
    
    // Update chart colors when theme changes
    document.addEventListener('alpine:initializing', () => {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            setTimeout(() => {
                location.reload();
            }, 100);
        });
    });
</script>
@endpush