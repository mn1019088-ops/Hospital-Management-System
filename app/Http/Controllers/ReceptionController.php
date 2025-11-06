<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Room;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\AvailableSlotsRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Requests\UpdateRoomStatusRequest;
use App\Http\Requests\SearchRoomRequest;
use App\Http\Requests\AvailableRoomsRequest;

class ReceptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:reception');
    }

    public function dashboard()
    {
        $stats = [
            'today_appointments'     => Appointment::whereDate('appointment_date', today())->count(),
            'total_patients'         => Patient::count(),
            'new_patients_today'     => Patient::whereDate('created_at', today())->count(),
            'doctors_available'      => Doctor::where('is_active', true)->count(),
            'pending_appointments'   => Appointment::where('status', 'scheduled')->count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'patients_this_week'     => Patient::where('created_at', '>=', now()->subWeek())->count(),
            'appointments_this_week' => Appointment::where('appointment_date', '>=', now()->startOfWeek())->count(),
            'total_departments'      => Department::count(),
            'total_rooms'            => Room::count(),
            'available_rooms'        => Room::where('status', 'available')->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentPatients = Patient::latest()->take(5)->get();

        return view('reception.dashboard', compact('stats', 'todayAppointments', 'recentPatients'));
    }

    public function patientAdd()
    {
        return view('reception.patient-add');
    }

    public function storePatient(StorePatientRequest $request)
    {
        try {
            DB::beginTransaction();

            $patient = Patient::create([
                'patient_id'    => 'PAT' . time(),
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender'        => $request->gender,
                'blood_group'   => $request->blood_group,
                'address'       => $request->address,
                'is_active'     => true,
            ]);

            DB::commit();
            return redirect()->route('reception.patient-add')->with('success', 'Patient registered successfully! ID: ' . $patient->patient_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to register patient. Please try again.')->withInput();
        }
    }

    public function patientList(Request $request)
    {
        $query = Patient::withCount(['appointments', 'medicalRecords']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('blood_group') && !empty($request->blood_group)) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('is_active', $request->status == 'active');
        }

        $patients = $query->latest()->paginate(10);

        return view('reception.patient-list', compact('patients'));
    }

    public function showPatient(Patient $patient)
    {
        $appointments = $patient->appointments()->with('doctor')->latest()->get();
        return view('reception.patient-show', compact('patient', 'appointments'));
    }

    public function updatePatient(UpdatePatientRequest $request, Patient $patient)
    {
        try {
            $patient->update($request->validated());
            return redirect()->route('reception.patients.list')->with('success', 'Patient updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update patient.')->withInput();
        }
    }

    public function togglePatientStatus(Patient $patient)
    {
        try {
            $patient->update([
                'is_active' => !$patient->is_active
            ]);

            $status = $patient->is_active ? 'activated' : 'deactivated';
            return redirect()->route('reception.patients.list')->with('success', "Patient {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update patient status.');
        }
    }

    public function deletePatient(Patient $patient)
    {
        try {
            DB::beginTransaction();

            if ($patient->appointments()->exists()) {
                return back()->with('error', 'Cannot delete patient with existing appointments.');
            }

            $patient->delete();

            DB::commit();
            return redirect()->route('reception.patients.list')
                ->with('success', 'Patient deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete patient. Please try again.');
        }
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('appointment_id', 'like', "%{$search}%")
                  ->orWhere('appointment_type', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('doctor_id') && !empty($request->doctor_id)) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $appointments = $query->latest()->paginate(10);
        $doctors = Doctor::where('is_active', true)->get();
        $patients = Patient::where('is_active', true)->get();

        return view('reception.appointment', compact('appointments', 'doctors', 'patients'));
    }

    public function createAppointment()
    {
        $patients = Patient::where('is_active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'patient_id']);
        
        $doctors = Doctor::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'specialization']);
        
        $appointmentTypes = [
            'consultation' => 'Consultation',
            'checkup' => 'Checkup',
            'surgery' => 'Surgery',
            'follow-up' => 'Follow-up'
        ];
        
        $today = Carbon::today()->format('Y-m-d');
        
        return view('reception.appointment-create', compact(
            'patients', 
            'doctors', 
            'appointmentTypes',
            'today'
        ));
    }

    public function storeAppointment(StoreAppointmentRequest $request)
    {
        try {
            DB::beginTransaction();

            $exists = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $request->appointment_time)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Doctor not available at selected time.')->withInput();
            }

            $patientConflict = Appointment::where('patient_id', $request->patient_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $request->appointment_time)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->exists();

            if ($patientConflict) {
                return back()->with('error', 'Patient already has an appointment at this time.')->withInput();
            }

            $appointmentDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->appointment_date . ' ' . $request->appointment_time);
            if ($appointmentDateTime->lte(Carbon::now())) {
                return back()->with('error', 'Cannot schedule appointment for past date/time.')->withInput();
            }

            $appointment = Appointment::create([
                'appointment_id'   => 'APT' . time(),
                'patient_id'       => $request->patient_id,
                'doctor_id'        => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'appointment_type' => $request->appointment_type,
                'reason'           => $request->reason,
                'fee'              => $request->fee ?? 0,
                'status'           => 'scheduled',
                'created_by'       => Auth::guard('reception')->id(),
            ]);

            DB::commit();
            return redirect()->route('reception.appointments')->with('success', 'Appointment scheduled successfully! ID: ' . $appointment->appointment_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to schedule appointment.')->withInput();
        }
    }

    public function showAppointment(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor']);
        return view('reception.appointment-show', compact('appointment'));
    }

    public function updateAppointment(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        try {
            $data = ['status' => $request->status];
            
            if ($request->status === 'confirmed') {
                $data['confirmed_at'] = now();
            } elseif ($request->status === 'cancelled') {
                $data['cancelled_at'] = now();
                $data['cancellation_reason'] = $request->cancellation_reason;
            }

            $appointment->update($data);
            return redirect()->route('reception.appointments')->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update appointment.');
        }
    }

    public function destroyAppointment(Appointment $appointment)
    {
        try {
            $appointment->delete();
            return redirect()->route('reception.appointments')->with('success', 'Appointment deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete appointment.');
        }
    }

    public function doctors(Request $request)
    {
        $query = Doctor::where('is_active', true)
            ->withCount(['appointments', 'schedules']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('qualification', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('specialization') && !empty($request->specialization)) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'available') {
                $query->whereHas('schedules', function($q) {
                    $q->where('is_active', true);
                });
            }
        }

        if ($request->has('experience') && !empty($request->experience)) {
            switch ($request->experience) {
                case '0-5':
                    $query->where('experience_years', '<=', 5);
                    break;
                case '6-10':
                    $query->whereBetween('experience_years', [6, 10]);
                    break;
                case '11-20':
                    $query->whereBetween('experience_years', [11, 20]);
                    break;
                case '20+':
                    $query->where('experience_years', '>', 20);
                    break;
            }
        }

        $doctors = $query->latest()->paginate(10);

        $specializations = Doctor::where('is_active', true)
            ->distinct()
            ->pluck('specialization')
            ->filter()
            ->values();

        return view('reception.doctor-list', compact('doctors', 'specializations'));
    }

    public function showDoctor(Doctor $doctor)
    {
        $schedules = $doctor->schedules()->where('is_active', true)->get();
        $appointments = $doctor->appointments()
            ->with('patient')
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return view('reception.doctor-show', compact('doctor', 'schedules', 'appointments'));
    }

    public function rooms(Request $request)
    {
        $query = Room::with('department');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_type', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%")
                  ->orWhereHas('department', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('room_type') && !empty($request->room_type)) {
            $query->where('room_type', $request->room_type);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('floor') && !empty($request->floor)) {
            $query->where('floor', $request->floor);
        }

        if ($request->has('department_id') && !empty($request->department_id)) {
            $query->where('department_id', $request->department_id);
        }

        $rooms = $query->latest()->paginate(10);

        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'maintenance_rooms' => Room::where('status', 'maintenance')->count(),
        ];

        $departments = Department::where('is_active', true)->get();

        return view('reception.rooms', compact('rooms', 'stats', 'departments'));
    }

    public function createRoom()
    {
        $departments = Department::where('is_active', true)->get();
        return view('reception.room-create', compact('departments'));
    }

    public function storeRoom(StoreRoomRequest $request)
    {
        try {
            DB::beginTransaction();

            $room = Room::create([
                'room_number' => $request->room_number,
                'room_type' => $request->room_type,
                'department_id' => $request->department_id,
                'ward' => $request->ward,
                'floor' => $request->floor,
                'capacity' => $request->capacity,
                'price_per_day' => $request->price_per_day,
                'status' => $request->status,
                'facilities' => $request->facilities,
                'notes' => $request->notes,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('reception.rooms')
                ->with('success', 'Room added successfully! Room Number: ' . $room->room_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add room. Please try again.')->withInput();
        }
    }

    public function showRoom(Room $room)
    {
        $room->load('department');
        return view('reception.room-show', compact('room'));
    }

    public function editRoom(Room $room)
    {
        $departments = Department::where('is_active', true)->get();
        return view('reception.room-edit', compact('room', 'departments'));
    }

    public function updateRoom(UpdateRoomRequest $request, Room $room)
    {
        try {
            $room->update($request->validated());

            return redirect()->route('reception.rooms')
                ->with('success', 'Room updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update room. Please try again.')->withInput();
        }
    }

    public function destroyRoom(Room $room)
    {
        try {
            if ($room->allocations()->exists()) {
                return back()->with('error', 'Cannot delete room with existing allocations.');
            }

            $room->delete();

            return redirect()->route('reception.rooms')
                ->with('success', 'Room deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete room. Please try again.');
        }
    }

    public function updateRoomStatus(UpdateRoomStatusRequest $request, Room $room)
    {
        try {
            $room->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Room status updated successfully!',
                'new_status' => $request->status,
                'new_badge_class' => $this->getStatusBadgeClass($request->status)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room status.'
            ], 500);
        }
    }

    public function getAvailableRooms(AvailableRoomsRequest $request)
    {
        $rooms = Room::where('department_id', $request->department_id)
            ->where('status', 'available')
            ->where('is_active', true)
            ->get(['id', 'room_number', 'room_type', 'price_per_day']);

        return response()->json($rooms);
    }

    public function departments(Request $request)
    {
        $query = Department::with('headDoctor');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhereHas('headDoctor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('is_active', $request->status == 'active');
        }

        if ($request->has('floor') && !empty($request->floor)) {
            $query->where('floor', $request->floor);
        }

        $departments = $query->latest()->paginate(10);
        $doctors = Doctor::where('is_active', true)->get();

        return view('reception.departments', compact('departments', 'doctors'));
    }

    public function quickStats()
    {
        return response()->json([
            'total_patients'        => Patient::count(),
            'today_appointments'    => Appointment::whereDate('appointment_date', today())->count(),
            'active_doctors'        => Doctor::where('is_active', true)->count(),
            'pending_appointments'  => Appointment::where('status', 'scheduled')->count(),
            'available_rooms'       => Room::where('status', 'available')->count(),
        ]);
    }

    public function getAvailableSlots(AvailableSlotsRequest $request)
    {
        $doctor = Doctor::findOrFail($request->doctor_id);
        $date = $request->date;
        $day = strtolower(Carbon::parse($date)->englishDayOfWeek);

        $schedule = $doctor->schedules()->where('day_of_week', $day)->where('is_active', true)->first();

        if (!$schedule) return response()->json(['available_slots' => []]);

        $start = Carbon::parse($schedule->start_time);
        $end   = Carbon::parse($schedule->end_time);
        $slots = [];

        while ($start->lt($end)) {
            $time = $start->format('H:i');
            $booked = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $date)
                ->where('appointment_time', $time)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->exists();

            if (!$booked) $slots[] = $time;
            $start->addMinutes(30);
        }

        return response()->json(['available_slots' => $slots]);
    }

    private function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'available':
                return 'bg-success';
            case 'occupied':
                return 'bg-warning text-dark';
            case 'maintenance':
                return 'bg-danger';
            case 'cleaning':
                return 'bg-info';
            default:
                return 'bg-secondary';
        }
    }

    private function generateAppointmentNumber()
    {
        $prefix = 'APT';
        $year = date('Y');
        $month = date('m');
        
        do {
            $sequence = str_pad(Appointment::whereYear('created_at', $year)->count() + 1, 4, '0', STR_PAD_LEFT);
            $appointmentNumber = "{$prefix}{$year}{$month}{$sequence}";
        } while (Appointment::where('appointment_number', $appointmentNumber)->exists());

        return $appointmentNumber;
    }
}