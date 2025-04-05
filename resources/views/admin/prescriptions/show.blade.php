@extends('admin.layouts.app')

@section('title', 'تفاصيل الوصفة الطبية #' . $prescription->prescription_number)
@section('description', 'عرض معلومات وتفاصيل الوصفة الطبية')

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

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 dark:text-red-400 text-xl"></i>
                </div>
                <div class="mr-3">
                    <p class="font-bold text-red-700 dark:text-red-300">حدث خطأ</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-700 dark:text-red-300">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button and Status -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.prescriptions.index') }}" 
               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-right ml-1"></i>
                العودة للقائمة
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">الوصفة الطبية #{{ $prescription->prescription_number }}</h2>
        </div>
        <div>
            @if($prescription->status == 'pending')
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                    معلقة
                </span>
            @elseif($prescription->status == 'under_review')
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                    قيد المراجعة
                </span>
            @elseif($prescription->status == 'approved')
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                    مقبولة
                </span>
            @elseif($prescription->status == 'partially_approved')
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300">
                    مقبولة جزئياً
                </span>
            @elseif($prescription->status == 'rejected')
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                    مرفوضة
                </span>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Prescription Details -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                <div class="border-b dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">تفاصيل الوصفة الطبية</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">الطبيب</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $prescription->doctor_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">المستشفى/العيادة</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $prescription->hospital_name ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">تاريخ الوصفة</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $prescription->prescription_date ? $prescription->prescription_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">تاريخ الإضافة</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $prescription->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    @if($prescription->notes)
                        <div class="mt-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">ملاحظات طبية</p>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-md text-gray-700 dark:text-gray-300">
                                {{ $prescription->notes }}
                            </div>
                        </div>
                    @endif

                    @if($prescription->rejection_reason && $prescription->status == 'rejected')
                        <div class="mt-6">
                            <p class="text-sm text-red-500 dark:text-red-400 mb-2">سبب الرفض</p>
                            <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-md text-red-700 dark:text-red-300">
                                {{ $prescription->rejection_reason }}
                            </div>
                        </div>
                    @endif

                    @if($prescription->reviewed_by)
                        <div class="mt-6 pt-6 border-t dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600" 
                                         src="{{ $prescription->reviewer->avatar ? asset('storage/' . $prescription->reviewer->avatar) : asset('images/default-avatar.png') }}" 
                                         alt="{{ $prescription->reviewer->name }}">
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">تمت المراجعة بواسطة</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $prescription->reviewer->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $prescription->reviewed_at->format('Y-m-d h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prescription Medications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                <div class="border-b dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">الأدوية المطلوبة</h3>
                    @if($prescription->status == 'under_review')
                        <button id="approveAllBtn" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 dark:hover:bg-green-700 transition-colors text-sm flex items-center">
                            <i class="fas fa-check ml-1"></i>
                            الموافقة على الكل
                        </button>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">اسم الدواء</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">التعليمات</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الحالة</th>
                                @if($prescription->status == 'under_review')
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الإجراءات</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($prescription->medications as $medication)
                                <tr data-medication-id="{{ $medication->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $medication->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $medication->scientific_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $medication->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $medication->instructions }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap status-cell">
                                        @if($medication->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                معلق
                                            </span>
                                        @elseif($medication->status == 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                مقبول
                                            </span>
                                        @elseif($medication->status == 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                                مرفوض
                                            </span>
                                        @endif
                                    </td>
                                    @if($prescription->status == 'under_review')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3 space-x-reverse">
                                                <form action="{{ route('admin.prescriptions.approve-medication', [$prescription, $medication]) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.prescriptions.reject-medication', [$prescription, $medication]) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $prescription->status == 'under_review' ? 5 : 4 }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        لا توجد أدوية مطلوبة في هذه الوصفة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prescription Images -->
            @if($prescription->images && count($prescription->images) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                    <div class="border-b dark:border-gray-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">صور الوصفة الطبية</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($prescription->images as $image)
                                <div class="relative group">
                                    <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image) }}" alt="صورة الوصفة الطبية" 
                                             class="h-48 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                            <i class="fas fa-search-plus text-white text-2xl"></i>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Customer Information and Actions Sidebar -->
        <div>
            
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات العميل</h3>
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            {{-- <img class="h-16 w-16 rounded-full object-cover border border-gray-200 dark:border-gray-600" 
                                 src="{{ $prescription->user->avatar ? asset('storage/' . $prescription->user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $prescription->user->first_name }}"> --}}
                        </div>
                        <div class="mr-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $prescription->user->first_name }} {{ $prescription->user->last_name }}</h4>
                            <p class="text-gray-500 dark:text-gray-400">عميل منذ {{ $prescription->user->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-envelope"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $prescription->user->email }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-phone"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $prescription->user->phone }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 text-gray-500 dark:text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="text-gray-700 dark:text-gray-300">{{ $prescription->user->address }}</div>
                        </div>
                    </div>
                </div>
                {{-- <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                    <a href="{{ route('admin.users.show', $prescription->user) }}" 
                       class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 hover:underline flex items-center justify-center transition-colors">
                        <span>عرض الملف الشخصي</span>
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div> --}}
            </div>

            <!-- Admin Actions -->
            @if($prescription->status == 'under_review')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">إجراءات المراجعة</h3>
                    <div class="space-y-4">
                        <button id="approveBtn" class="w-full bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-check ml-1"></i>
                            الموافقة على الوصفة
                        </button>
                        <button id="rejectBtn" class="w-full bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times ml-1"></i>
                            رفض الوصفة
                        </button>
                        <button id="completeReviewBtn" class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-clipboard-check ml-1"></i>
                            إكمال المراجعة
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md transition-colors">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تأكيد الموافقة</h3>
                <button type="button" class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="mb-4 text-gray-700 dark:text-gray-300">هل أنت متأكد من الموافقة على هذه الوصفة الطبية؟</p>
                <form action="{{ route('admin.prescriptions.update-status', [$prescription, 'approved']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="notes" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">ملاحظات (اختياري)</label>
                        <textarea id="notes" name="notes" rows="3" 
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="أضف ملاحظات حول الموافقة..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="close-modal bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors ml-2">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            تأكيد الموافقة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md transition-colors">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تأكيد الرفض</h3>
                <button type="button" class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="mb-4 text-gray-700 dark:text-gray-300">هل أنت متأكد من رفض هذه الوصفة الطبية؟</p>
                <form action="{{ route('admin.prescriptions.update-status', [$prescription, 'rejected']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">سبب الرفض <span class="text-red-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="يرجى تحديد سبب رفض الوصفة الطبية..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="close-modal bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors ml-2">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            تأكيد الرفض
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Review Modal -->
    <div id="completeReviewModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md transition-colors">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">إكمال المراجعة</h3>
                <button type="button" class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="mb-4 text-gray-700 dark:text-gray-300">هل أنت متأكد من إكمال مراجعة هذه الوصفة الطبية؟ سيتم تحديث الحالة بناءً على مراجعة الأدوية.</p>
                <form action="{{ route('admin.prescriptions.complete-review', $prescription) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="review_notes" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">ملاحظات المراجعة (اختياري)</label>
                        <textarea id="review_notes" name="review_notes" rows="3" 
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="أضف ملاحظات حول المراجعة..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="close-modal bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors ml-2">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            إكمال المراجعة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Dark mode toggle styles */
    #darkModeToggle:checked + div {
        @apply bg-teal-500;
    }
    
    .dot {
        transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out, left 0.3s ease-in-out;
    }
    
    #darkModeToggle:checked ~ .dot {
        transform: translateX(100%);
    }
    
    /* Modal transition styles */
    .modal-transition {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    
    /* Smooth transitions for dark mode */
    .transition-colors {
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/dark-mode.js') }}"></script>
<script>
    // Modal functions with animation
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        // First remove hidden class
        modal.classList.remove('hidden');
        // Force reflow to make the animation visible
        void modal.offsetWidth;
        // Then set opacity to 1 to fade in
        modal.style.opacity = '1';
    }
    
    function closeModals() {
        document.querySelectorAll('.fixed.modal-transition').forEach(modal => {
            // Fade out first
            modal.style.opacity = '0';
            // Then hide after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.opacity = '';
            }, 300);
        });
    }
    
    // Add event listeners
    document.getElementById('approveBtn').addEventListener('click', function() {
        openModal('approveModal');
    });
    
    document.getElementById('rejectBtn').addEventListener('click', function() {
        openModal('rejectModal');
    });
    
    document.getElementById('completeReviewBtn').addEventListener('click', function() {
        openModal('completeReviewModal');
    });
    
    document.getElementById('approveAllBtn').addEventListener('click', function() {
        if (confirm('هل أنت متأكد من الموافقة على جميع الأدوية؟')) {
            // Send AJAX request to approve all medications
            fetch('{{ route("admin.prescriptions.approve-all-medications", $prescription) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء محاولة الموافقة على جميع الأدوية');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء محاولة الموافقة على جميع الأدوية');
            });
        }
    });
    
    // Close modal when clicking the close button or cancel button
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', closeModals);
    });
    
    // Close modals when clicking outside the modal content
    window.addEventListener('click', function(event) {
        document.querySelectorAll('.fixed.modal-transition').forEach(modal => {
            if (event.target === modal) {
                closeModals();
            }
        });
    });
    
    // Prevent form submission when pressing Enter in the textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
            }
        });
    });
    
    // Handle medication approval status dynamically with dark mode support
    const updateMedicationStatus = function(medicationId, status) {
        const statusCell = document.querySelector(`tr[data-medication-id="${medicationId}"] .status-cell`);
        
        if (statusCell) {
            statusCell.innerHTML = '';
            
            if (status === 'approved') {
                statusCell.innerHTML = `
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                        مقبول
                    </span>
                `;
            } else if (status === 'rejected') {
                statusCell.innerHTML = `
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                        مرفوض
                    </span>
                `;
            } else {
                statusCell.innerHTML = `
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                        معلق
                    </span>
                `;
            }
        }
    };
    
    // Review completion functions
    const checkAllMedicationsStatus = function() {
        const totalMedications = document.querySelectorAll('tr[data-medication-id]').length;
        const approvedMedications = document.querySelectorAll('tr[data-medication-id] .status-cell .bg-green-100, tr[data-medication-id] .status-cell .dark\\:bg-green-900\\/50').length;
        const rejectedMedications = document.querySelectorAll('tr[data-medication-id] .status-cell .bg-red-100, tr[data-medication-id] .status-cell .dark\\:bg-red-900\\/50').length;
        
        // Enable or disable the "Complete Review" button based on all medications being reviewed
        const completeReviewBtn = document.getElementById('completeReviewBtn');
        
        if (approvedMedications + rejectedMedications === totalMedications && totalMedications > 0) {
            completeReviewBtn.disabled = false;
            completeReviewBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            completeReviewBtn.classList.add('hover:bg-blue-600', 'dark:hover:bg-blue-700');
        } else {
            completeReviewBtn.disabled = true;
            completeReviewBtn.classList.add('opacity-50', 'cursor-not-allowed');
            completeReviewBtn.classList.remove('hover:bg-blue-600', 'dark:hover:bg-blue-700');
        }
        
        return {
            total: totalMedications,
            approved: approvedMedications,
            rejected: rejectedMedications
        };
    };
    
    // Initialize dark mode toggle state
    document.addEventListener('DOMContentLoaded', function() {
        // Initial check for medication status
        checkAllMedicationsStatus();
        
        // Initialize dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            // Update toggle state based on current mode
            darkModeToggle.checked = document.documentElement.classList.contains('dark');
            
            // Update toggle dot position
            const toggleDot = document.querySelector('.dot');
            if (toggleDot) {
                if (darkModeToggle.checked) {
                    toggleDot.classList.add('left-6');
                    toggleDot.classList.remove('left-1');
                } else {
                    toggleDot.classList.add('left-1');
                    toggleDot.classList.remove('left-6');
                }
            }
        }
        
        // Add event listener for dark mode toggle changes
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                const toggleDot = document.querySelector('.dot');
                if (this.checked) {
                    toggleDot.classList.remove('left-1');
                    toggleDot.classList.add('left-6');
                } else {
                    toggleDot.classList.remove('left-6');
                    toggleDot.classList.add('left-1');
                }
            });
        }
    });

    // Add medication status to each row
    document.querySelectorAll('tr').forEach(row => {
        if (row.querySelector('td')) {
            const medicationId = row.getAttribute('data-medication-id');
            if (medicationId) {
                // Add event listeners for approval/rejection buttons
                const approveButton = row.querySelector('.text-green-600, .dark\\:text-green-400');
                const rejectButton = row.querySelector('.text-red-600, .dark\\:text-red-400');
                
                if (approveButton) {
                    approveButton.parentElement.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        const form = this;
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateMedicationStatus(medicationId, 'approved');
                                checkAllMedicationsStatus();
                            } else {
                                alert(data.message || 'حدث خطأ أثناء محاولة الموافقة على الدواء');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء محاولة الموافقة على الدواء');
                        });
                    });
                }
                
                if (rejectButton) {
                    rejectButton.parentElement.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        const form = this;
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateMedicationStatus(medicationId, 'rejected');
                                checkAllMedicationsStatus();
                            } else {
                                alert(data.message || 'حدث خطأ أثناء محاولة رفض الدواء');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء محاولة رفض الدواء');
                        });
                    });
                }
            }
        }
    });
</script>
@endpush