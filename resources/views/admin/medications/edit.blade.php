@extends('admin.layouts.app')

@section('title', 'تعديل الدواء')
@section('description', 'تعديل معلومات الدواء')

@section('content')
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">تعديل الدواء</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">تعديل معلومات الدواء: {{ $medication->name }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.medications.show', $medication) }}" 
                       class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                              text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة إلى تفاصيل الدواء
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Medication Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
        <form action="{{ route('admin.medications.update', $medication) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- معلومات أساسية -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        معلومات أساسية
                    </h3>
                </div>
                
                <!-- اسم الدواء -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        اسم الدواء <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $medication->name) }}" required 
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('name') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- القسم -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        القسم <span class="text-red-600">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   @error('category_id') border-red-500 dark:border-red-500 @enderror
                                   transition-colors duration-200">
                        <option value="">اختر القسم</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $medication->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- الشركة المصنعة -->
                <div>
                    <label for="manufacturer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        الشركة المصنعة
                    </label>
                    <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $medication->manufacturer) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('manufacturer') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('manufacturer')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- شكل الجرعة -->
                <div>
                    <label for="dosage_form" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        شكل الجرعة <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="dosage_form" name="dosage_form" value="{{ old('dosage_form', $medication->dosage_form) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('dosage_form') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('dosage_form')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- التركيز -->
                <div>
                    <label for="strength" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        التركيز
                    </label>
                    <input type="text" id="strength" name="strength" value="{{ old('strength', $medication->strength) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('strength') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('strength')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- السعر -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        السعر (ريال) <span class="text-red-600">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', $medication->price) }}" required step="0.01" min="0"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('price') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- المخزون -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        المخزون <span class="text-red-600">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $medication->stock) }}" required min="0"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                  @error('stock') border-red-500 dark:border-red-500 @enderror
                                  transition-colors duration-200">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- الإعدادات -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4 mt-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                        الإعدادات
                    </h3>
                </div>
                
                <!-- خيارات -->
                <div class="md:col-span-2 flex flex-col sm:flex-row gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="requires_prescription" name="requires_prescription" value="1" 
                               {{ old('requires_prescription', $medication->requires_prescription) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <label for="requires_prescription" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                            يتطلب وصفة طبية
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $medication->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <label for="is_active" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                            نشط (متاح للبيع)
                        </label>
                    </div>
                </div>
                
                <!-- الوصف -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        الوصف
                    </label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                     bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                     focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                     @error('description') border-red-500 dark:border-red-500 @enderror
                                     transition-colors duration-200">{{ old('description', $medication->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- الصورة -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        صورة الدواء
                    </label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">الصورة الحالية:</p>
                            @if($medication->image)
                                <img src="{{ asset('storage/'.$medication->image) }}" alt="{{ $medication->name }}" 
                                     class="rounded-md h-40 object-cover border border-gray-200 dark:border-gray-700">
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-md h-40 flex items-center justify-center">
                                    <span class="text-gray-400 dark:text-gray-500">لا توجد صورة</span>
                                </div>
                            @endif
                        </div>
                        
                                                    <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">تحميل صورة جديدة:</p>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-teal-600 dark:text-teal-400 hover:text-teal-500 dark:hover:text-teal-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500 dark:focus-within:ring-offset-gray-800">
                                            <span>تحميل ملف</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pr-1">أو سحب وإفلات</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PNG, JPG, GIF حتى 2MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            <button class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
            text-white rounded-lg px-4 py-2 flex items-center justify-center transition-colors duration-200" type="submit">تعديل بيانات الدواء</button>
        </form>
        
    </div>   

@endsection    