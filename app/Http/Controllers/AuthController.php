<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PatientRegistrationMail;
use App\Mail\DoctorRegistrationMail;
use App\Mail\ReceptionRegistrationMail;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Reception;
use App\Models\Patient;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        
        $credentials = $request->only('email', 'password');
        $userType = $validated['user_type'];

        if (Auth::guard($userType)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            session(['user_type' => $userType]);

            return $this->redirectToDashboard($userType)
                ->with('success', 'Login successful! Welcome back.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'user_type', 'remember'));
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $userType = $validated['user_type'];

        if ($userType === 'admin') {
            return back()->with('error', 'Admin registration is not allowed.')->withInput();
        }

        DB::beginTransaction();

        try {
            $user = $this->createUser($userType, $validated);

            $this->sendWelcomeEmail($userType, $user);

            DB::commit();

            Auth::guard($userType)->login($user);
            session(['user_type' => $userType]);

            return $this->redirectToDashboard($userType)
                ->with('success', 'Registration successful! Welcome.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Registration failed: ' . $e->getMessage());
            
            $errorMessage = $this->getErrorMessage($e);

            return back()->with('error', $errorMessage)->withInput();
        }
    }

    private function createUser($userType, $validated)
    {
        switch ($userType) {
            case 'doctor':
                return Doctor::create([
                    'doctor_id' => 'DOC' . time(),
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'phone' => $validated['phone'],
                    'specialization' => $validated['specialization'],
                    'qualification' => $validated['qualification'],
                    'experience_years' => $validated['experience_years'],
                    'is_active' => true,
                ]);

            case 'reception':
                return Reception::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'phone' => $validated['phone'],
                    'employee_id' => $validated['employee_id'] ?? null,
                    'shift_timing' => $validated['shift_timing'] ?? null,
                    'is_active' => true,
                ]);

            case 'patient':
                $nameParts = $this->splitName($validated['name']);
                
                return Patient::create([
                    'patient_id' => 'PAT' . time(),
                    'first_name' => $nameParts['first_name'],
                    'last_name' => $nameParts['last_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'phone' => $validated['phone'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                    'address' => $validated['address'],
                    'blood_group' => $validated['blood_group'] ?? null,
                    'emergency_contact' => $validated['emergency_contact'] ?? null,
                    'medical_history' => $validated['medical_history'] ?? null,
                    'allergies' => $validated['allergies'] ?? null,
                    'is_active' => true,
                ]);

            default:
                throw new \Exception('Invalid user type selected.');
        }
    }

    private function sendWelcomeEmail($userType, $user)
    {
        try {
            switch ($userType) {
                case 'doctor':
                    Mail::to($user->email)->send(new DoctorRegistrationMail($user));
                    break;
                case 'reception':
                    Mail::to($user->email)->send(new ReceptionRegistrationMail($user));
                    break;
                case 'patient':
                    Mail::to($user->email)->send(new PatientRegistrationMail($user));
                    break;
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send {$userType} registration email: " . $e->getMessage());
        }
    }

    private function splitName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));
        $firstName = $nameParts[0] ?? $fullName;
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
        
        return [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
    }

    private function getErrorMessage($exception)
    {
        if (str_contains($exception->getMessage(), 'Duplicate entry')) {
            return 'Registration failed. Email already exists.';
        }

        return 'Registration failed. Please try again.';
    }

    protected function redirectToDashboard($userType)
    {
        $routes = [
            'admin' => 'admin.dashboard',
            'doctor' => 'doctor.dashboard',
            'reception' => 'reception.dashboard',
            'patient' => 'patient.dashboard',
        ];

        return redirect()->route($routes[$userType] ?? 'login');
    }

    public function logout(Request $request)
    {
        $userType = session('user_type', Auth::getDefaultDriver());
        
        Auth::guard($userType)->logout();

        $request->session()->forget('user_type');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        $userType = session('user_type');
        
        if (!$userType) {
            return redirect()->route('login');
        }

        $user = Auth::guard($userType)->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $views = [
            'admin' => 'admin.dashboard',
            'doctor' => 'doctor.dashboard',
            'reception' => 'reception.dashboard',
            'patient' => 'patient.dashboard',
        ];

        return view($views[$userType] ?? 'auth.login', compact('user'));
    }
    
    private function getUserTable($userType)
    {
        $tables = [
            'admin' => 'admins',
            'doctor' => 'doctors',
            'reception' => 'receptions',
            'patient' => 'patients',
        ];

        return $tables[$userType] ?? 'users';
    }
}