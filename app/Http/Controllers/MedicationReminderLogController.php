<?php

namespace App\Http\Controllers;

use App\Models\MedicationReminderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicationReminderLogController extends Controller
{
    public function markAsTaken(MedicationReminderLog $log)
    {
        // التحقق من ملكية التذكير
        if ($log->reminder->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        try {
            // تحديث حالة التذكير
            $log->update([
                'is_taken' => true,
                'taken_at' => now(),
            ]);

            // إذا كان الوقت المجدول قبل أكثر من ساعة
            if (Carbon::parse($log->scheduled_time)->diffInHours(now()) > 1) {
                // تسجيل ملاحظة بأن الدواء تم أخذه متأخراً
                $log->update([
                    'notes' => 'تم أخذ الدواء متأخراً عن الموعد المحدد'
                ]);
            }

            return response()->json([
                'message' => 'تم تحديث حالة التذكير بنجاح',
                'status' => 'success',
                'data' => [
                    'taken_at' => $log->taken_at->format('Y-m-d H:i:s'),
                    'is_late' => $log->scheduled_time->diffInHours(now()) > 1
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تحديث حالة التذكير',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsSkipped(MedicationReminderLog $log)
    {
        // التحقق من ملكية التذكير
        if ($log->reminder->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        try {
            $log->update([
                'is_skipped' => true,
                'notes' => 'تم تخطي هذه الجرعة'
            ]);

            return response()->json([
                'message' => 'تم تحديث حالة التذكير بنجاح',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تحديث حالة التذكير',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addNote(Request $request, MedicationReminderLog $log)
    {
        // التحقق من ملكية التذكير
        if ($log->reminder->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        $request->validate([
            'note' => 'required|string|max:500'
        ]);

        try {
            $log->update([
                'notes' => $request->note
            ]);

            return response()->json([
                'message' => 'تم إضافة الملاحظة بنجاح',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة الملاحظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}