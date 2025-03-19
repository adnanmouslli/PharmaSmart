@extends('layout.user')

@section('title', 'تفاصيل الوصفة الطبية')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            وصفة طبية #{{ $prescription->prescription_number }}
        </h1>
        <p class="text-gray-500 dark:text-gray-400">تفاصيل الوصفة الطبية وحالتها</p>
    </div>

    <!-- Status and Info -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Status -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">الحالة</h4>
                @php
                $statusColors = [
                    'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                    'under_review' => 'bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400',
                    'approved' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                    'partially_approved' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                    'rejected' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                ];
                @endphp
                <span class="px-3 py-1 text-sm rounded-full inline-block {{ $statusColors[$prescription->status] }}">
                    {{ ("{$prescription->status}") }}
                </span>
            </div>

            <!-- Doctor -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">الطبيب</h4>
                <p class="text-gray-800 dark:text-white">{{ $prescription->doctor_name }}</p>
            </div>

            <!-- Hospital -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">المستشفى</h4>
                <p class="text-gray-800 dark:text-white">{{ $prescription->hospital_name ?? 'غير محدد' }}</p>
            </div>

            <!-- Date -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">تاريخ الوصفة</h4>
                <p class="text-gray-800 dark:text-white">{{ $prescription->prescription_date->format('Y/m/d') }}</p>
            </div>
        </div>

        @if($prescription->rejection_reason)
        <!-- Rejection Reason -->
        <div class="mt-6 p-4 bg-rose-50 dark:bg-rose-500/5 rounded-xl">
            <h4 class="text-sm font-medium text-rose-600 dark:text-rose-400 mb-1">سبب الرفض</h4>
            <p class="text-rose-700 dark:text-rose-300">{{ $prescription->rejection_reason }}</p>
        </div>
        @endif

        @if($prescription->notes)
        <!-- Notes -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ملاحظات</h4>
            <p class="text-gray-700 dark:text-gray-300">{{ $prescription->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Prescription Image -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">صورة الوصفة الطبية</h3>
        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
            <img src="{{ asset('storage/' . $prescription->image) }}" 
                 alt="صورة الوصفة الطبية"
                 class="w-full h-full object-contain">
        </div>
    </div>

    <!-- Medications List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">الأدوية المطلوبة</h3>

        <div class="space-y-4">
            @forelse($prescription->medications as $medication)
            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <!-- Medication Image -->
                <div class="w-16 h-16 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center">
                    @if($medication->image)
                        <img src="{{ asset('storage/' . $medication->image) }}" 
                             alt="{{ $medication->name }}"
                             class="w-full h-full object-cover rounded-lg">
                    @else
                        <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-2xl"></i>
                    @endif
                </div>

                <!-- Medication Info -->
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-gray-800 dark:text-white">{{ $medication->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $medication->strength }}</p>
                        </div>
                        @php
                        $medicationStatusColors = [
                            'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                            'approved' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                            'rejected' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                        ];
                        @endphp
                        <span class="px-3 py-1 text-sm rounded-full {{ $medicationStatusColors[$medication->pivot->status] }}">
                            {{ ("{$medication->pivot->status}") }}
                        </span>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">الكمية</span>
                            <p class="text-gray-800 dark:text-white">{{ $medication->pivot->quantity }}</p>
                        </div>
                        @if($medication->pivot->dosage_instructions)
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">تعليمات الجرعة</span>
                            <p class="text-gray-800 dark:text-white">{{ $medication->pivot->dosage_instructions }}</p>
                        </div>
                        @endif
                    </div>

                    @if($medication->pivot->notes)
                    <div class="mt-3">
                        <span class="text-sm text-gray-500 dark:text-gray-400">ملاحظات</span>
                        <p class="text-gray-800 dark:text-white">{{ $medication->pivot->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h4 class="text-gray-800 dark:text-white font-medium mb-2">لا توجد أدوية مضافة</h4>
                <p class="text-gray-500 dark:text-gray-400">لم يتم إضافة أي أدوية لهذه الوصفة</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('prescriptions.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-right"></i>
            العودة للوصفات
        </a>

        @if($prescription->status === 'pending')
        <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST"
              onsubmit="return confirm('هل أنت متأكد من حذف هذه الوصفة؟');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-6 py-3 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors">
                <i class="fas fa-trash ml-2"></i>
                حذف الوصفة
            </button>
        </form>
        @endif
    </div>
</div>
@endsection