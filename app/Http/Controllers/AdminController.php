<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Room;
use App\Models\MedicalRecord;
use App\Models\Reception;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Requests\StoreReceptionRequest;
use App\Http\Requests\UpdateReceptionRequest;
use App\Http\Requests\RoomStatusRequest;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard(): View
    {
        try {
            $stats = [
                'total_doctors' => Doctor::count(),
                'total_patients' => Patient::count(),
                'total_appointments' => Appointment::count(),
                'available_rooms' => Room::where('status', 'available')->count(),
                'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
                'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
                'active_doctors' => Doctor::where('is_active', true)->count(),
                'occupied_rooms' => Room::where('status', 'occupied')->count(),
                'today_revenue' => Appointment::whereDate('appointment_date', today())
                    ->where('status', 'completed')
                    ->sum('fee'),
            ];

            $recentAppointments = Appointment::with(['patient', 'doctor'])
                ->latest()
                ->take(10)
                ->get();

            return view('admin.dashboard', compact('stats', 'recentAppointments'));

        } catch (\Exception $e) {
            return view('admin.dashboard')->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    public function doctors(Request $request): View
    {
        try {
            $query = Doctor::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%")
                        ->orWhere('doctor_id', 'like', "%{$search}%")
                        ->orWhere('qualification', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            $doctors = $query->latest()->paginate(10)->appends($request->only('search', 'status'));

            return view('admin.doctor-list', compact('doctors'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading doctors: ' . $e->getMessage());
        }
    }

    public function storeDoctor(StoreDoctorRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $doctor = Doctor::create([
                'doctor_id' => 'DOC' . time(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'],
                'experience_years' => $validated['experience_years'],
                'bio' => $validated['bio'] ?? null,
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating doctor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateDoctor(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'],
                'experience_years' => $validated['experience_years'],
                'bio' => $validated['bio'] ?? null,
                'is_active' => $validated['is_active'] ?? $doctor->is_active,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $doctor->update($updateData);

            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating doctor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroyDoctor(Doctor $doctor): RedirectResponse
    {
        try {
            $doctorName = $doctor->name;
            $doctor->delete();

            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor ' . $doctorName . ' deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.doctors')
                ->with('error', 'Error deleting doctor: ' . $e->getMessage());
        }
    }

    public function toggleDoctorStatus(Doctor $doctor): RedirectResponse
    {
        try {
            $doctor->update([
                'is_active' => !$doctor->is_active
            ]);

            $status = $doctor->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Doctor {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating doctor status: ' . $e->getMessage());
        }
    }

    public function patients(Request $request): View
    {
        try {
            $query = Patient::query()->withCount(['appointments', 'medicalRecords']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%");
                });
            }

            $patients = $query->latest()->paginate(10)->appends($request->only('search'));

            return view('admin.patient-list', compact('patients'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading patients: ' . $e->getMessage());
        }
    }

    public function destroyPatient(Patient $patient): RedirectResponse
    {
        try {
            $patientName = $patient->first_name . ' ' . $patient->last_name;
            $patient->delete();

            return redirect()->route('admin.patients')
                ->with('success', 'Patient ' . $patientName . ' deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.patients')
                ->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }

    public function togglePatientStatus($id): RedirectResponse
    {
        try {
            $patient = Patient::findOrFail($id);
            $patient->update([
                'is_active' => !$patient->is_active
            ]);

            $status = $patient->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Patient {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating patient status: ' . $e->getMessage());
        }
    }

    public function appointments(Request $request): View
    {
        try {
            $query = Appointment::with(['patient', 'doctor']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('appointment_id', 'like', "%{$search}%")
                        ->orWhereHas('patient', fn($q2) =>
                            $q2->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%"))
                        ->orWhereHas('doctor', fn($q3) =>
                            $q3->where('name', 'like', "%{$search}%"));
                });
            }

            $appointments = $query->latest()->paginate(10)->appends($request->only('search'));
            $patients = Patient::where('is_active', true)->orderBy('first_name')->get();
            $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

            return view('admin.appointment', compact('appointments', 'patients', 'doctors'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading appointments: ' . $e->getMessage());
        }
    }

    public function destroyAppointment($id): RedirectResponse
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointmentId = $appointment->appointment_id;
            $appointment->delete();

            return redirect()->route('admin.appointments')
                ->with('success', 'Appointment ' . $appointmentId . ' deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.appointments')
                ->with('error', 'Error deleting appointment: ' . $e->getMessage());
        }
    }

    public function departments(Request $request): View
    {
        try {
            $query = Department::with(['headDoctor', 'rooms']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%")
                    ->orWhereHas('headDoctor', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            $departments = $query->latest()->paginate(8)->appends($request->only('search'));
            $doctors = Doctor::where('is_active', true)->get();

            return view('admin.department', compact('departments', 'doctors'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading departments: ' . $e->getMessage());
        }
    }

    public function storeDepartment(StoreDepartmentRequest $request): RedirectResponse
    {
        try {
            Department::create($request->validated());
            return redirect()->route('admin.departments')->with('success', 'Department added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating department: ' . $e->getMessage())->withInput();
        }
    }

    public function updateDepartment(UpdateDepartmentRequest $request, $id): RedirectResponse
    {
        try {
            $department = Department::findOrFail($id);
            $department->update($request->validated());
            return redirect()->route('admin.departments')->with('success', 'Department updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating department: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyDepartment($id): RedirectResponse
    {
        try {
            $department = Department::findOrFail($id);
            $departmentName = $department->name;
            $department->delete();
            return redirect()->route('admin.departments')->with('success', 'Department ' . $departmentName . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.departments')->with('error', 'Error deleting department: ' . $e->getMessage());
        }
    }

    public function toggleDepartmentStatus($id): RedirectResponse
    {
        try {
            $department = Department::findOrFail($id);
            $department->update([
                'is_active' => !$department->is_active
            ]);

            $status = $department->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Department {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating department status: ' . $e->getMessage());
        }
    }

    public function rooms(Request $request): View
    {
        try {
            $query = Room::with('department');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('room_number', 'like', "%{$search}%")
                        ->orWhere('room_type', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('floor', 'like', "%{$search}%")
                        ->orWhereHas('department', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $rooms = $query->latest()->paginate(10)->appends($request->only('search'));
            $departments = Department::where('is_active', true)->get();

            return view('admin.rooms', compact('rooms', 'departments'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading rooms: ' . $e->getMessage());
        }
    }

    public function storeRoom(StoreRoomRequest $request): RedirectResponse
    {
        try {
            Room::create($request->validated());
            return redirect()->route('admin.rooms')->with('success', 'Room added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating room: ' . $e->getMessage())->withInput();
        }
    }

    public function updateRoom(UpdateRoomRequest $request, $id): RedirectResponse
    {
        try {
            $room = Room::findOrFail($id);
            $room->update($request->validated());
            return redirect()->route('admin.rooms')->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating room: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyRoom($id): RedirectResponse
    {
        try {
            $room = Room::findOrFail($id);
            $roomNumber = $room->room_number;
            $room->delete();
            return redirect()->route('admin.rooms')->with('success', 'Room ' . $roomNumber . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms')->with('error', 'Error deleting room: ' . $e->getMessage());
        }
    }

    public function updateRoomStatus(RoomStatusRequest $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $room = Room::findOrFail($id);
            $oldStatus = $room->status;
            $room->update(['status' => $request->status]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Room status updated successfully from ' . $oldStatus . ' to ' . $request->status,
                    'new_status' => $request->status,
                    'new_badge_class' => $this->getStatusBadgeClass($request->status),
                    'room_number' => $room->room_number
                ]);
            }
            
            return redirect()->route('admin.rooms')->with('success', 
                'Room ' . $room->room_number . ' status changed from ' . $oldStatus . ' to ' . $request->status
            );
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating room status: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error updating room status: ' . $e->getMessage());
        }
    }

    public function medicalRecords(Request $request): View
    {
        try {
            $query = MedicalRecord::with(['patient', 'doctor']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('diagnosis', 'like', "%{$search}%")
                        ->orWhere('treatment', 'like', "%{$search}%")
                        ->orWhereHas('patient', function ($q2) use ($search) {
                            $q2->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('doctor', function ($q3) use ($search) {
                            $q3->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $medicalRecords = $query->latest()->paginate(10)->appends($request->only('search'));
            $patients = Patient::where('is_active', true)->get();
            $doctors = Doctor::where('is_active', true)->get();

            return view('admin.medical-record', compact('medicalRecords', 'patients', 'doctors'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading medical records: ' . $e->getMessage());
        }
    }

    public function destroyMedicalRecord($id): RedirectResponse
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $medicalRecord->delete();
            return redirect()->route('admin.medical-records')->with('success', 'Medical record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.medical-records')->with('error', 'Error deleting medical record: ' . $e->getMessage());
        }
    }

    public function receptions(Request $request): View
    {
        try {
            $query = Reception::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $receptions = $query->latest()->paginate(10)->appends($request->only('search'));

            return view('admin.reception-list', compact('receptions'));

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error loading reception staff: ' . $e->getMessage());
        }
    }

    public function storeReception(StoreReceptionRequest $request): RedirectResponse
    {
        try {
            Reception::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active ?? true,
            ]);

            return redirect()->route('admin.receptions')->with('success', 'Reception staff added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating reception staff: ' . $e->getMessage())->withInput();
        }
    }

    public function updateReception(UpdateReceptionRequest $request, $id): RedirectResponse
    {
        try {
            $reception = Reception::findOrFail($id);
            $data = $request->validated();
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $reception->update($data);
            return redirect()->route('admin.receptions')->with('success', 'Reception staff updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating reception staff: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyReception($id): RedirectResponse
    {
        try {
            $reception = Reception::findOrFail($id);
            $receptionName = $reception->name;
            $reception->delete();
            return redirect()->route('admin.receptions')->with('success', 'Reception staff ' . $receptionName . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.receptions')->with('error', 'Error deleting reception staff: ' . $e->getMessage());
        }
    }

    public function toggleReceptionStatus($id): RedirectResponse
    {
        try {
            $reception = Reception::findOrFail($id);
            $reception->update([
                'is_active' => !$reception->is_active
            ]);

            $status = $reception->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Reception staff {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating reception staff status: ' . $e->getMessage());
        }
    }

    private function getStatusBadgeClass($status): string
    {
        return match($status) {
            'available' => 'bg-success',
            'occupied' => 'bg-warning',
            'maintenance' => 'bg-danger',
            'cleaning' => 'bg-info',
            default => 'bg-secondary'
        };
    }
}