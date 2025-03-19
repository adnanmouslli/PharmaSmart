@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-lg">
        <!-- Login Form Card -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="rounded-full bg-white/10 backdrop-blur-md w-20 h-20 mx-auto flex items-center justify-center mb-6">
                    <i class="fas fa-user text-4xl text-teal-400"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">تسجيل الدخول</h2>
                <p class="text-gray-300">مرحباً بعودتك! يرجى تسجيل الدخول للمتابعة</p>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-300 mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                  text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                  focus:ring-1 focus:ring-teal-500">
                    @error('email')
                        <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-300 mb-2">كلمة المرور</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-gray-300/20 
                                  text-white placeholder-gray-400 focus:outline-none focus:border-teal-500
                                  focus:ring-1 focus:ring-teal-500">
                    @error('password')
                        <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl 
                               font-medium transition-all shadow-lg hover:shadow-xl">
                    تسجيل الدخول
                </button>

    

                <!-- Register Link -->
                <div class="text-center text-gray-300">
                    ليس لديك حساب؟ 
                    <a href="{{ route('register') }}" class="text-teal-400 hover:underline">إنشاء حساب جديد</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Message -->
@if (session('success'))
<div class="fixed bottom-4 left-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

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