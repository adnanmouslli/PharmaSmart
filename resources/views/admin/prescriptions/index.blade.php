@extends('admin.layouts.app')

@section('title', 'إدارة الوصفات الطبية')
@section('description', 'مراجعة واعتماد الوصفات الطبية')

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

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">إدارة الوصفات الطبية</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">مراجعة واعتماد الوصفات الطبية</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1 min-w-[200px]">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </span>
                        <input type="text" id="search-prescriptions" placeholder="بحث عن وصفة..." 
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
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>مقبولة</option>
                            <option value="partially_approved" {{ request('status') == 'partially_approved' ? 'selected' : '' }}>مقبولة جزئياً</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
        <!-- Table Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                <span class="text-gray-600 dark:text-gray-400">إجمالي الوصفات: {{ $prescriptions->total() }}</span>
                @if(request('status'))
                    <a href="{{ route('admin.prescriptions.index') }}" class="text-teal-600 dark:text-teal-400 hover:underline flex items-center">
                        <i class="fas fa-times-circle ml-1"></i>
                        إلغاء التصفية
                    </a>
                @endif
            </div>
            <div class="w-full sm:w-auto">
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
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                رقم الوصفة
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
                                اسم الطبيب
                                <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                تاريخ الإضافة
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
                    @forelse($prescriptions as $prescription)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $prescription->prescription_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        {{-- <img class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600" 
                                            src="{{ $prescription->user->avatar ? asset('storage/'.$prescription->user->avatar) : asset('images/default-avatar.png') }}" 
                                            alt="{{ $prescription->user->first_name }}"> --}}
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $prescription->user->first_name }} {{ $prescription->user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $prescription->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $prescription->doctor_name }}
                                </div>
                                @if($prescription->hospital_name)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $prescription->hospital_name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $prescription->created_at->format('Y/m/d') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $prescription->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($prescription->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                        معلقة
                                    </span>
                                @elseif($prescription->status == 'under_review')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                        قيد المراجعة
                                    </span>
                                @elseif($prescription->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                        مقبولة
                                    </span>
                                @elseif($prescription->status == 'partially_approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300">
                                        مقبولة جزئياً
                                    </span>
                                @elseif($prescription->status == 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                        مرفوضة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <a href="{{ route('admin.prescriptions.show', $prescription) }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($prescription->status == 'pending')
                                        <form action="{{ route('admin.prescriptions.update-status', [$prescription, 'under_review']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                                <i class="fas fa-clipboard-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-file-medical text-4xl mb-4 text-gray-400 dark:text-gray-500"></i>
                                    <p>لا توجد وصفات طبية متطابقة مع بحثك</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $prescriptions->appends(request()->query())->links() }}
        </div>
    </div>
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
    const searchInput = document.getElementById('search-prescriptions');
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
</script>
@endpush