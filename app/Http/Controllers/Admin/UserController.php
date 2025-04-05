<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\MedicationReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $users = $query->paginate($request->input('per_page', 10));
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $orders = Order::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->limit(5)
                       ->get();
                       
        $prescriptions = Prescription::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                                    
        $reminders = MedicationReminder::where('user_id', $user->id)
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get();
        
        $orderStats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'pending' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        $totalSpent = Order::where('user_id', $user->id)
                         ->where('status', 'completed')
                         ->sum('total_amount');
        
        return view('admin.users.show', compact(
            'user', 
            'orders', 
            'prescriptions', 
            'reminders', 
            'orderStats', 
            'totalSpent'
        ));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        if (isset($validated['password']) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكن حذف المستخدم نظراً لوجود بيانات مرتبطة به');
        }
    }
    
    /**
     * Get all orders for the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function orders(User $user)
    {
        $orders = Order::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);
                       
        return view('admin.users.orders', compact('user', 'orders'));
    }
    
    /**
     * Get all prescriptions for the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function prescriptions(User $user)
    {
        $prescriptions = Prescription::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);
                                    
        return view('admin.users.prescriptions', compact('user', 'prescriptions'));
    }
    
    /**
     * Get all medication reminders for the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function reminders(User $user)
    {
        $reminders = MedicationReminder::where('user_id', $user->id)
                                      ->orderBy('created_at', 'desc')
                                      ->paginate(10);
                                      
        return view('admin.users.reminders', compact('user', 'reminders'));
    }
}