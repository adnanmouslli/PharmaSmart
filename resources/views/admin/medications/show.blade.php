@extends('admin.layouts.app')

@section('title', 'تفاصيل الدواء')
@section('description', 'عرض كافة معلومات الدواء')

@section('content')
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        تفاصيل الدواء: {{ $medication->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">عرض كافة المعلومات المتعلقة بالدواء</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('admin.medications.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                              text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة إلى القائمة
                    </a>
                    <a href="{{ route('admin.medications.edit', $medication) }}" 
                       class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 
                              text-white rounded-lg px-4 py-2 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل الدواء
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for updating stock -->
    <div id="update-stock-modal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-gray-900/30 w-full max-w-md">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تحديث المخزون</h3>
                <button onclick="document.getElementById('update-stock-modal').classList.add('hidden')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.medications.update-stock', $medication) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <p class="mb-2 text-gray-700 dark:text-gray-300">
                        <span class="font-bold">الدواء:</span> {{ $medication->name }}
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <span class="font-bold">المخزون الحالي:</span> {{ $medication->stock }}
                    </p>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        المخزون الجديد
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ $medication->stock }}" min="0" 
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                 transition-colors duration-200">
                </div>
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        ملاحظات (اختياري)
                    </label>
                    <textarea id="note" name="note" rows="3" 
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                     bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                     focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                     transition-colors duration-200"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" 
                            onclick="document.getElementById('update-stock-modal').classList.add('hidden')"
                            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                                  text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 mr-2">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                  text-white rounded-lg px-4 py-2">
                        تحديث المخزون
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-gray-900/30 w-full max-w-md">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تأكيد الحذف</h3>
                <button onclick="document.getElementById('delete-modal').classList.add('hidden')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <span class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
                        </span>
                    </div>
                    <p class="text-center text-gray-700 dark:text-gray-300">
                        هل أنت متأكد من حذف هذا الدواء؟ <br>
                        <span class="font-semibold">{{ $medication->name }}</span>
                    </p>
                    <p class="text-center text-red-600 dark:text-red-400 text-sm mt-2">
                        هذا الإجراء لا يمكن التراجع عنه.
                    </p>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="button" 
                            onclick="document.getElementById('delete-modal').classList.add('hidden')"
                            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                                  text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2">
                        إلغاء
                    </button>
                    <form action="{{ route('admin.medications.destroy', $medication) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 
                                      text-white rounded-lg px-4 py-2">
                            تأكيد الحذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Medication Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">معلومات الدواء</h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">اسم الدواء</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">القسم</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->category->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">الشركة المصنعة</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->manufacturer ?: 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">شكل الجرعة</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->dosage_form }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">التركيز</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->strength ?: 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">السعر</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ number_format($medication->price, 2) }} ريال</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">المخزون</h4>
                            <p class="text-base 
                                @if($medication->stock > 10) text-green-600 dark:text-green-400 
                                @elseif($medication->stock > 0) text-yellow-600 dark:text-yellow-400 
                                @else text-red-600 dark:text-red-400 @endif">
                                {{ $medication->stock > 0 ? $medication->stock : 'نفد من المخزون' }}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">تاريخ الإضافة</h4>
                            <p class="text-base text-gray-900 dark:text-white">{{ $medication->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">الوصف</h3>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $medication->description ?: 'لا يوجد وصف متاح لهذا الدواء.' }}
                    </p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">إجراءات</h3>
                </div>
                
                <div class="p-6 flex flex-wrap gap-4">
                    <button type="button" onclick="document.getElementById('update-stock-modal').classList.remove('hidden')"
                            class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                   text-white rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                        <i class="fas fa-boxes ml-2"></i>
                        تحديث المخزون
                    </button>
                    
                    <form action="{{ route('admin.medications.toggle-active', $medication) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="{{ $medication->is_active ? 'bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600' }} 
                                text-white rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                            <i class="fas fa-{{ $medication->is_active ? 'times' : 'check' }} ml-2"></i>
                            {{ $medication->is_active ? 'تعطيل الدواء' : 'تفعيل الدواء' }}
                        </button>
                    </form>
                    
                    <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')" 
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 
                                  text-white rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                        <i class="fas fa-trash-alt ml-2"></i>
                        حذف الدواء
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Side Information -->
        <div class="space-y-6">
            <!-- Image Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">صورة الدواء</h3>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-center">
                        @if($medication->image)
                            <img src="{{ asset('storage/'.$medication->image) }}" alt="{{ $medication->name }}" 
                                 class="rounded-lg max-h-80 object-contain border border-gray-200 dark:border-gray-700">
                        @else
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg w-full h-60 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 dark:text-gray-500 text-4xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-center mt-2">
                                لا توجد صورة متاحة
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">حالة الدواء</h3>
                </div>
                
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 rounded-full flex items-center justify-center text-white text-4xl mb-4
                                    {{ $medication->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                            <i class="fas fa-{{ $medication->is_active ? 'check' : 'times' }}"></i>
                        </div>
                        <h4 class="text-xl font-semibold mb-2 
                                   {{ $medication->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $medication->is_active ? 'نشط' : 'غير نشط' }}
                        </h4>
                        <p class="text-gray-500 dark:text-gray-400 text-center">
                            {{ $medication->is_active ? 'هذا الدواء متاح للبيع حالياً.' : 'هذا الدواء غير متاح للبيع حالياً.' }}
                        </p>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700">
                    
                    <div class="flex flex-col space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">يتطلب وصفة طبية</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $medication->requires_prescription ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                                {{ $medication->requires_prescription ? 'نعم' : 'لا' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>   

        </div>   

    </div>    

@endsection    