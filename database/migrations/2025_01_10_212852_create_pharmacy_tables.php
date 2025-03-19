<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Medications table
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->string('manufacturer')->nullable();
            $table->string('dosage_form'); // قرص، شراب، حقن
            $table->string('strength')->nullable(); // مثل 500mg
            $table->integer('stock')->default(0);
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Prescriptions table
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('doctor_name');
            $table->string('hospital_name')->nullable();
            $table->date('prescription_date');
            $table->string('image');
            $table->text('notes')->nullable();
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'partially_approved',
                'rejected'
            ])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Prescription Medications table
        Schema::create('prescription_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medication_id')->constrained();
            $table->integer('quantity');
            $table->string('dosage_instructions')->nullable();
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        // Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Order Items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medication_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('medication_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medication_id')->nullable()->constrained()->nullOnDelete();
            $table->string('medication_name'); // في حال كان الدواء غير موجود في قاعدة البيانات
            $table->string('strength')->nullable(); // مثل 500mg
            $table->string('dosage_form')->nullable(); // شكل الدواء
            $table->integer('doses_per_day'); // عدد الجرعات في اليوم
            $table->time('first_dose_time'); // وقت الجرعة الأولى
            $table->integer('dose_interval'); // الفاصل الزمني بين الجرعات بالساعات
            $table->text('instructions')->nullable(); // تعليمات خاصة
            $table->date('start_date'); // تاريخ بدء العلاج
            $table->date('end_date')->nullable(); // تاريخ انتهاء العلاج
            $table->boolean('is_active')->default(true);
            $table->string('notification_method')->default('both'); // email, sms, both
            $table->timestamps();
        });

        Schema::create('medication_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->constrained('medication_reminders')->cascadeOnDelete();
            $table->dateTime('scheduled_time');
            $table->dateTime('taken_at')->nullable();
            $table->boolean('is_taken')->default(false);
            $table->boolean('is_skipped')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('prescription_medications');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('medication_reminder_logs');
        Schema::dropIfExists('medication_reminders');
    }
};