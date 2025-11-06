<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;

use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor');
    }

    public function dashboard()
    {
        $doctor = Auth::guard('doctor')->user();

        $stats = [
            'today_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->count(),
            'total_patients' => Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->count(),
            'pending_consultations' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'scheduled')->count(),
            'completed_today' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->where('status', 'completed')->count(),
            'total_appointments' => Appointment::where('doctor_id', $doctor->id)->count(),
            'confirmed_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'confirmed')->count(),
        ];

        $todaySchedule = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('stats', 'todaySchedule', 'recentAppointments'));
    }

    public function appointments(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();

        $query = Appointment::with('patient')
            ->where('doctor_id', $doctorId);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('appointment_id', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                            ->orderBy('appointment_time', 'desc')
                            ->paginate(10);

        return view('doctor.appointment', compact('appointments'));
    }

    public function editAppointment($id)
    {
        $doctorId = Auth::guard('doctor')->id();
        
        $appointment = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->findOrFail($id);

        $appointmentTypes = [
            'consultation' => 'Consultation',
            'checkup' => 'Checkup',
            'surgery' => 'Surgery',
            'follow-up' => 'Follow-up',
            'emergency' => 'Emergency'
        ];

        $statusOptions = [
            'scheduled' => 'Scheduled',
            'confirmed' => 'Confirmed',
            'in-progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return view('doctor.appointments.edit', compact('appointment', 'appointmentTypes', 'statusOptions'));
    }

    public function updateAppointment(UpdateAppointmentRequest $request, $id)
    {
        $doctorId = Auth::guard('doctor')->id();
        
        $appointment = Appointment::where('doctor_id', $doctorId)
            ->findOrFail($id);

        $validated = $request->validated();

        try {
            $appointment->update([
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'appointment_type' => $validated['appointment_type'],
                'status' => $validated['status'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'],
                'updated_by' => $doctorId,
            ]);

            if ($appointment->wasChanged('status')) {
                \Log::info("Appointment {$appointment->id} status changed to {$validated['status']} by doctor {$doctorId}");
            }

            return redirect()->route('doctor.appointments')
                ->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update appointment. Please try again.');
        }
    }

    public function updateAppointmentStatus(UpdateAppointmentStatusRequest $request, $id)
    {
        $doctorId = Auth::guard('doctor')->id();
        
        $appointment = Appointment::where('doctor_id', $doctorId)
            ->findOrFail($id);

        $validated = $request->validated();

        try {
            $oldStatus = $appointment->status;
            $newStatus = $validated['status'];

            $appointment->update([
                'status' => $newStatus,
                'updated_by' => $doctorId,
            ]);

            if ($newStatus === 'in-progress') {
                $appointment->update(['started_at' => now()]);
            } elseif ($newStatus === 'completed') {
                $appointment->update(['completed_at' => now()]);
            } elseif ($newStatus === 'cancelled') {
                $appointment->update(['cancelled_at' => now()]);
            }

            return redirect()->back()
                ->with('success', 'Appointment status updated to ' . $newStatus . ' successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment status update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update appointment status. Please try again.');
        }
    }

    public function showAppointment($id)
    {
        $doctorId = Auth::guard('doctor')->id();
        
        $appointment = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->findOrFail($id);

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function viewPatients(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        $search = $request->input('search');

        $patients = Patient::whereHas('appointments', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('doctor.view-patient', compact('patients', 'search'));
    }

    public function showPatient(Patient $patient)
    {
        $doctor = Auth::guard('doctor')->user();

        if (!Appointment::where('doctor_id', $doctor->id)->where('patient_id', $patient->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->get();

        $appointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->get();

        return view('doctor.patient-show', compact('patient', 'medicalRecords', 'appointments'));
    }

    public function patientMedicalRecords(Patient $patient)
    {
        $doctor = Auth::guard('doctor')->user();

        if (!Appointment::where('doctor_id', $doctor->id)->where('patient_id', $patient->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $medicalRecords = MedicalRecord::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('doctor.patient-medical-records', compact('patient', 'medicalRecords'));
    }

    public function medicalRecords(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        $search = $request->input('search');

        $medicalRecords = MedicalRecord::with('patient')
            ->where('doctor_id', $doctor->id)
            ->when($search, function ($query, $search) {
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('diagnosis', 'like', "%{$search}%")
                ->orWhere('treatment', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('doctor.medical-record', compact('medicalRecords', 'search'));
    }

    public function createMedicalRecord()
    {
        $doctor = Auth::guard('doctor')->user();
        $patients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->get();

        return view('doctor.medical-record-create', compact('patients'));
    }

    public function storeMedicalRecord(StoreMedicalRecordRequest $request)
    {
        $validated = $request->validated();
        $doctorId = Auth::guard('doctor')->id();

        try {
            DB::transaction(function() use ($validated, $doctorId) {
                $bmi = null;
                $bmiCategory = null;
                if (!empty($validated['height']) && $validated['height'] > 0) {
                    $heightInMeters = $validated['height'] / 100;
                    $bmi = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
                    $bmiCategory = $bmi < 18.5 ? 'Underweight' : ($bmi < 25 ? 'Normal' : ($bmi < 30 ? 'Overweight' : 'Obese'));
                }

                MedicalRecord::create(array_merge($validated, [
                    'doctor_id' => $doctorId,
                    'record_id' => 'MR'.time(),
                    'bmi' => $bmi,
                    'bmi_category' => $bmiCategory,
                ]));
            });

            return redirect()->route('doctor.medical-records')
                ->with('success', 'Medical record added successfully!');

        } catch (\Exception $e) {
            \Log::error('Medical record creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create medical record. Please try again.');
        }
    }

    public function showMedicalRecord(MedicalRecord $medicalRecord)
    {
        $doctor = Auth::guard('doctor')->user();
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('doctor.medical-record-show', compact('medicalRecord'));
    }

    public function editMedicalRecord(MedicalRecord $medicalRecord)
    {
        $doctor = Auth::guard('doctor')->user();
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $patients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->get();
        return view('doctor.medical-record-edit', compact('medicalRecord', 'patients'));
    }

    public function updateMedicalRecord(StoreMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $doctor = Auth::guard('doctor')->user();
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        try {
            DB::transaction(function() use ($medicalRecord, $validated) {
                $bmi = null;
                $bmiCategory = null;
                if (!empty($validated['height']) && $validated['height'] > 0) {
                    $heightInMeters = $validated['height'] / 100;
                    $bmi = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
                    $bmiCategory = $bmi < 18.5 ? 'Underweight' : ($bmi < 25 ? 'Normal' : ($bmi < 30 ? 'Overweight' : 'Obese'));
                }

                $medicalRecord->update(array_merge($validated, [
                    'bmi' => $bmi,
                    'bmi_category' => $bmiCategory,
                ]));
            });

            return redirect()->route('doctor.medical-records')
                ->with('success', 'Medical record updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Medical record update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update medical record. Please try again.');
        }
    }

    public function destroyMedicalRecord(MedicalRecord $medicalRecord)
    {
        $doctor = Auth::guard('doctor')->user();
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $medicalRecord->delete();
            return redirect()->route('doctor.medical-records')
                ->with('success', 'Medical record deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Medical record deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete medical record. Please try again.');
        }
    }

    public function schedule()
    {
        $doctor = Auth::guard('doctor')->user();
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('is_active', true)
            ->get();

        return view('doctor.schedule.index', compact('schedules'));
    }

    public function quickStats()
    {
        $doctor = Auth::guard('doctor')->user();

        $stats = [
            'today_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->count(),
            'total_patients' => Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'scheduled')
                ->count(),
            'completed_today' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->where('status', 'completed')
                ->count(),
        ];

        return response()->json($stats);
    }

    public function getAvailableSlots(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();
        $date = $request->input('date');
        
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in-progress'])
            ->pluck('appointment_time')
            ->toArray();

        $availableSlots = [];
        $startTime = strtotime('09:00');
        $endTime = strtotime('17:00');
        
        for ($time = $startTime; $time <= $endTime; $time += 1800) {
            $slot = date('H:i', $time);
            if (!in_array($slot, $bookedSlots)) {
                $availableSlots[] = $slot;
            }
        }

        return response()->json([
            'available_slots' => $availableSlots,
            'date' => $date
        ]);
    }
}