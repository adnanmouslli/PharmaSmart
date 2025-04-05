@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')
@section('description', 'عرض وإدارة جميع طلبات العملاء')

@section('content')
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">إدارة الطلبات</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">عرض وإدارة جميع طلبات العملاء</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1 min-w-[200px]">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </span>
                        <input type="text" id="search-orders" placeholder="بحث عن طلب..." 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pr-10 py-2
                                      bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                      focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                      placeholder-gray-400 dark:placeholder-gray-500 transition-colors duration-200">
                    </div>
                    <div class="relative flex-1 min-w-[200px]">
                        <select id="status-filter" 
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 pr-4
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                       transition-colors duration-200">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
        <!-- Table Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                <span class="text-gray-600 dark:text-gray-400">إجمالي الطلبات: {{ $orders->total() }}</span>
                @if(request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="text-teal-600 dark:text-teal-400 hover:underline flex items-center">
                        <i class="fas fa-times-circle ml-1"></i>
                        إلغاء التصفية
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <select id="per-page" 
                            class="rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   transition-colors duration-200">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 لكل صفحة</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 لكل صفحة</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 لكل صفحة</option>
                    </select>
                </div>
                {{-- <a href="{{ route('admin.orders.export') }}" 
                   class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                          text-white rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                    <i class="fas fa-download ml-2"></i>
                    تصدير
                </a> --}}
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                رقم الطلب
                                <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                العميل
                                <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                المبلغ
                                <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                التاريخ
                                <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</div>
                                @if($order->prescription_id)
                                    <div class="text-xs text-teal-600 dark:text-teal-400 mt-1">
                                        <i class="fas fa-file-medical ml-1"></i>
                                        بوصفة طبية
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600" 
                                            src="{{ $order->user->avatar ? asset('storage/'.$order->user->avatar) : asset('images/default-avatar.png') }}" 
                                            alt="{{ $order->user->first_name }}">
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $order->user->first_name }} {{ $order->user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($order->total_amount, 2) }} ريال
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('Y/m/d') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('h:i A') }}</div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute z-10 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 mt-1 -left-2"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95">
                                            {{-- <a href="{{ route('admin.orders.print', $order) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-print ml-2"></i>
                                                طباعة
                                            </a> --}}
                                            @if($order->status == 'pending')
                                                <form action="{{ route('admin.orders.update-status', [$order, 'processing']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        <i class="fas fa-spinner ml-2"></i>
                                                        تحديث للمعالجة
                                                    </button>
                                                </form>
                                            @elseif($order->status == 'processing')
                                                <form action="{{ route('admin.orders.update-status', [$order, 'completed']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        <i class="fas fa-check ml-2"></i>
                                                        تحديث للاكتمال
                                                    </button>
                                                </form>
                                            @endif
                                            @if($order->status != 'cancelled')
                                                <form action="{{ route('admin.orders.update-status', [$order, 'cancelled']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" 
                                                            class="w-full text-right block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                                        <i class="fas fa-times ml-2"></i>
                                                        إلغاء الطلب
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl mb-4 text-gray-400 dark:text-gray-500"></i>
                                    <p>لا توجد طلبات متطابقة مع بحثك</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Order Details Modal -->
    {{-- <div id="order-modal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-gray-900/30 w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تفاصيل الطلب</h3>
                <button id="close-modal" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modal-content" class="p-6">
                <!-- Content will be loaded here via AJAX -->
                <div class="flex justify-center py-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-teal-500 dark:border-teal-400"></div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('scripts')
<script>
    // Status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        let url = new URL(window.location.href);
        
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        window.location.href = url.toString();
    });

    // Per page
    document.getElementById('per-page').addEventListener('change', function() {
        const perPage = this.value;
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        window.location.href = url.toString();
    });

    // Search functionality
    const searchInput = document.getElementById('search-orders');
    let timeout = null;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        
        timeout = setTimeout(() => {
            const query = this.value;
            let url = new URL(window.location.href);
            
            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            
            window.location.href = url.toString();
        }, 500);
    });

    // Order details modal
    document.querySelectorAll('.view-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const modal = document.getElementById('order-modal');
            const modalContent = document.getElementById('modal-content');
            
            modal.classList.remove('hidden');
            
            // Load order details via AJAX
            fetch(`/admin/orders/${orderId}/details`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    modalContent.innerHTML = `<div class="text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل تفاصيل الطلب</div>`;
                });
        });
    });

    // Close modal
    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('order-modal').classList.add('hidden');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('order-modal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>
@endpush