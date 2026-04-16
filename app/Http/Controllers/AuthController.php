<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use App\Models\Project;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        if (session()->has('user_id')) {

            if (session('user_role') === 'manager') {
                return redirect()->route('manager.dashboard');
            }

            if (session('user_role') === 'employee') {
                return redirect()->route('employee.dashboard');
            }
        }

        return view('login');
    }

    // Show register page
    public function showRegister()
    {
        return view('register');
    }

    // Handle registration
   


    public function register(Request $request)
    {
        // 🔹 If manager is logged in → force employee role
        if (session('user_role') === 'manager') {
            $role = 'employee';
        } else {
            // 🔹 Public user must choose role
            $request->validate([
                'role' => 'required|in:manager,employee'
            ]);
            $role = $request->role;
        }

        // 🔹 Common validation
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'mobile' => 'required|digits:10',
            'secondary_mobile' => 'nullable|digits:10',
            'dob' => 'nullable|date|before:today',
        ]);

        try {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $role,
                'mobile' => $request->mobile,
                'secondary_mobile' => $request->secondary_mobile,
                'dob' => $request->dob,
            ]);

        } catch (QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating your account. Please try again.');
        }

        return redirect()->route('login')
            ->with('success', 'Account created successfully!');
    }

    // Handle login
   public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);
            Session::put('user_name', $user->name);

            if ($user->role === 'manager') {
                return redirect()->route('manager.dashboard');
            }

            return redirect()->route('employee.dashboard');
        }

        return back()->with('error', 'Invalid email or password');
    }      

    public function logout()
    {
        Session::flush();   // remove all session data

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    // Store project
public function storeProject(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'deadline' => 'nullable|date',
        'employee_id' => 'required|exists:users,id'
    ]);

    Project::create([
        'name' => $request->name,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'deadline' => $request->deadline,
        'status' => 'pending',
        'employee_id' => $request->employee_id,
        'manager_id' => session('user') // your current manager ID
    ]);

    return redirect()->route('manager.projects')
        ->with('success', 'Project created successfully.');
}
}