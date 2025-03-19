@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-2xl">
        <!-- Signup Form Card -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="rounded-full bg-white/10 backdrop-blur-md w-20 h-20 mx-auto flex items-center justify-center mb-6">
                    <i class="fas fa-user-plus text-4xl text-teal-400"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">إنشاء حساب جديد</h2>
                <p class="text-gray-300">انضم إلى الصيدلية الذكية واستمتع بخدماتنا المميزة</p>
            </div>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-gray-300 mb-2">الاسم الأول</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                      text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                      focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-gray-300 mb-2">الاسم الأخير</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                      text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                      focus:ring-1 focus:ring-teal-500">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-300 mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                  text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                  focus:ring-1 focus:ring-teal-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-gray-300 mb-2">رقم الجوال</label>
                    <input type="tel" id="phone" name="phone" required
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                  text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                  focus:ring-1 focus:ring-teal-500"
                           dir="ltr">
                </div>

                <!-- address -->
                <div>
                    <label for="address" class="block text-gray-300 mb-2">العنوان</label>
                    <input type="text" id="address" name="address" required
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                  text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                  focus:ring-1 focus:ring-teal-500"
                           dir="ltr">
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-gray-300 mb-2">كلمة المرور</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                      text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                      focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-gray-300 mb-2">تأكيد كلمة المرور</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                      text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                      focus:ring-1 focus:ring-teal-500">
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="terms" name="terms" required
                           class="w-4 h-4 rounded border-gray-300/20 text-teal-600 focus:ring-teal-500">
                    <label for="terms" class="text-gray-300">
                        أوافق على <a href="#" class="text-teal-400 hover:underline">الشروط والأحكام</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl 
                               font-medium transition-all shadow-lg hover:shadow-xl">
                    إنشاء حساب
                </button>

                <!-- Login Link -->
                <div class="text-center text-gray-300">
                    لديك حساب بالفعل؟ 
                    <a href="{{ route('login') }}" class="text-teal-400 hover:underline">تسجيل الدخول</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="fixed bottom-4 left-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@endsection