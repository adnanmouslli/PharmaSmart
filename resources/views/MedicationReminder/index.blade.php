@extends('layout.user')

@section('title', 'تذكيرات الدواء')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="flex flex-wrap items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 md:mb-0">
            <i class="fas fa-clock ml-2"></i>
            تذكيرات الدواء
        </h1>
        <button onclick="openReminderModal()" 
                class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 transition duration-200">
            <i class="fas fa-plus"></i>
            <span>إضافة تذكير جديد</span>
        </button>
    </div>

    <!-- قائمة التذكيرات النشطة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($activeReminders as $reminder)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ $reminder->medication_name }}
                        </h3>
                        @if($reminder->strength)
                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $reminder->strength }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="editReminder({{ $reminder->id }})" 
                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteReminder({{ $reminder->id }})" 
                                class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock w-5"></i>
                        <span>{{ $reminder->doses_per_day }} مرات يومياً</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-hourglass-start w-5"></i>
                        <span>أول جرعة: {{ \Carbon\Carbon::parse($reminder->first_dose_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span>
                            من {{ $reminder->start_date->format('Y/m/d') }}
                            @if($reminder->end_date)
                                إلى {{ $reminder->end_date->format('Y/m/d') }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- شريط التقدم -->
                @if($reminder->end_date)
                    @php
                        $totalDays = $reminder->start_date->diffInDays($reminder->end_date);
                        $remainingDays = (int)now()->diffInDays($reminder->end_date);
                        $progress = max(0, min(100, (($totalDays - $remainingDays) / $totalDays) * 100));
                    @endphp
                    <div class="mt-4">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-teal-600 rounded-full" 
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            متبقي {{ $remainingDays }} يوم
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-3xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                لا توجد تذكيرات نشطة
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                قم بإضافة تذكير جديد للبدء في تتبع أدويتك
            </p>
        </div>
        @endforelse
    </div>

   <!-- نموذج إضافة/تعديل التذكير -->
    <div id="reminderModal" class="fixed inset-0 bg-gray-900/50 items-center justify-center modal-container overflow-y-auto py-8" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-3xl mx-auto my-auto overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="modalTitle">
                        إضافة تذكير جديد
                    </h2>
                    <button onclick="closeReminderModal()" class="text-gray-500 hover:text-gray-700 p-2">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form id="reminderForm" class="space-y-8">
                    @csrf
                    <!-- البيانات الأساسية -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                اسم الدواء
                            </label>
                            <input type="text" name="medication_name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                التركيز
                            </label>
                            <input type="text" name="strength"
                                placeholder="مثال: 500mg"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- معلومات الجرعات -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                عدد الجرعات اليومية
                            </label>
                            <input type="number" name="doses_per_day" required min="1"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                وقت الجرعة الأولى
                            </label>
                            <input type="time" name="first_dose_time" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                الفاصل الزمني (ساعات)
                            </label>
                            <input type="number" name="dose_interval" required min="1"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- تواريخ العلاج -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                تاريخ البدء
                            </label>
                            <input type="date" name="start_date" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                تاريخ الانتهاء
                            </label>
                            <input type="date" name="end_date"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- تعليمات إضافية -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            تعليمات خاصة
                        </label>
                        <textarea name="instructions" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                                placeholder="مثال: يؤخذ بعد الأكل"></textarea>
                    </div>

                    <!-- طريقة التنبيه -->
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            طريقة التنبيه
                        </label>
                        <div class="flex gap-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="notification_method" value="email" class="form-radio text-teal-600">
                                <span class="mr-2 text-gray-700 dark:text-gray-300">البريد الإلكتروني</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="notification_method" value="sms" class="form-radio text-teal-600">
                                <span class="mr-2 text-gray-700 dark:text-gray-300">رسالة نصية</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="notification_method" value="both" class="form-radio text-teal-600" checked>
                                <span class="mr-2 text-gray-700 dark:text-gray-300">كلاهما</span>
                            </label>
                        </div>
                    </div> --}}

                    <div class="flex items-center justify-end gap-4 mt-8">
                        <button type="button" onclick="closeReminderModal()"
                                class="px-8 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-8 py-3 rounded-lg bg-teal-600 hover:bg-teal-700 text-white">
                            حفظ التذكير
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// حالة عامة
let currentReminderId = null;

// فتح النموذج للإضافة أو التعديل
function openReminderModal(reminderId = null) {
    currentReminderId = reminderId;
    const modal = document.getElementById('reminderModal');
    const form = document.getElementById('reminderForm');
    const title = document.getElementById('modalTitle');
    
    // تحديث العنوان بناءً على العملية
    title.textContent = reminderId ? 'تعديل التذكير' : 'إضافة تذكير جديد';
    modal.style.display = 'flex';

    if (reminderId) {
        // جلب بيانات التذكير للتعديل
        fetchReminderData(reminderId);
    } else {
        form.reset();
        form.querySelector('input[name="notification_method"][value="both"]').checked = true;
    }
}

// دالة تعديل التذكير - تستخدم openReminderModal
function editReminder(reminderId) {
    openReminderModal(reminderId);
}

// جلب بيانات التذكير
async function fetchReminderData(reminderId) {
    try {
        const response = await fetch(`/reminders/${reminderId}`);
        if (!response.ok) throw new Error('Failed to fetch reminder data');
        
        const data = await response.json();
        fillFormWithData(data);
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء جلب بيانات التذكير');
    }
}

// ملء النموذج بالبيانات
function fillFormWithData(data) {
    const form = document.getElementById('reminderForm');
    
    // Helper function to format time to H:i
    const formatTime = (timeString) => {
        if (!timeString) return '';
        const date = new Date(`2000-01-01T${timeString}`);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    };
    
    // Helper function to format date
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    };
    
    // Fill form fields
    const fields = [
        'medication_name', 
        'strength', 
        'doses_per_day', 
        'first_dose_time',
        'dose_interval', 
        'start_date', 
        'end_date', 
        'instructions'
    ];
    
    fields.forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) return;

        if (field === 'start_date' || field === 'end_date') {
            input.value = formatDate(data[field]);
        } else if (field === 'first_dose_time' && data[field]) {
            // Handle time format for first_dose_time
            input.value = formatTime(data[field]);
        } else {
            input.value = data[field] || '';
        }
    });

    // Handle notification method
    const notificationMethod = data.notification_method || 'both';
    const radioButton = form.querySelector(`input[name="notification_method"][value="${notificationMethod}"]`);
    if (radioButton) radioButton.checked = true;
}


