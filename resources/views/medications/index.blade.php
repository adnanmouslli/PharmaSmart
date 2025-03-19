@extends('layout.user')

@section('title', 'الأدوية المتوفرة')

@section('content')
<!-- Header Section -->
<div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-2xl p-6 shadow-sm mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-1">الأدوية المتوفرة</h1>
            <p class="text-gray-500 dark:text-gray-400">تصفح مجموعتنا الواسعة من الأدوية والمستحضرات الصيدلانية</p>
        </div>
        
        <!-- Search & Filter -->
        <div class="relative">
            <form action="{{ route('medications.index') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="ابحث عن دواء..." 
                       class="w-full sm:w-64 px-4 py-2 pr-10 rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:border-teal-500">
                <button type="submit" class="absolute top-0 right-0 h-full px-3 text-gray-400 dark:text-gray-300">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm mb-6 overflow-hidden">
    <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('medications.index') }}" 
           class="px-4 py-2 {{ !request('category') ? 'bg-teal-50 dark:bg-teal-600 dark:text-gray-100 text-teal-600' : 'bg-gray-50 dark:bg-gray-700 dark:text-gray-200 text-gray-600' }} rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
            جميع الأدوية
        </a>
        @foreach($categories as $category)
        <a href="{{ route('medications.index', ['category' => $category->id]) }}" 
           class="px-4 py-2 {{ request('category') == $category->id ? 'bg-teal-50 dark:bg-teal-600 dark:text-gray-100 text-teal-600' : 'bg-gray-50 dark:bg-gray-700 dark:text-gray-200 text-gray-600' }} rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
            <i class="fas {{ $category->icon }} ml-2"></i>
            {{ $category->name }}
        </a>
        @endforeach
    </div>
</div>

<!-- Medications Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($medications as $medication)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all">
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
                    @if($medication->requires_prescription)
                       
                        @if(Auth::user()->prescriptions()
                        ->whereHas('medications', function ($query) use ($medication) {
                            $query->where('medications.id', $medication->id)
                                ->where('prescription_medications.status', 'approved'); // تحقق من حالة الوصفة
                        })->exists())
                        <span class="px-2 py-1 bg-amber-100 dark:bg-green-700 dark:text-amber-100 text-green-700 rounded-full text-sm">
                            تم الموافقة على الوصفة
                        </span>
                    @else
                        <span class="px-2 py-1 bg-amber-100 dark:bg-amber-700 dark:text-amber-100 text-amber-700 rounded-full text-sm">
                            يتطلب وصفة
                        </span>
                    @endif

                    @else
                        <span class="px-2 py-1 bg-amber-100 dark:bg-amber-700 dark:text-amber-100 text-amber-700 rounded-full text-sm">
                            يتطلب وصفة
                        </span>
                    @endif

                </div>
            @endif
        </div>
        
        <div class="p-4">
            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $medication->name }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ Str::limit($medication->description, 60) }}</p>
            
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="fas {{ $medication->category->icon }} text-gray-400 dark:text-gray-500"></i>
                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $medication->category->name }}</span>
                </div>
                <span class="font-bold text-teal-600 dark:text-teal-400">{{ number_format($medication->price, 2) }} ر.س</span>
            </div>

            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-capsules text-gray-400 dark:text-gray-500"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $medication->dosage_form }}</span>
                @if($medication->strength)
                    <span class="text-sm text-gray-400 dark:text-gray-500">|</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $medication->strength }}</span>
                @endif
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-sm {{ $medication->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    {{ $medication->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                </span>
               

                @if($medication->requires_prescription)
                    @if(Auth::user()->prescriptions()
                        ->whereHas('medications', function ($query) use ($medication) {
                            $query->where('medications.id', $medication->id)
                                ->where('prescription_medications.status', 'approved'); // تحقق من حالة الوصفة
                        })->exists())
                       {{-- <form 
                        action="{{ route('orders.index') }}" 
                        method="GET">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-teal-600 dark:bg-teal-700 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-800 transition-colors text-sm">
                                طلب الدواء
                            </button>
                        </form> --}}
                        <a
                        class="px-4 py-2 bg-teal-600 dark:bg-teal-700 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-800 transition-colors text-sm"
                        href="/orders" >طلب الدواء</a>
                    @else
                        <a href="{{ route('prescriptions.create') }}" 
                        class="px-4 py-2 bg-gray-500 dark:bg-gray-700 text-white rounded-lg cursor-not-allowed text-sm">
                            رفع وصفة
                        </a>
                    @endif
                @else
                    {{-- <form 
                    action="{{ route('orders.index') }}" 
                    method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-teal-600 dark:bg-teal-700 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-800 transition-colors text-sm">
                                طلب الدواء
                        </button>
                    </form> --}}

                    <a
                    class="px-4 py-2 bg-teal-600 dark:bg-teal-700 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-800 transition-colors text-sm"
                    href="/orders" >طلب الدواء</a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl">
            <div class="w-16 h-16 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-2xl"></i>
            </div>
            <h4 class="text-gray-800 dark:text-gray-200 font-medium mb-2">لا توجد أدوية متوفرة</h4>
            <p class="text-gray-500 dark:text-gray-400">جرب تغيير معايير البحث</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $medications->links() }}
</div>
@endsection