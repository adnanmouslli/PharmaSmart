@extends('layout.user')

@section('title', 'الوصفات الطبية')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-teal-500 to-teal-600 dark:from-teal-600 dark:to-teal-700 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mt-32 -mr-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -mb-24 -ml-24"></div>
    
    <div class="relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-3xl font-bold mb-4">الوصفات الطبية</h1>
            <p class="text-teal-100 dark:text-teal-200 text-lg mb-8">قم برفع وصفاتك الطبية ومتابعة حالتها بسهولة</p>
            
            <a href="{{ route('prescriptions.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-white/10 text-teal-600 dark:text-white rounded-xl hover:bg-white/90 dark:hover:bg-white/20 transition-colors">
                <i class="fas fa-plus-circle"></i>
                رفع وصفة جديدة
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $prescriptions->where('status', 'pending')->count() }}</div>
                <div class="text-teal-100 dark:text-teal-200">قيد الانتظار</div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $prescriptions->where('status', 'approved')->count() }}</div>
                <div class="text-teal-100 dark:text-teal-200">تمت الموافقة</div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $prescriptions->count() }}</div>
                <div class="text-teal-100 dark:text-teal-200">إجمالي الوصفات</div>
            </div>
        </div>
    </div>
</div>

<!-- Prescriptions List -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10">
    @if($prescriptions->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-prescription text-gray-500 dark:text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-gray-800 dark:text-white font-medium mb-2">لا توجد وصفات طبية</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ بإضافة أول وصفة طبية</p>
            <a href="{{ route('prescriptions.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-500/20 transition-colors">
                <i class="fas fa-plus-circle"></i>
                رفع وصفة طبية
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">رقم الوصفة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">الطبيب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">المراجع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($prescriptions as $prescription)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                {{ $prescription->prescription_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $prescription->doctor_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $prescription->prescription_date->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'under_review' => 'bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400',
                                    'approved' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                    'partially_approved' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'rejected' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                                ];
                                @endphp
                                <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$prescription->status] }}">
                                    {{ ("{$prescription->status}") }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $prescription->reviewer ? $prescription->reviewer->getFullNameAttribute() : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('prescriptions.show', $prescription) }}" 
                                       class="p-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($prescription->status === 'pending')
                                        <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الوصفة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-rose-500 hover:text-rose-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $prescriptions->links() }}
        </div>
    @endif
</div>
@endsection