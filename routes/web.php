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

Route::post('/reminder-logs/{log}/take', [MedicationReminderLogController::class, 'markAsTaken'])
    ->name('reminder-logs.take');
Route::post('/reminder-logs/{log}/skip', [MedicationReminderLogController::class, 'markAsSkipped'])
    ->name('reminder-logs.skip');
Route::post('/reminder-logs/{log}/note', [MedicationReminderLogController::class, 'addNote'])
    ->name('reminder-logs.note');
    

Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
// Route::get('/chat', [ConsultationController::class, 'chat'])->name('chat');


Route::prefix('api')->group(function () {
    Route::get('/medications/search', [MedicationController::class, 'search'])
        ->name('api.medications.search');
});    

    
});
