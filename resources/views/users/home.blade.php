@extends('layout.user')

@section('title', 'الرئيسية')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-br from-teal-500 to-teal-600 dark:from-teal-600 dark:to-teal-700 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mt-32 -mr-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -mb-24 -ml-24"></div>
    
    <div class="relative z-10">
        <div class="flex items-center gap-4 mb-4">
            <img src="{{ auth()->user()->avatar_url ?? 'http://127.0.0.1:8000/avoter.png' }}" 
                 alt="صورة المستخدم"
                 class="w-16 h-16 rounded-full border-4 border-white/20 dark:border-white/10">
            <div>
                <h1 class="text-2xl font-bold mb-1">
                    مرحباً {{ auth()->user()->first_name }}!
                </h1>
                <p class="text-teal-100 dark:text-teal-200">
                    نتمنى لك يوماً صحياً سعيداً
                </p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $activeOrders }}</div>
                <div class="text-teal-100 dark:text-teal-200">طلبات نشطة</div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $pendingPrescriptions }}</div>
                <div class="text-teal-100 dark:text-teal-200">وصفات طبية معلقة</div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <div class="text-2xl font-bold">{{ $completedOrders }}</div>
                <div class="text-teal-100 dark:text-teal-200">طلبات مكتملة</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- New Prescription -->
    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-gray-700/10 hover:shadow-md dark:hover:shadow-gray-700/20 transition-all relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 dark:bg-teal-500/10 rounded-full -mt-16 -mr-16 transition-transform duration-300 group-hover:scale-110"></div>
        
        <div class="relative">
            <div class="w-12 h-12 bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 rounded-xl flex items-center justify-center mb-4
                      transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12">
                <i class="fas fa-prescription text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">رفع وصفة طبية</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">قم برفع وصفتك الطبية لصرفها</p>
            <a href="{{ route('prescriptions.create') }}" 
               class="inline-flex items-center gap-2 text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition-all group">
                رفع وصفة
                <i class="fas fa-arrow-left transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Browse Medications -->
    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-gray-700/10 hover:shadow-md dark:hover:shadow-gray-700/20 transition-all relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-50 dark:bg-cyan-500/10 rounded-full -mt-16 -mr-16 transition-transform duration-300 group-hover:scale-110"></div>
        
        <div class="relative">
            <div class="w-12 h-12 bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 rounded-xl flex items-center justify-center mb-4
                      transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12">
                <i class="fas fa-pills text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">تصفح الأدوية</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">استعرض الأدوية المتوفرة في الصيدلية</p>
            <a href="{{ route('medications.index') }}" 
               class="inline-flex items-center gap-2 text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-all group">
                تصفح الأدوية
                <i class="fas fa-arrow-left transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<!-- Recent Orders & Prescriptions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-gray-700/10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">الطلبات الأخيرة</h3>
            <a href="{{ route('orders.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                عرض الكل
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-gray-500 dark:text-gray-400"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">طلب #{{ $order->order_number }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('Y/m/d') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php
                    $orderStatusColors = [
                        'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                        'processing' => 'bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400',
                        'completed' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                        'cancelled' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                    ];
                    @endphp

                    <span class="px-3 py-1 text-sm rounded-full {{ $orderStatusColors[$order->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __("{$order->status}") }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-bag text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h4 class="text-gray-500 dark:text-gray-400 mb-2">لا توجد طلبات حديثة</h4>
                <a href="{{ route('medications.index') }}" 
                   class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 hover:underline">
                    تصفح الأدوية
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Prescriptions -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-gray-700/10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">الوصفات الطبية الأخيرة</h3>
            <a href="{{ route('prescriptions.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                عرض الكل
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($recentPrescriptions as $prescription)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-prescription text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">#{{ $prescription->prescription_number }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">د. {{ $prescription->doctor_name }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    @php
                    $statusColors = [
                        'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                        'under_review' => 'bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400',
                        'approved' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                        'partially_approved' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                        'rejected' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                    ];
                    @endphp

                    <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$prescription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __("{$prescription->status}") }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $prescription->prescription_date->format('Y/m/d') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-medical text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
<h4 class="text-gray-500 dark:text-gray-400 mb-2">لا توجد وصفات طبية</h4>
                <a href="{{ route('prescriptions.create') }}" 
                   class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 hover:underline">
                    رفع وصفة طبية
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Available Medications -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-gray-700/10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">أدوية متوفرة</h3>
            <p class="text-gray-500 dark:text-gray-400">أحدث الأدوية المتوفرة في الصيدلية</p>
        </div>
        <a href="{{ route('medications.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
            عرض المزيد
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($recentMedications as $medication)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-700/10 hover:shadow-md dark:hover:shadow-gray-700/20 transition-all">
            <div class="aspect-square relative overflow-hidden rounded-t-xl">
                @if($medication->image)
                    <img src="{{ asset('storage/' . $medication->image) }}" 
                         alt="{{ $medication->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-4xl"></i>
                    </div>
                @endif
                
                @if($medication->requires_prescription)
                    <div class="absolute top-4 right-4">
                        @if($approvedPrescriptionMeds->contains('id', $medication->id))
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 rounded-full text-sm">
                                <i class="fas fa-check-circle ml-1"></i>
                                معتمد في وصفة
                            </span>
                        @else
                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 rounded-full text-sm">
                                <i class="fas fa-file-medical ml-1"></i>
                                يتطلب وصفة
                            </span>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="p-4">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">{{ $medication->name }}</h4>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $medication->category->name }}</span>
                    <span class="font-bold text-teal-600 dark:text-teal-400">{{ number_format($medication->price, 2) }} ر.س</span>
                </div>
                
                @if($medication->requires_prescription)
                    @php
                        $approvedPrescription = $approvedPrescriptionMeds->firstWhere('id', $medication->id);
                    @endphp
                    @if($approvedPrescription)
                        <div class="mb-3 text-sm text-gray-600 dark:text-gray-300">
                            <p>الكمية المعتمدة: {{ $approvedPrescription->prescribed_quantity }}</p>
                            @if($approvedPrescription->dosage_instructions)
                                <p class="mt-1 text-emerald-600 dark:text-emerald-400">
                                    {{ $approvedPrescription->dosage_instructions }}
                                </p>
                            @endif
                        </div>
                    @endif
                @endif

                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $medication->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $medication->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                    </span>
                    @if($medication->stock > 0)
                        @if($medication->requires_prescription)
                            @if(!$approvedPrescriptionMeds->contains('id', $medication->id))
                               
                            <a href="{{ route('prescriptions.create') }}" 
                               class="px-4 py-2 bg-teal-600 dark:bg-teal-500 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-600 transition-colors text-sm">
                                رفع وصفة
                            </a>
                            @endif
                        
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h4 class="text-gray-800 dark:text-white font-medium mb-2">لا توجد أدوية متوفرة حالياً</h4>
                <p class="text-gray-500 dark:text-gray-400">سيتم إضافة أدوية جديدة قريباً</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function updateStats() {
    fetch('/api/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('activeOrders').textContent = data.activeOrders;
            document.getElementById('pendingPrescriptions').textContent = data.pendingPrescriptions;
            document.getElementById('completedOrders').textContent = data.completedOrders;
        })
        .catch(error => console.error('Error:', error));
}
</script>
@endpush

@endsection