<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


use App\Http\Controllers\{
    ConsultationController,
    HomeController,
    MedicationController,
    MedicationReminderController,
    MedicationReminderLogController,
    PrescriptionController,
    OrderController,
};

// استيراد متحكمات لوحة المدير الجديدة
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPrescriptionController;
use App\Http\Controllers\Admin\AdminMedicationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\NotificationController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// مسارات المستخدم المصادق
Route::middleware(['auth'])->group(function () {
   // تسجيل الخروج
   Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
   // لوحة التحكم الرئيسية
   Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    
   // الإحصائيات
   Route::get('/api/stats', [HomeController::class, 'getStats']);

   // الأدوية
   Route::prefix('medications')->name('medications.')->group(function () {
       Route::get('/', [MedicationController::class, 'index'])->name('index');
       Route::get('/{medication}', [MedicationController::class, 'show'])->name('show');
       Route::get('/category/{category}', [MedicationController::class, 'category'])->name('category');
   });

   // الوصفات الطبية
   Route::controller(PrescriptionController::class)->group(function () {
       Route::get('/prescriptions', 'index')->name('prescriptions.index');
       Route::get('/prescriptions/create', 'create')->name('prescriptions.create');
       Route::post('/prescriptions', 'store')->name('prescriptions.store');
       Route::get('/prescriptions/{prescription}', 'show')->name('prescriptions.show');
       Route::delete('/prescriptions/{prescription}', 'destroy')->name('prescriptions.destroy');
   });

   // عرض الطلبات
   Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

   // إنشاء طلب جديد
   Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
   Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
   Route::get('/orders/add', [OrderController::class, 'add'])->name('orders.add');

   // عرض تفاصيل الطلب
   Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

   // إلغاء الطلب
   Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

   Route::get('/reminders', [MedicationReminderController::class, 'index'])->name('reminders.index');
   Route::post('/reminders', [MedicationReminderController::class, 'store'])->name('reminders.store');
   Route::get('/reminders/{reminder}', [MedicationReminderController::class, 'show'])->name('reminders.show');
   Route::put('/reminders/{reminder}', [MedicationReminderController::class, 'update'])->name('reminders.update');
   Route::delete('/reminders/{reminder}', [MedicationReminderController::class, 'destroy'])->name('reminders.destroy');

   Route::post('/reminder-logs/{log}/take', [MedicationReminderLogController::class, 'markAsTaken'])->name('reminder-logs.take');
   Route::post('/reminder-logs/{log}/skip', [MedicationReminderLogController::class, 'markAsSkipped'])->name('reminder-logs.skip');
   Route::post('/reminder-logs/{log}/note', [MedicationReminderLogController::class, 'addNote'])->name('reminder-logs.note');
       
   Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');

   Route::prefix('api')->group(function () {
       Route::get('/medications/search', [MedicationController::class, 'search'])
           ->name('api.medications.search');
   });    
});

