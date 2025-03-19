<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصيدلية الذكية</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="relative">
    <!-- Background Image with Overlay -->
    <div class="fixed inset-0 -z-10">
        <!-- Using a pharmacy/medical themed background image -->
        <img src="https://img.freepik.com/free-photo/medical-banner-with-doctor-working-laptop_23-2149611234.jpg"
             alt="Modern Pharmacy Interior"
             class="object-cover w-full h-full brightness-[0.85]">
        <div class="absolute inset-0 bg-gradient-to-b from-teal-900/95 via-teal-900/98 to-gray-900/95"></div>
    </div>

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-mortar-pestle text-teal-600"></i>
                    <span class="bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                        الصيدلية الذكية
                    </span>
                </div>

                <!-- Navigation Links -->
                <ul class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <li>
                        <a href="#home"
                           class="text-gray-700 px-4 py-2 rounded-lg transition-all duration-300
                                  hover:bg-teal-50 hover:text-teal-600">
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="#products"
                           class="text-gray-700 px-4 py-2 rounded-lg transition-all duration-300
                                  hover:bg-teal-50 hover:text-teal-600">
                            منتجاتنا
                        </a>
                    </li>
                    <li>
                        <a href="#services"
                           class="text-gray-700 px-4 py-2 rounded-lg transition-all duration-300
                                  hover:bg-teal-50 hover:text-teal-600">
                            خدماتنا
                        </a>
                    </li>
                    <li>
                        <a href="#consult"
                           class="text-gray-700 px-4 py-2 rounded-lg transition-all duration-300
                                  hover:bg-teal-50 hover:text-teal-600">
                            استشارة صيدلي
                        </a>
                    </li>
                    <li>
                        <a href="/login"
                           class="bg-teal-600 text-white px-6 py-2 rounded-lg transition-all duration-300
                                  hover:bg-teal-700 shadow-lg hover:shadow-xl">
                            تسجيل دخول
                        </a>
                    </li>
                </ul>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Hidden by default) -->
    <div class="fixed inset-0 bg-white z-40 hidden">
        <div class="flex flex-col items-center justify-center h-full space-y-8">
            <a href="#home" class="text-gray-700 text-xl hover:text-teal-600 transition-colors">الرئيسية</a>
            <a href="#products" class="text-gray-700 text-xl hover:text-teal-600 transition-colors">منتجاتنا</a>
            <a href="#services" class="text-gray-700 text-xl hover:text-teal-600 transition-colors">خدماتنا</a>
            <a href="#consult" class="text-gray-700 text-xl hover:text-teal-600 transition-colors">استشارة صيدلي</a>
            <a href="/login" class="bg-teal-600 text-white px-8 py-3 rounded-lg hover:bg-teal-700 transition-colors">تسجيل دخول</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="relative min-h-screen pt-16">
        @yield("content")
    </main>

    <!-- Footer -->
    <footer class="bg-white py-12 mt-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800">عن الصيدلية</h3>
                    <p class="text-gray-600">صيدلية ذكية تقدم خدمات متكاملة على مدار الساعة</p>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800">روابط سريعة</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-teal-600">الأدوية</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-teal-600">المستلزمات الطبية</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-teal-600">العناية الشخصية</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800">تواصل معنا</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-phone"></i>
                            <span>920000000</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-envelope"></i>
                            <span>info@pharmacy.com</span>
                        </li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800">تابعنا</h3>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-600 hover:text-teal-600 text-xl">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-teal-600 text-xl">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-teal-600 text-xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-8 pt-8 text-center text-gray-600">
                جميع الحقوق محفوظة © 2025 الصيدلية الذكية
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const menuBtn = document.querySelector('button');
        const mobileMenu = document.querySelector('.fixed.inset-0.bg-white');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>