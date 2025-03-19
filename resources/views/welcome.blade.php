@extends("layout.app")

@section("content")
<!-- Hero Section -->
<div class="relative min-h-[90vh] flex items-center justify-center px-4 py-20">
    <!-- Main Content -->
    <div class="relative text-center max-w-4xl mx-auto space-y-12">
        <!-- Logo & Title -->
        <div class="space-y-8">
            <div class="rounded-full bg-white/10 backdrop-blur-md w-24 h-24 mx-auto flex items-center justify-center">
                <i class="fas fa-mortar-pestle text-6xl text-teal-400 animate-float"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold">
                <span class="text-white">
                    صيدليتك الذكية
                </span>
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                نقدم لك تجربة صيدلانية متكاملة مع خدمات رقمية متطورة وفريق متخصص
            </p>
        </div>

        <!-- Main Features -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
            <div class="p-6 rounded-xl bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all">
                <i class="fas fa-pills text-teal-400 text-2xl"></i>
                <p class="text-white mt-2">دواء موثوق</p>
            </div>
            <div class="p-6 rounded-xl bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all">
                <i class="fas fa-truck text-teal-400 text-2xl"></i>
                <p class="text-white mt-2">توصيل سريع</p>
            </div>
            <div class="p-6 rounded-xl bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all">
                <i class="fas fa-user-md text-teal-400 text-2xl"></i>
                <p class="text-white mt-2">استشارة طبية</p>
            </div>
            <div class="p-6 rounded-xl bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all">
                <i class="fas fa-clock text-teal-400 text-2xl"></i>
                <p class="text-white mt-2">24/7 خدمة</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
            <a href="/order-now"
               class="px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-xl transition-all
                      flex items-center justify-center gap-2 text-lg shadow-lg hover:shadow-xl">
                <i class="fas fa-shopping-cart"></i>
                اطلب الآن
            </a>
            <a href="/consultations"
               class="px-8 py-4 bg-white hover:bg-gray-100 text-teal-600 rounded-xl transition-all
                      flex items-center justify-center gap-2 text-lg shadow-lg hover:shadow-xl">
                <i class="fas fa-user-md"></i>
                استشارة صيدلي
            </a>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="relative py-32 px-4 bg-white">
    <div class="container mx-auto max-w-6xl">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <span class="inline-block px-4 py-1 rounded-full bg-teal-50 text-teal-600 text-sm font-semibold mb-6">
                الأقسام الرئيسية
            </span>
            <h2 class="text-4xl font-bold text-gray-900 mb-8">
                تسوق حسب القسم
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                اكتشف مجموعة متنوعة من المنتجات الصيدلانية والصحية
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Category 1 -->
            <div class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all">
                <img src="https://img.freepik.com/free-photo/various-pills-pharmacy_1339-2242.jpg" 
                     alt="Medications" 
                     class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-black/90 transition-all"></div>
                <div class="absolute bottom-0 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">الأدوية</h3>
                    <p class="text-gray-200 mb-4">تشكيلة واسعة من الأدوية المرخصة</p>
                    <a href="/medications" class="inline-flex items-center text-teal-400 hover:text-teal-300">
                        تصفح الأدوية
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>

            <!-- Category 2 -->
            <div class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all">
                <img src="https://img.freepik.com/free-photo/medical-supplies-arranged-table_23-2150254071.jpg" 
                     alt="Medical Supplies" 
                     class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-black/90 transition-all"></div>
                <div class="absolute bottom-0 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">المستلزمات الطبية</h3>
                    <p class="text-gray-200 mb-4">كل ما تحتاجه من مستلزمات طبية</p>
                    <a href="/supplies" class="inline-flex items-center text-teal-400 hover:text-teal-300">
                        تصفح المستلزمات
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>

            <!-- Category 3 -->
            <div class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all">
                <img src="https://img.freepik.com/free-photo/flat-lay-natural-self-care-products-composition_23-2148990019.jpg" 
                     alt="Personal Care" 
                     class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-black/90 transition-all"></div>
                <div class="absolute bottom-0 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">العناية الشخصية</h3>
                    <p class="text-gray-200 mb-4">منتجات العناية والتجميل</p>
                    <a href="/personal-care" class="inline-flex items-center text-teal-400 hover:text-teal-300">
                        تصفح المنتجات
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- App Features Section -->
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">مميزات تطبيقنا</h2>
            <p class="text-lg text-gray-600">اكتشف كيف يمكن لتطبيقنا أن يجعل حياتك أسهل</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <div class="w-14 h-14 bg-teal-50 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-search text-2xl text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">بحث سهل</h3>
                <p class="text-gray-600">ابحث عن الأدوية والمنتجات بسهولة</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <div class="w-14 h-14 bg-teal-50 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-bell text-2xl text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">تنبيهات الدواء</h3>
                <p class="text-gray-600">تذكير بمواعيد تناول الأدوية</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <div class="w-14 h-14 bg-teal-50 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-file-medical text-2xl text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">الوصفات الطبية</h3>
                <p class="text-gray-600">تخزين وإدارة الوصفات الطبية</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <div class="w-14 h-14 bg-teal-50 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-comments text-2xl text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">دعم مباشر</h3>
                <p class="text-gray-600">تواصل مع الصيادلة مباشرة</p>
            </div>
        </div>
    </div>
</section>

</div>
</div>
</section>

<!-- Contact Section -->
<section class="py-24 bg-white">
<div class="container mx-auto px-4">
<div class="max-w-6xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 gap-12">
    <!-- Contact Info -->
    <div class="space-y-8">
        <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-6">تواصل معنا</h2>
            <p class="text-lg text-gray-600">نحن هنا لمساعدتك والإجابة على جميع استفساراتك</p>
        </div>
        
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-phone text-teal-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">اتصل بنا</h3>
                    <p class="text-gray-600">920000000</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope text-teal-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">البريد الإلكتروني</h3>
                    <p class="text-gray-600">info@smartpharmacy.com</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-teal-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">العنوان</h3>
                    <p class="text-gray-600">شارع الملك فهد، الرياض</p>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="#" class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 hover:bg-teal-100 transition-all">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 hover:bg-teal-100 transition-all">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 hover:bg-teal-100 transition-all">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 hover:bg-teal-100 transition-all">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-gray-50 p-8 rounded-2xl shadow-lg">
        <form class="space-y-6">
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">الاسم</label>
                <input type="text" id="name" name="name" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-teal-500">
            </div>
            
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-teal-500">
            </div>
            
            <div>
                <label for="message" class="block text-gray-700 font-medium mb-2">الرسالة</label>
                <textarea id="message" name="message" rows="4" 
                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-teal-500"></textarea>
            </div>

            <button type="submit" 
                    class="w-full bg-teal-600 text-white py-3 rounded-lg font-medium
                           hover:bg-teal-700 transition-all shadow-lg hover:shadow-xl">
                إرسال الرسالة
            </button>
        </form>
    </div>
</div>
</div>
</div>
</section>

<style>
@keyframes float {
0% { transform: translateY(0px); }
50% { transform: translateY(-20px); }
100% { transform: translateY(0px); }
}

.animate-float {
animation: float 3s ease-in-out infinite;
}
</style>

@endsection