function prepareFormData(formData) {
    const data = Object.fromEntries(formData);
    
    // Ensure first_dose_time is in H:i format
    if (data.first_dose_time) {
        const [hours, minutes] = data.first_dose_time.split(':');
        data.first_dose_time = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
    }
    
    return data;
}

// Handle form submission
async function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = prepareFormData(formData);
    
    // Validate required fields
    const requiredFields = [
        'medication_name',
        'doses_per_day',
        'first_dose_time',
        'dose_interval',
        'start_date'
    ];
    
    for (const field of requiredFields) {
        if (!data[field]) {
            alert(`الرجاء ملء الحقل: ${field}`);
            return;
        }
    }
    
    // Prepare request
    const url = currentReminderId ? `/reminders/${currentReminderId}` : '/reminders';
    const method = currentReminderId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'حدث خطأ أثناء حفظ التذكير');
        }
        
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}


// إغلاق النموذج
function closeReminderModal() {
    const modal = document.getElementById('reminderModal');
    modal.style.display = 'none';
    currentReminderId = null;
}

// معالجة تقديم النموذج
async function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // التحقق من الحقول المطلوبة
    const requiredFields = [
        'medication_name',
        'doses_per_day',
        'first_dose_time',
        'dose_interval',
        'start_date'
    ];
    
    for (const field of requiredFields) {
        if (!data[field]) {
            alert(`الرجاء ملء الحقل: ${field}`);
            return;
        }
    }
    
    // تحضير الطلب
    const url = currentReminderId ? `/reminders/${currentReminderId}` : '/reminders';
    const method = currentReminderId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'حدث خطأ أثناء حفظ التذكير');
        }
        
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}

// حذف التذكير
async function deleteReminder(id) {
    if (!confirm('هل أنت متأكد من حذف هذا التذكير؟')) return;
    
    try {
        const response = await fetch(`/reminders/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Failed to delete reminder');
        
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حذف التذكير');
    }
}

// إضافة مستمعي الأحداث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reminderForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
});


const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

</script>
@endpush

@push('styles')
<style>
.modal-container {
    display: none;
    z-index: 50;
}
.modal-container.active {
    display: flex;
}
.modal-overlay {
    backdrop-filter: blur(4px);
}
</style>
@endpush

@endsection