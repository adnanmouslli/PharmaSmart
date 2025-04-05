<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>لوحة تحكم الصيدلية الذكية - @yield('title', 'الرئيسية')</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-sm dark:shadow-gray-700/10 transition-colors duration-200">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button & Logo -->
                <div class="flex items-center gap-4">
                    <button @click="$store.sidebar.toggle()" 
                            class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-mortar-pestle text-teal-600 dark:text-teal-500 text-2xl"></i>
                        <span class="text-xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                            الصيدلية الذكية - لوحة التحكم
                        </span>
                    </div>
                </div>

                <!-- Search Bar - Hidden on Mobile -->
                <div class="hidden md:flex flex-1 max-w-xl mx-6">
                    <div class="relative w-full">
                        <input type="text" 
                               placeholder="بحث سريع..." 
                               class="w-full py-2 px-4 pr-10 rounded-lg border border-gray-200 dark:border-gray-700 
                                      bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                      focus:outline-none focus:border-teal-500 dark:focus:border-teal-400
                                      placeholder-gray-500 dark:placeholder-gray-400
                                      transition-colors duration-200">
                        <i class="fas fa-search absolute top-3 right-3 text-gray-400 dark:text-gray-500"></i>
                    </div>
                </div>

                <!-- User Menu & Actions -->
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    {{-- <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button> --}}

                    <!-- Notifications -->
                    {{-- <div x-data="{ isOpen: false }" class="relative">
                        <button @click="isOpen = !isOpen" 
                                class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <i class="fas fa-bell text-xl"></i>
                            @if(($pendingOrdersCount ?? 0) + ($pendingPrescriptionsCount ?? 0) > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                    {{ ($pendingOrdersCount ?? 0) + ($pendingPrescriptionsCount ?? 0) }}
                                </span>
                            @endif
                        </button>

                        <!-- Notifications Dropdown -->
                        <div x-show="isOpen" 
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute left-0 mt-2 w-80 rounded-lg bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700/10 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-semibold text-gray-800 dark:text-white">الإشعارات</h3>
                            </div>
                            <div class="max-h-80 overflow-y-auto py-2">
                                @if(($pendingOrdersCount ?? 0) > 0)
                                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-500 dark:text-blue-300">
                                                <i class="fas fa-shopping-basket"></i>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                                    <span class="font-semibold">{{ $pendingOrdersCount ?? 0 }}</span> طلبات جديدة بحاجة إلى مراجعة
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                
                                @if(($pendingPrescriptionsCount ?? 0) > 0)
                                    <a href="{{ route('admin.prescriptions.index', ['status' => 'pending']) }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center text-yellow-500 dark:text-yellow-300">
                                                <i class="fas fa-file-medical"></i>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                                    <span class="font-semibold">{{ $pendingPrescriptionsCount ?? 0 }}</span> وصفات طبية بحاجة إلى مراجعة
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                
                                @if(($unreadConsultationsCount ?? 0) > 0)
                                    <a href="{{ route('admin.consultations.index') }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-500 dark:text-green-300">
                                                <i class="fas fa-comment-medical"></i>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                                    <span class="font-semibold">{{ $unreadConsultationsCount ?? 0 }}</span> استشارات جديدة بحاجة إلى رد
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                
                                @if(!(($pendingOrdersCount ?? 0) + ($pendingPrescriptionsCount ?? 0) + ($unreadConsultationsCount ?? 0) > 0))
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-gray-500 dark:text-gray-400">لا توجد إشعارات جديدة</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <!-- User Dropdown -->
                    <div x-data="{ isOpen: false }" class="relative">
                        <button @click="isOpen = !isOpen" 
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            {{-- <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                                 alt="صورة المستخدم" 
                                 class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 object-cover"> --}}
                            <span class="hidden md:inline text-gray-700 dark:text-gray-200">
                                {{ auth()->user()->first_name }}
                            </span>
                            <i class="fas fa-chevron-down text-sm text-gray-500 dark:text-gray-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="isOpen" 
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute left-0 mt-2 w-48 rounded-lg bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700/10 py-2">
                            {{-- <a href="{{ route('admin.profile.edit') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fas fa-user w-5"></i>
                                الملف الشخصي
                            </a>
                            <a href="{{ route('admin.settings.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fas fa-cog w-5"></i>
                                الإعدادات
                            </a> --}}
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2 text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside x-data="$store.sidebar" 
           :class="{'translate-x-0': open, 'translate-x-full': !open}"
           class="fixed right-0 top-0 pt-16 h-screen w-64 bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700/10 transform transition-all duration-300 lg:translate-x-0 z-40">
        
        <!-- Sidebar Content -->
        <div class="h-full overflow-y-auto py-4">
            <!-- Pharmacist Info -->
            <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-700 mb-4">
                <div class="flex flex-col items-center">
                    {{-- <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-20 h-20 rounded-full border-2 border-teal-400 dark:border-teal-500 object-cover mb-3"> --}}
                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                    <span class="text-teal-600 dark:text-teal-400 text-sm">صيدلي</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.dashboard') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                    <span>لوحة المعلومات</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.orders*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-shopping-basket w-5 h-5"></i>
                    <span>الطلبات</span>
                    @if(($pendingOrdersCount ?? 0) > 0)
                        <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full mr-auto">{{ $pendingOrdersCount ?? 0 }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.prescriptions.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.prescriptions*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-file-medical w-5 h-5"></i>
                    <span>الوصفات الطبية</span>
                    @if(($pendingPrescriptionsCount ?? 0) > 0)
                        <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full mr-auto">{{ $pendingPrescriptionsCount ?? 0 }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.medications.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.medications*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-pills w-5 h-5"></i>
                    <span>الأدوية</span>
                </a>
{{--                 
                <a href="{{ route('admin.consultations.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.consultations*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-comment-medical w-5 h-5"></i>
                    <span>الاستشارات</span>
                    @if(($unreadConsultationsCount ?? 0) > 0)
                        <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full mr-auto">{{ $unreadConsultationsCount ?? 0 }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.users*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-users w-5 h-5"></i>
                    <span>العملاء</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.categories*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-tags w-5 h-5"></i>
                    <span>الأقسام</span>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.reports*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-chart-pie w-5 h-5"></i>
                    <span>التقارير</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200
                         {{ request()->routeIs('admin.settings*') ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-cog w-5 h-5"></i>
                    <span>الإعدادات</span>
                </a> --}}
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="pt-16 lg:mr-64 transition-all duration-300">
        <div class="p-6">
            <!-- Page Title & Overview -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-1">@yield('title', 'لوحة التحكم')</h1>
                @if(View::hasSection('description'))
                    <p class="text-gray-600 dark:text-gray-400">@yield('description')</p>
                @endif
            </div>
            
            <!-- Page Content -->
            <div class="space-y-6">
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Mobile Sidebar Backdrop -->
    <div 
        x-show="$store.sidebar.open" 
        x-cloak
        @click="$store.sidebar.open = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm lg:hidden z-30"></div>

    <script>
        // Initialize Alpine Store
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            });

            // Check for dark mode preference
            if (!localStorage.getItem('darkMode')) {
                const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                localStorage.setItem('darkMode', systemDarkMode);
                document.documentElement.classList.toggle('dark', systemDarkMode);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>