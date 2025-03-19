<?php

namespace App\Http\Controllers;

use App\Models\MedicationReminder;
use App\Models\MedicationReminderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicationReminderController extends Controller
{
    public function index()
    {
        $activeReminders = MedicationReminder::where('user_id', Auth::id())
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->latest()
            ->get();
            
        return view('MedicationReminder.index', compact('activeReminders'));
    }

    public function show(MedicationReminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json($reminder);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'strength' => 'nullable|string|max:50',
            'doses_per_day' => 'required|integer|min:1',
            'first_dose_time' => 'required|date_format:H:i',
            'dose_interval' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'instructions' => 'nullable|string',
            // 'notification_method' => 'required|in:email,sms,both',
        ]);

        $reminder = MedicationReminder::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        // إنشاء سجلات التذكير
        $this->createReminderLogs($reminder);

        return response()->json(['message' => 'تم إنشاء التذكير بنجاح']);
    }

    public function update(Request $request, MedicationReminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'strength' => 'nullable|string|max:50',
            'doses_per_day' => 'required|integer|min:1',
            'first_dose_time' => 'required|date_format:H:i',
            'dose_interval' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'instructions' => 'nullable|string',
            // 'notification_method' => 'required|in:email,sms,both',
        ]);

        $reminder->update($validated);

        // إعادة إنشاء سجلات التذكير
        $reminder->logs()->delete();
        $this->createReminderLogs($reminder);

        return response()->json(['message' => 'تم تحديث التذكير بنجاح']);
    }

    public function destroy(MedicationReminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->delete();
        return response()->json(['message' => 'تم حذف التذكير بنجاح']);
    }

    protected function createReminderLogs(MedicationReminder $reminder)
    {
        $startDate = Carbon::parse($reminder->start_date)->startOfDay();
        $endDate = $reminder->end_date 
            ? Carbon::parse($reminder->end_date)->endOfDay()
            : $startDate->copy()->addDays(30);
            
        $firstDoseTime = Carbon::parse($reminder->first_dose_time);
        $interval = (int) $reminder->dose_interval;
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $currentTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $firstDoseTime->format('H:i:s'));
                        
            for ($i = 0; $i < (int) $reminder->doses_per_day; $i++) {
                MedicationReminderLog::create([
                    'reminder_id' => $reminder->id,
                    'scheduled_time' => $currentDate->copy()->setTimeFrom($currentTime),
                ]);
                
                $currentTime->addHours($interval);
            }
            
            $currentDate->addDay();
        }
    }
}