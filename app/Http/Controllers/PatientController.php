<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UpdatePatientProfileRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\StoreMedicalRecordRequest;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Doctor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function patientindex()
    {
        $patients = Patient::latest()->paginate(10);
        return view('patient.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();
        
        $patientData = $validated;
        unset($patientData['password_confirmation']);
        
        $patientData['password'] = Hash::make($validated['password']);

        Patient::create($patientData);

        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(5);

        $medicalRecords = MedicalRecord::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(5);

        return view('patients.show', compact('patient', 'appointments', 'medicalRecords'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['password_confirmation']);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    public function toggleStatus(Patient $patient)
    {
        $patient->update(['is_active' => !$patient->is_active]);

        $status = $patient->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Patient {$status} successfully.");
    }

    /**
     * Show patient appointments (Admin view)
     */
    public function appointments(Patient $patient)
    {
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patients.appointments.index', compact('patient', 'appointments'));
    }

    /**
     * Show form to create appointment for patient (Admin view)
     */
    public function createAppointment(Patient $patient)
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('patients.appointments.create', compact('patient', 'doctors'));
    }

    /**
     * Store appointment for patient (Admin view)
     */
    public function storeAppointment(StoreAppointmentRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            Appointment::create([
                'appointment_id' => 'APT' . time() . Str::random(4),
                'patient_id' => $patient->id,
                'doctor_id' => $validated['doctor_id'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
            ]);

            DB::commit();

            return redirect()->route('patients.appointments', $patient)
                ->with('success', 'Appointment created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create appointment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show form to edit appointment (Admin view)
     */
    public function editAppointment(Patient $patient, Appointment $appointment)
    {
        // Verify the appointment belongs to the patient
        if ($appointment->patient_id !== $patient->id) {
            abort(404, 'Appointment not found for this patient.');
        }

        $doctors = Doctor::where('is_active', true)->get();
        return view('patients.appointments.edit', compact('patient', 'appointment', 'doctors'));
    }

    /**
     * Update appointment (Admin view)
     */
    public function updateAppointment(UpdateAppointmentRequest $request, Patient $patient, Appointment $appointment)
    {
        // Verify the appointment belongs to the patient
        if ($appointment->patient_id !== $patient->id) {
            abort(404, 'Appointment not found for this patient.');
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $appointment->update([
                'doctor_id' => $validated['doctor_id'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? $appointment->status,
            ]);

            DB::commit();

            return redirect()->route('patients.appointments', $patient)
                ->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update appointment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete appointment (Admin view)
     */
    public function destroyAppointment(Patient $patient, Appointment $appointment)
    {
        // Verify the appointment belongs to the patient
        if ($appointment->patient_id !== $patient->id) {
            abort(404, 'Appointment not found for this patient.');
        }

        DB::beginTransaction();

        try {
            $appointment->delete();

            DB::commit();

            return redirect()->route('patients.appointments', $patient)
                ->with('success', 'Appointment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete appointment. Please try again.');
        }
    }

    /**
     * Show patient medical records (Admin view)
     */
    public function medicalRecords(Patient $patient)
    {
        $medicalRecords = MedicalRecord::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patients.medical-records.index', compact('patient', 'medicalRecords'));
    }

    /**
     * Show form to create medical record for patient (Admin view)
     */
    public function createMedicalRecord(Patient $patient)
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('patients.medical-records.create', compact('patient', 'doctors'));
    }


    // =============================================
    // PATIENT PORTAL METHODS (Patient Side)
    // =============================================

    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();
        return view('patient.dashboard', compact('patient'));
    }

    public function patientAppointments()
    {
        $patient = Auth::guard('patient')->user();
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments', 'patient'));
    }

    public function createPatientAppointment()
    {
        $patient = Auth::guard('patient')->user();
        $doctors = Doctor::where('is_active', true)->get();
        return view('patient.appointments.create', compact('patient', 'doctors'));
    }

    public function editPatientAppointment(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['scheduled', 'confirmed'])) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Cannot edit appointment with current status: ' . $appointment->status);
        }

        $patient = Auth::guard('patient')->user();
        $doctors = Doctor::where('is_active', true)->get();
        
        return view('patient.appointments.edit', compact('appointment', 'patient', 'doctors'));
    }

    public function storePatientAppointment(StoreAppointmentRequest $request)
    {
        $patient = Auth::guard('patient')->user();
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            Appointment::create([
                'appointment_id' => 'APT' . time(),
                'patient_id' => $patient->id,
                'doctor_id' => $validated['doctor_id'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
            ]);

            DB::commit();

            return redirect()->route('patient.appointments.index')
                ->with('success', 'Appointment created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create appointment. Please try again.')
                ->withInput();
        }
    }

    public function updatePatientAppointment(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['scheduled', 'confirmed'])) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Cannot update appointment with current status: ' . $appointment->status);
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $appointment->update([
                'doctor_id' => $validated['doctor_id'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('patient.appointments.index')
                ->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update appointment. Please try again.')
                ->withInput();
        }
    }

    public function showPatientAppointment(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->load('doctor');
        return view('patient.appointments.show', compact('appointment'));
    }
        /**
     * Check doctor availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $doctorId = $request->doctor_id;
        $appointmentDate = $request->appointment_date;
        $appointmentTime = $request->appointment_time;

        // Check if doctor has any appointment at the same date and time
        $existingAppointment = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->whereIn('status', ['pending', 'confirmed', 'scheduled'])
            ->first();

        if ($existingAppointment) {
            return response()->json([
                'available' => false,
                'message' => 'Doctor already has an appointment at this time. Please choose a different time.'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Doctor is available at this time.'
        ]);
    }

    public function destroyPatientAppointment(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['scheduled', 'cancelled'])) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Cannot delete appointment with current status: ' . $appointment->status);
        }

        DB::beginTransaction();

        try {
            $appointment->delete();

            DB::commit();

            return redirect()->route('patient.appointments.index')
                ->with('success', 'Appointment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete appointment. Please try again.');
        }
    }

    public function cancelPatientAppointment(Request $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['scheduled', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Cannot cancel appointment with current status: ' . $appointment->status);
        }

        DB::beginTransaction();

        try {
            $appointment->update([
                'status' => 'cancelled',
                'notes' => $appointment->notes . "\n\nCancelled by patient on " . now()->format('Y-m-d H:i:s')
            ]);

            DB::commit();

            return redirect()->route('patient.appointments.index')
                ->with('success', 'Appointment cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel appointment. Please try again.');
        }
    }

    public function patientMedicalRecords()
    {
        $patient = Auth::guard('patient')->user();
        $medicalRecords = MedicalRecord::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patient.medical-records.index', compact('medicalRecords', 'patient'));
    }

    public function showMedicalRecord(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->patient_id !== Auth::guard('patient')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $medicalRecord->load('doctor');
        return view('patient.medical-records.show', compact('medicalRecord'));
    }

    public function profile()
    {
        $patient = Auth::guard('patient')->user();
        return view('patient.profile', compact('patient'));
    }

    public function updateProfile(UpdatePatientProfileRequest $request)
    {
        $patient = Auth::guard('patient')->user();
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['password_confirmation']);

        $patient->update($validated);

        return redirect()->route('patient.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function logout(Request $request)
    {
        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/patient/login');
    }
}