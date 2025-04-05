@extends('admin.layouts.app')

@section('title', 'إدارة الأدوية')
@section('description', 'عرض وإدارة جميع الأدوية في النظام')

@section('content')
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">إدارة الأدوية</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">عرض وإدارة جميع الأدوية في النظام</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <form action="{{ route('admin.medications.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1 min-w-[200px]">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                            </span>
                            <input type="text" name="search" placeholder="بحث عن دواء..." value="{{ request('search') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 pr-10 py-2
                                          bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                          placeholder-gray-400 dark:placeholder-gray-500 transition-colors duration-200">
                        </div>
                        <div class="relative flex-1 min-w-[200px]">
                            <select name="category_id" 
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 pr-4
                                           bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                           focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                           transition-colors duration-200">
                                <option value="">جميع الأقسام</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                text-white rounded-lg px-4 py-2 flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-filter ml-2"></i>
                            تصفية
                        </button>
                        <a href="{{ route('admin.medications.create') }}" 
                           class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                  text-white rounded-lg px-4 py-2 flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-plus ml-2"></i>
                            إضافة دواء جديد
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-all duration-200">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">خيارات التصفية</h3>
            <form action="{{ route('admin.medications.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- الحفاظ على البحث الحالي -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <!-- تصفية حسب القسم -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">القسم</label>
                    <select id="category_id" name="category_id" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   transition-colors duration-200">
                        <option value="">جميع الأقسام</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- تصفية حسب الحالة -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة</label>
                    <select id="status" name="status" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   transition-colors duration-200">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                
                <!-- تصفية حسب الوصفة الطبية -->
                <div>
                    <label for="prescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوصفة الطبية</label>
                    <select id="prescription" name="prescription" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   transition-colors duration-200">
                        <option value="">الكل</option>
                        <option value="required" {{ request('prescription') == 'required' ? 'selected' : '' }}>يتطلب وصفة طبية</option>
                        <option value="not_required" {{ request('prescription') == 'not_required' ? 'selected' : '' }}>لا يتطلب وصفة طبية</option>
                    </select>
                </div>
                
                <!-- تصفية حسب المخزون -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المخزون</label>
                    <select id="stock" name="stock" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                   transition-colors duration-200">
                        <option value="">الكل</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>منخفض (أقل من 10)</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>نفد من المخزون</option>
                    </select>
                </div>
                
                <div class="md:col-span-4 flex justify-between mt-2">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                          text-white rounded-lg px-6 py-2 flex items-center transition-colors duration-200">
                        <i class="fas fa-filter ml-2"></i>
                        تطبيق التصفية
                    </button>
                    
                    @if(request()->anyFilled(['category_id', 'status', 'prescription', 'stock']))
                        <a href="{{ route('admin.medications.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                                 text-gray-700 dark:text-gray-200 rounded-lg px-6 py-2 flex items-center transition-colors duration-200">
                            <i class="fas fa-times-circle ml-2"></i>
                            إلغاء التصفية
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Medications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/10 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
        <!-- Table Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                <span class="text-gray-600 dark:text-gray-400">إجمالي الأدوية: {{ $medications->total() }}</span>
            </div>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <form action="{{ route('admin.medications.index') }}" method="GET" id="per-page-form">
                        <!-- الحفاظ على التصفية الحالية -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('category_id'))
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        @endif
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if(request('prescription'))
                            <input type="hidden" name="prescription" value="{{ request('prescription') }}">
                        @endif
                        @if(request('stock'))
                            <input type="hidden" name="stock" value="{{ request('stock') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        
                        <select id="per-page" name="per_page" onchange="document.getElementById('per-page-form').submit()"
                                class="rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                       transition-colors duration-200">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 لكل صفحة</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 لكل صفحة</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 لكل صفحة</option>
                        </select>
                    </form>
                </div>
                {{-- <a href="{{ route('admin.medications.export') }}" 
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
                                الصورة
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                اسم الدواء
                                <a href="{{ route('admin.medications.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </a>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                القسم
                                <a href="{{ route('admin.medications.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'category_id', 'direction' => request('sort') === 'category_id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </a>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                السعر
                                <a href="{{ route('admin.medications.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </a>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center justify-between">
                                المخزون
                                <a href="{{ route('admin.medications.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'stock', 'direction' => request('sort') === 'stock' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-sort"></i>
                                </a>
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
                    @forelse($medications as $medication)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-md object-cover border border-gray-200 dark:border-gray-600" 
                                         src="{{ $medication->image ? asset('storage/'.$medication->image) : asset('images/default-medication.png') }}" 
                                         alt="{{ $medication->name }}">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $medication->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @if($medication->manufacturer)
                                        <span class="ml-2">{{ $medication->manufacturer }}</span>
                                    @endif
                                    @if($medication->strength)
                                        <span>{{ $medication->strength }}</span>
                                    @endif
                                </div>
                                @if($medication->requires_prescription)
                                    <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                        <i class="fas fa-prescription ml-1"></i>
                                        يتطلب وصفة طبية
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 dark:bg-teal-900/50 text-teal-800 dark:text-teal-300">
                                    {{ $medication->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($medication->price, 2) }} ريال
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($medication->stock > 10)
                                    <span class="text-green-600 dark:text-green-400">{{ $medication->stock }}</span>
                                @elseif($medication->stock > 0)
                                    <span class="text-yellow-600 dark:text-yellow-400">{{ $medication->stock }}</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">نفد من المخزون</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($medication->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                        نشط
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <a href="{{ route('admin.medications.show', $medication) }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.medications.edit', $medication) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                        <i class="fas fa-edit"></i>
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
                                            <button type="button" 
                                                    onclick="document.getElementById('update-stock-modal-{{ $medication->id }}').classList.remove('hidden')" 
                                                    class="w-full text-right block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-boxes ml-2"></i>
                                                تحديث المخزون
                                            </button>
                                            <form action="{{ route('admin.medications.toggle-active', $medication) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                    <i class="fas fa-{{ $medication->is_active ? 'times' : 'check' }} ml-2"></i>
                                                    {{ $medication->is_active ? 'تعطيل' : 'تفعيل' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.medications.destroy', $medication) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدواء؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                                    <i class="fas fa-trash-alt ml-2"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Modal for updating stock -->
                        <div id="update-stock-modal-{{ $medication->id }}" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-gray-900/30 w-full max-w-md">
                                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">تحديث المخزون</h3>
                                    <button onclick="document.getElementById('update-stock-modal-{{ $medication->id }}').classList.add('hidden')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
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
                                        <label for="stock-{{ $medication->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            المخزون الجديد
                                        </label>
                                        <input type="number" id="stock-{{ $medication->id }}" name="stock" value="{{ $medication->stock }}" min="0" 
                                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                                     bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                                     focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                                     transition-colors duration-200">
                                    </div>
                                    <div class="mb-4">
                                        <label for="note-{{ $medication->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            ملاحظات (اختياري)
                                        </label>
                                        <textarea id="note-{{ $medication->id }}" name="note" rows="3" 
                                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 py-2 px-3
                                                         bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                                         focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400
                                                         transition-colors duration-200"></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" 
                                                onclick="document.getElementById('update-stock-modal-{{ $medication->id }}').classList.add('hidden')"
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
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-pills text-4xl mb-4 text-gray-400 dark:text-gray-500"></i>
                                    <p>لا توجد أدوية متطابقة مع بحثك</p>
                                    <a href="{{ route('admin.medications.create') }}" class="mt-4 bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 
                                            text-white rounded-lg px-4 py-2 flex items-center transition-colors duration-200">
                                        <i class="fas fa-plus ml-2"></i>
                                        إضافة دواء جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            
        </div> 
        
    </div>   
    
@endsection    