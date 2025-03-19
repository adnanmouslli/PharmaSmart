<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>الصيدلية الذكية - @yield('title', 'لوحة التحكم')</title>
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
                            الصيدلية الذكية
                        </span>
                    </div>
                </div>

                <!-- Search Bar - Hidden on Mobile -->
                <div class="hidden md:flex flex-1 max-w-xl mx-6">
                    <div class="relative w-full">
                        <input type="text" 
                               placeholder="ابحث عن الأدوية والمنتجات..." 
                               class="w-full py-2 px-4 pr-10 rounded-lg border border-gray-200 dark:border-gray-700 
                                      bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                      focus:outline-none focus:border-teal-500 dark:focus:border-teal-400
                                      placeholder-gray-500 dark:placeholder-gray-400
                                      transition-colors duration-200">
                        <i class="fas fa-search absolute top-3 right-3 text-gray-400 dark:text-gray-500"></i>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    {{-- <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button> --}}

                    {{-- <!-- Notifications -->
                    <div x-data="{ isOpen: false }" class="relative">
                        <button @click="isOpen = !isOpen" 
                                class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
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
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($notifications ?? [] as $notification)
                                    <a href="{{ $notification->link }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <p class="text-sm text-gray-800 dark:text-gray-200">{{ $notification->message }}</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-gray-500 dark:text-gray-400">لا توجد إشعارات جديدة</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div> --}}

                    <!-- User Dropdown -->
                    <div x-data="{ isOpen: false }" class="relative">
                        <button @click="isOpen = !isOpen" 
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <img src="{{ auth()->user()->avatar_url ?? 'http://127.0.0.1:8000/avoter.png' }}" 
                                 alt="صورة المستخدم" 
                                 class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700">
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
    <aside x-data="{ isOpen: true }" 
           :class="{'translate-x-0': isOpen, 'translate-x-full': !isOpen}"
           class="fixed right-0 top-0 pt-16 h-screen w-64 bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700/10 transform transition-all duration-300 lg:translate-x-0">
        
        <!-- Close button for mobile -->
        <button @click="isOpen = false" 
                class="lg:hidden absolute left-4 top-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="h-full overflow-y-auto">
            <!-- User Profile Summary -->
            <div class="px-4 py-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <img src="{{ auth()->user()->avatar_url ?? 'http://127.0.0.1:8000/avoter.png' }}" 
                         alt="صورة المستخدم" 
                         class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-6">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('home.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('home.index') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-home w-5"></i>
                            <span>الرئيسية</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prescriptions.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('prescriptions.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-prescription w-5"></i>
                            <span>الوصفات الطبية</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medications.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('medications.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-pills w-5"></i>
                            <span>الأدوية</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('orders.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>الطلبات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('consultations.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('consultations.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-user-md w-5"></i>
                            <span>استشارات صيدلية</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route('reminders.index')}}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('reminders.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-clock w-5"></i>
                            <span>تذكيرات الدواء</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                {{ request()->routeIs('insurance.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fas fa-shield-alt w-5"></i>
                            <span>التأمين الطبي</span>
                        </a>
                    </li> --}}
                </ul>

                <!-- Health Section -->
                {{-- <div class="mt-8">
                    <h3 class="px-4 text-sm font-medium text-gray-400 dark:text-gray-500">الصحة والرعاية</h3>
                    <ul class="mt-2 space-y-1">
                        <li>
                            <a href="" 
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                    {{ request()->routeIs('health.records.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-file-medical w-5"></i>
                                <span>السجل الصحي</span>
                            </a>
                        </li>
                        <li>
                            <a href="" 
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                                    {{ request()->routeIs('health.articles.*') ? 'text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-500/10 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-newspaper w-5"></i>
                                <span>مقالات صحية</span>
                            </a>
                        </li>
                    </ul>
                </div> --}}
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:mr-64 pt-16">
        <div class="p-6 max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            @if(isset($breadcrumbs))
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home.index') }}" 
                               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <i class="fas fa-home ml-2"></i>
                                الرئيسية
                            </a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumb)
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-left text-gray-400 dark:text-gray-600 mx-2"></i>
                                @if($breadcrumb['url'])
                                    <a href="{{ $breadcrumb['url'] }}" 
                                       class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @else
                                    <span class="text-gray-700 dark:text-gray-200">{{ $breadcrumb['title'] }}</span>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Notification Toast -->
    <div x-data="{ 
            notifications: [], 
            add(message, type = 'success') {
                this.notifications.push({
                    id: Date.now(),
                    message,
                    type,
                    show: true
                });
                setTimeout(() => {
                    this.remove(this.notifications[this.notifications.length - 1].id);
                }, 5000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(notification => notification.id !== id);
            }
        }" 
        class="fixed bottom-4 left-4 z-50 space-y-4">
        
        <!-- Success Message -->
        @if(session('success'))
        <div x-init="add('{{ session('success') }}', 'success')"
             x-show="notifications.length > 0"
             class="pointer-events-none">
            <template x-for="notification in notifications" :key="notification.id">
                <div x-show="notification.show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-2"
                     class="flex items-center gap-3 px-6 py-3 rounded-lg shadow-lg dark:shadow-gray-900/20"
                     :class="notification.type === 'success' ? 'bg-emerald-500 dark:bg-emerald-600' : 'bg-red-500 dark:bg-red-600'">
                    <span class="flex-shrink-0 text-white">
                        <template x-if="notification.type === 'success'">
                            <i class="fas fa-check-circle text-xl"></i>
                        </template>
                        <template x-if="notification.type === 'error'">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                        </template>
                    </span>
                    <p class="text-white" x-text="notification.message"></p>
                </div>
            </template>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div x-init="add('{{ session('error') }}', 'error')"
             x-show="notifications.length > 0"
             class="pointer-events-none">
            <!-- Content same as success but with error styling -->
        </div>
        @endif
    </div>

    <!-- Initialize Alpine Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: window.innerWidth >= 1024,
                toggle() {
                    this.open = !this.open;
                },
            });

            // Set initial dark mode based on system preference or saved setting
            if (!localStorage.getItem('darkMode')) {
                const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                localStorage.setItem('darkMode', systemDarkMode);
                document.documentElement.classList.toggle('dark', systemDarkMode);
            }

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', (event) => {
                if (window.innerWidth < 1024 && 
                    !event.target.closest('aside') && 
                    !event.target.closest('#sidebar-toggle')) {
                    Alpine.store('sidebar').open = false;
                }
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    Alpine.store('sidebar').open = true;
                }
            });

            // Listen for system dark mode changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('darkMode')) {
                    document.documentElement.classList.toggle('dark', e.matches);
                }
            });
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>