// مسارات لوحة تحكم المدير (الصيدلي)
Route::prefix('admin')->name('admin.')->group(function () {
    // لوحة المعلومات
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // إدارة الطلبات - استخدام المتحكم الجديد
    Route::controller(AdminOrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::patch('/orders/{order}/status/{status}', 'updateStatus')->name('orders.update-status');
        Route::get('/orders/{order}/print', 'print')->name('orders.print');
        Route::post('/orders/{order}/note', 'addNote')->name('orders.add-note');
        Route::get('/orders/{order}/details', 'getDetails')->name('orders.get-details');
        Route::get('/orders/create-from-prescription/{prescription}', 'createFromPrescription')
            ->name('orders.create-from-prescription');
        Route::get('/orders/export', 'export')->name('orders.export');
    });
    
    // إدارة الوصفات الطبية - استخدام المتحكم الجديد
    Route::controller(AdminPrescriptionController::class)->group(function () {
        Route::get('/prescriptions', 'index')->name('prescriptions.index');
        Route::get('/prescriptions/{prescription}', 'show')->name('prescriptions.show');
        Route::patch('/prescriptions/{prescription}/status/{status}', 'updateStatus')
            ->name('prescriptions.update-status');
        Route::patch('/prescriptions/{prescription}/medication/{medication}/approve', 'approveMedication')
            ->name('prescriptions.approve-medication');
        Route::patch('/prescriptions/{prescription}/medication/{medication}/reject', 'rejectMedication')
            ->name('prescriptions.reject-medication');
        Route::post('/prescriptions/{prescription}/approve-all', 'approveAllMedications')
            ->name('prescriptions.approve-all-medications');
        Route::patch('/prescriptions/{prescription}/complete-review', 'completeReview')
            ->name('prescriptions.complete-review');
    });
    
    // إدارة الأدوية - استخدام المتحكم الجديد
    Route::controller(AdminMedicationController::class)->group(function () {
        Route::get('/medications', 'index')->name('medications.index');
        Route::get('/medications/create', 'create')->name('medications.create');
        Route::post('/medications', 'store')->name('medications.store');
        Route::get('/medications/{medication}', 'show')->name('medications.show');
        Route::get('/medications/{medication}/edit', 'edit')->name('medications.edit');
        Route::put('/medications/{medication}', 'update')->name('medications.update');
        Route::delete('/medications/{medication}', 'destroy')->name('medications.destroy');
        Route::patch('/medications/{medication}/stock', 'updateStock')->name('medications.update-stock');
        Route::patch('/medications/{medication}/toggle-active', 'toggleActive')->name('medications.toggle-active');
        Route::get('/medications/export', 'export')->name('medications.export');
    });
    
    // إدارة الأقسام
    Route::resource('/categories', CategoryController::class);

    // إدارة المستخدمين (تكملة)
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{user}', 'show')->name('users.show');
        Route::get('/users/{user}/edit', 'edit')->name('users.edit');
        Route::put('/users/{user}', 'update')->name('users.update');
        Route::delete('/users/{user}', 'destroy')->name('users.destroy');
        Route::get('/users/{user}/orders', 'orders')->name('users.orders');
        Route::get('/users/{user}/prescriptions', 'prescriptions')->name('users.prescriptions');
        Route::get('/users/{user}/reminders', 'reminders')->name('users.reminders');
    });
    
    // إدارة الاستشارات
    Route::controller(ConsultationController::class)->group(function () {
        Route::get('/consultations', 'index')->name('consultations.index');
        Route::get('/consultations/{consultation}', 'show')->name('consultations.show');
        Route::post('/consultations/{consultation}/reply', 'reply')->name('consultations.reply');
        Route::patch('/consultations/{consultation}/status/{status}', 'updateStatus')
            ->name('consultations.update-status');
        Route::delete('/consultations/{consultation}', 'destroy')->name('consultations.destroy');
    });
    
    // التقارير - استخدام المتحكم الجديد
    Route::controller(AdminReportController::class)->group(function () {
        Route::get('/reports', 'index')->name('reports.index');
        Route::get('/reports/sales', 'sales')->name('reports.sales');
        Route::get('/reports/medications', 'medications')->name('reports.medications');
        Route::get('/reports/customers', 'customers')->name('reports.customers');
        Route::get('/reports/prescriptions', 'prescriptions')->name('reports.prescriptions');
        Route::get('/reports/export/{type}', 'export')->name('reports.export');
    });
    
    // إعدادات النظام
    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index');
        Route::post('/settings', 'update')->name('settings.update');
    });
    
    // إدارة الملف الشخصي للصيدلي
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.update-password');
    });
    
    // الإشعارات
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index')->name('notifications.index');
        Route::get('/notifications/{notification}/read', 'markAsRead')->name('notifications.read');
        Route::post('/notifications/read-all', 'markAllAsRead')->name('notifications.read-all');
    });
});