<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\RoomAllocationController;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    // Remove logout from guest middleware - it should be available for authenticated users
});

// Add logout route for all authenticated users
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
    Route::put('/doctors/{doctor}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [AdminController::class, 'destroyDoctor'])->name('doctors.destroy');
    Route::patch('/doctors/{doctor}/toggle-status', [AdminController::class, 'toggleDoctorStatus'])->name('doctors.toggle-status');

    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::post('/patients', [AdminController::class, 'storePatient'])->name('patients.store');
    Route::put('/patients/{id}', [AdminController::class, 'updatePatient'])->name('patients.update');
    Route::delete('/patients/{patient}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');
    Route::patch('/patients/{id}/toggle-status', [AdminController::class, 'togglePatientStatus'])->name('patients.toggle-status');
    
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [AdminController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/appointments/{id}', [AdminController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [AdminController::class, 'destroyAppointment'])->name('appointments.destroy');
    Route::patch('/appointments/{id}/status/{status}', [AdminController::class, 'updateAppointmentStatus'])->name('appointments.update-status');

    Route::get('/departments', [AdminController::class, 'departments'])->name('departments');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::put('/departments/{id}', [AdminController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}', [AdminController::class, 'destroyDepartment'])->name('departments.destroy');
    Route::patch('/departments/{id}/toggle-status', [AdminController::class, 'toggleDepartmentStatus'])->name('departments.toggle-status');

    Route::get('/rooms', [AdminController::class, 'rooms'])->name('rooms');
    Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
    Route::put('/rooms/{id}', [AdminController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{id}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');
    Route::patch('/rooms/{id}/status/{status}', [AdminController::class, 'updateRoomStatus'])->name('rooms.update-status');

    Route::get('/medical-records', [AdminController::class, 'medicalRecords'])->name('medical-records');
    Route::post('/medical-records', [AdminController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::put('/medical-records/{id}', [AdminController::class, 'updateMedicalRecord'])->name('medical-records.update');
    Route::delete('/medical-records/{id}', [AdminController::class, 'destroyMedicalRecord'])->name('medical-records.destroy');

    Route::get('/receptions', [AdminController::class, 'receptions'])->name('receptions');
    Route::post('/receptions', [AdminController::class, 'storeReception'])->name('receptions.store');
    Route::put('/receptions/{id}', [AdminController::class, 'updateReception'])->name('receptions.update');
    Route::delete('/receptions/{id}', [AdminController::class, 'destroyReception'])->name('receptions.destroy');
    Route::patch('/receptions/{id}/toggle-status', [AdminController::class, 'toggleReceptionStatus'])->name('receptions.toggle-status');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/staff', [AdminController::class, 'staff'])->name('staff');
    Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks');
});

// Doctor Routes - ONLY THIS ONE GROUP
Route::prefix('doctor')->name('doctor.')->middleware('auth:doctor')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/{id}', [DoctorController::class, 'showAppointment'])->name('appointments.show');
    Route::get('/appointments/{id}/edit', [DoctorController::class, 'editAppointment'])->name('appointments.edit');
    Route::put('/appointments/{id}', [DoctorController::class, 'updateAppointment'])->name('appointments.update');
    Route::put('/appointments/{id}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointments.update-status');

    Route::get('/patients', [DoctorController::class, 'viewPatients'])->name('patients');
    Route::get('/patients/{patient}', [DoctorController::class, 'showPatient'])->name('patients.show');
    Route::get('/patients/{patient}/medical-records', [DoctorController::class, 'patientMedicalRecords'])->name('patients.medical-records');

    Route::get('/medical-records', [DoctorController::class, 'medicalRecords'])->name('medical-records');
    Route::get('/medical-records/create', [DoctorController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records', [DoctorController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{medicalRecord}', [DoctorController::class, 'showMedicalRecord'])->name('medical-records.show');
    Route::get('/medical-records/{medicalRecord}/edit', [DoctorController::class, 'editMedicalRecord'])->name('medical-records.edit');
    Route::put('/medical-records/{medicalRecord}', [DoctorController::class, 'updateMedicalRecord'])->name('medical-records.update');
    Route::delete('/medical-records/{medicalRecord}', [DoctorController::class, 'destroyMedicalRecord'])->name('medical-records.destroy');
});

Route::prefix('reception')->name('reception.')->middleware('auth:reception')->group(function () {
    Route::get('/dashboard', [ReceptionController::class, 'dashboard'])->name('dashboard');

    Route::get('/patients/add', [ReceptionController::class, 'patientAdd'])->name('patient-add');
    Route::post('/patients', [ReceptionController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients', [ReceptionController::class, 'patientList'])->name('patients.list');
    Route::get('/patients/{patient}', [ReceptionController::class, 'showPatient'])->name('patients.show');
    Route::put('/patients/{patient}', [ReceptionController::class, 'updatePatient'])->name('patients.update');
    Route::delete('/patients/{patient}/delete', [ReceptionController::class, 'deletePatient'])->name('patient.delete');
    Route::get('/appointments', [ReceptionController::class, 'appointments'])->name('appointments');

    Route::get('/appointments', [ReceptionController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [ReceptionController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [ReceptionController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [ReceptionController::class, 'destroyAppointment'])->name('appointments.destroy');

    Route::get('/doctors', [ReceptionController::class, 'doctors'])->name('doctors');
    Route::get('/doctors/{doctor}', [ReceptionController::class, 'showDoctor'])->name('doctors.show');

    Route::get('/rooms', [ReceptionController::class, 'rooms'])->name('rooms');
    Route::get('/rooms/create', [ReceptionController::class, 'createRoom'])->name('rooms.create');
    Route::post('/rooms', [ReceptionController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/rooms/{room}', [ReceptionController::class, 'showRoom'])->name('rooms.show');
    Route::get('/rooms/{room}/edit', [ReceptionController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/rooms/{room}', [ReceptionController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{room}', [ReceptionController::class, 'destroyRoom'])->name('rooms.destroy');
    Route::post('/rooms/{room}/status', [ReceptionController::class, 'updateRoomStatus'])->name('rooms.status');
    Route::get('/available-rooms', [ReceptionController::class, 'getAvailableRooms'])->name('rooms.available');
    Route::get('/profile', [ReceptionController::class, 'profile'])->name('profile');
    Route::put('/profile', [ReceptionController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ReceptionController::class, 'updatePassword'])->name('profile.password');
    Route::get('/rooms-search', [ReceptionController::class, 'searchRooms'])->name('rooms.search');

    Route::get('/room-allocations', [RoomAllocationController::class, 'index'])->name('room-allocations');
    Route::get('/room-allocations/create', [RoomAllocationController::class, 'create'])->name('room-allocations.create');
    Route::post('/room-allocations', [RoomAllocationController::class, 'store'])->name('room-allocations.store');
    Route::get('/room-allocations/{id}/edit', [RoomAllocationController::class, 'edit'])->name('room-allocations.edit');
    Route::put('/room-allocations/{id}', [RoomAllocationController::class, 'update'])->name('room-allocations.update');
    Route::delete('/room-allocations/{id}', [RoomAllocationController::class, 'destroy'])->name('room-allocations.destroy');
    Route::post('/room-allocations/{id}/discharge', [RoomAllocationController::class, 'discharge'])->name('room-allocations.discharge');
    Route::post('/room-allocations/{id}/payment', [RoomAllocationController::class, 'addPayment'])->name('room-allocations.payment');
});

// Patient Routes (Admin Side)
Route::middleware(['auth'])->prefix('patients')->group(function () {
    Route::get('/', [PatientController::class, 'patientindex'])->name('patients.index');
    Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::patch('/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patients.toggle-status');
    
    // Patient Appointments (Admin)
    Route::get('/{patient}/appointments', [PatientController::class, 'appointments'])->name('patients.appointments');
    Route::get('/{patient}/appointments/create', [PatientController::class, 'createAppointment'])->name('patients.appointments.create');
    Route::post('/{patient}/appointments', [PatientController::class, 'storeAppointment'])->name('patients.appointments.store');
    Route::get('/{patient}/appointments/{appointment}/edit', [PatientController::class, 'editAppointment'])->name('patients.appointments.edit');
    Route::put('/{patient}/appointments/{appointment}', [PatientController::class, 'updateAppointment'])->name('patients.appointments.update');
    Route::delete('/{patient}/appointments/{appointment}', [PatientController::class, 'destroyAppointment'])->name('patients.appointments.destroy');
    
    // Patient Medical Records (Admin)
    Route::get('/{patient}/medical-records', [PatientController::class, 'medicalRecords'])->name('patients.medical-records');
    Route::get('/{patient}/medical-records/create', [PatientController::class, 'createMedicalRecord'])->name('patients.medical-records.create');
    Route::post('/{patient}/medical-records', [PatientController::class, 'storeMedicalRecord'])->name('patients.medical-records.store');
});

// Patient Portal Routes (Patient Side)
Route::middleware(['auth:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    
    // Patient Appointments (Patient Side)
    Route::get('/appointments', [PatientController::class, 'patientAppointments'])->name('appointments.index');
    Route::get('/appointments/create', [PatientController::class, 'createPatientAppointment'])->name('appointments.create');
    Route::post('/appointments', [PatientController::class, 'storePatientAppointment'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [PatientController::class, 'showPatientAppointment'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [PatientController::class, 'editPatientAppointment'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [PatientController::class, 'updatePatientAppointment'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [PatientController::class, 'destroyPatientAppointment'])->name('appointments.destroy');
    Route::post('/appointments/{appointment}/cancel', [PatientController::class, 'cancelPatientAppointment'])->name('appointments.cancel');
    // Add this route for availability checking
    Route::post('/patient/check-availability', [PatientController::class, 'checkAvailability'])
        ->name('patient.appointments.check-availability')
        ->middleware('auth:patient');
    // Patient Medical Records (Patient Side)
    Route::get('/medical-records', [PatientController::class, 'patientMedicalRecords'])->name('medical-records.index');
    Route::get('/medical-records/{medicalRecord}', [PatientController::class, 'showMedicalRecord'])->name('medical-records.show');
    
    Route::post('/logout', [PatientController::class, 'logout'])->name('logout');
});

Route::fallback(function () {
    return view('errors.404');
});

// routes/web.php
Route::get('/test-email', function () {
    $patient = \App\Models\Patient::first();
    if ($patient) {
        \Mail::to($patient->email)->send(new \App\Mail\PatientRegistrationMail($patient, 'testpassword'));
        return 'Test email sent to ' . $patient->email;
    }
    return 'No patient found for testing';
});