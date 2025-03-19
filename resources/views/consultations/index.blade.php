@extends('layout.user')

@section('title', 'المستشار الصيدلي')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col h-screen">
    <!-- رأس الصفحة محسن -->
    <div class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex items-center h-20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-robot text-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">المستشار الصيدلي</h1>
                        <div class="flex items-center mt-1">
                            <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 mr-2" id="connectionStatus">متصل</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- منطقة الرسائل محسنة -->
    <div class="flex-1 overflow-y-auto px-4 scroll-smooth" id="chatMessages">
        <div class="max-w-3xl mx-auto py-8">
            <!-- ستتم إضافة الرسائل هنا -->
        </div>
    </div>

    <!-- منطقة الخيارات والإدخال محسنة -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg">
        <div class="max-w-3xl mx-auto">
            <!-- منطقة الخيارات -->
            <div id="optionsArea" class="px-4 py-4 space-y-3">
                <!-- سيتم إضافة الخيارات هنا -->
            </div>

            <!-- نموذج الإدخال محسن -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <form id="chatForm" class="flex gap-3">
                    <input type="text" 
                           id="userInput" 
                           class="flex-1 px-4 py-3.5 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-200"
                           placeholder="اكتب رسالتك هنا..."
                           autocomplete="off">
                    <button type="submit"
                            class="px-6 py-3.5 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white rounded-xl transition duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <span class="font-medium">إرسال</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- قالب الرسائل محسن -->
<template id="messageTemplate">
    <div class="message-wrapper mb-6">
        <div class="message opacity-0 transition-all duration-300 ease-out">
            <!-- رسالة البوت -->
            <div class="bot-message">
                <div class="flex items-start gap-3 mb-2">
                    <div class="avatar w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex-shrink-0 flex items-center justify-center shadow-md">
                        <i class="fas fa-robot text-sm text-white"></i>
                    </div>
                    <div class="message-bubble max-w-[85%] bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-800 dark:text-gray-200 text-[15px] leading-relaxed whitespace-pre-wrap"></p>
                    </div>
                </div>
            </div>
            
            <!-- رسالة المستخدم -->
            <div class="user-message">
                <div class="flex items-start gap-3 mb-2 justify-end">
                    <div class="message-bubble max-w-[85%] bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-4 shadow-md">
                        <p class="text-white text-[15px] leading-relaxed whitespace-pre-wrap"></p>
                    </div>
                    <div class="avatar w-10 h-10 rounded-xl bg-gradient-to-br from-gray-600 to-gray-700 flex-shrink-0 flex items-center justify-center shadow-md">
                        <i class="fas fa-user text-sm text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
    <script src="{{ asset('js/chat-handler.js') }}"></script>
@endpush

@push('styles')
<style>
/* أساسيات محسنة */
.message-wrapper {
    animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* تنسيق الخيارات محسن */
.chat-option {
    @apply w-full text-right px-4 py-3.5 bg-white dark:bg-gray-800 
           hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl 
           transition-all duration-200 shadow-sm border-2 border-gray-200 
           dark:border-gray-700 font-medium;
}

.chat-option:hover {
    @apply shadow-md transform -translate-y-0.5;
}

/* مؤشر الكتابة محسن */
.typing-indicator {
    @apply inline-flex items-center p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700;
}

.typing-indicator span {
    @apply w-2 h-2 bg-current rounded-full mx-0.5;
    animation: typing 1.4s infinite;
}

/* تحسينات الرسائل */
.message-bubble {
    position: relative;
}

.bot-message .message-bubble::before {
    content: '';
    position: absolute;
    right: 100%;
    top: 12px;
    border: 8px solid transparent;
    border-right-color: #ffffff;
    filter: drop-shadow(-3px 1px 2px rgba(0, 0, 0, 0.1));
}

.dark .bot-message .message-bubble::before {
    border-right-color: #1f2937;
}

.user-message .message-bubble::after {
    content: '';
    position: absolute;
    left: 100%;
    top: 12px;
    border: 8px solid transparent;
    border-left-color: #0d9488;
}

/* تحسين الانيميشن */
@keyframes slideIn {
    0% {
        transform: translateY(20px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes typing {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* تحسين scrollbar */
#chatMessages {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

#chatMessages::-webkit-scrollbar {
    width: 6px;
}

#chatMessages::-webkit-scrollbar-track {
    background: transparent;
}

#chatMessages::-webkit-scrollbar-thumb {
    @apply bg-gray-400 dark:bg-gray-600 rounded-full;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    @apply bg-gray-500 dark:bg-gray-500;
}

/* تحسين تنسيق الخيارات */
#optionsArea .grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
</style>
@endpush