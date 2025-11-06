<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomAllocationRequest;
use App\Http\Requests\UpdateRoomAllocationRequest;
use App\Http\Requests\DischargeRoomAllocationRequest;
use App\Http\Requests\AddPaymentRequest;

use App\Models\RoomAllocation;
use App\Models\Room;
use App\Models\Patient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:reception');
    }

    public function create()
    {
        $availableRooms = Room::where('status', 'available')
            ->whereColumn('occupied', '<', 'capacity')
            ->get();
            
        $patientsWithActiveAllocations = RoomAllocation::where('status', 'active')
            ->pluck('patient_id')
            ->toArray();
            
        $patients = Patient::whereNotIn('id', $patientsWithActiveAllocations)->get();

        return view('reception.room-allocation-create', compact('availableRooms', 'patients'));
    }

    public function store(StoreRoomAllocationRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $existingAllocation = RoomAllocation::where('patient_id', $data['patient_id'])
                ->where('status', 'active')
                ->first();

            if ($existingAllocation) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Patient already has an active room allocation (Room: ' . $existingAllocation->room->room_number . '). Please discharge the patient first before allocating a new room.');
            }

            $room = Room::findOrFail($data['room_id']);
            if ($room->status !== 'available' || $room->occupied >= $room->capacity) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected room is not available or is at full capacity. Please choose a different room.');
            }

            $allocationId = 'ALLOC' . date('Ymd') . str_pad(RoomAllocation::count() + 1, 4, '0', STR_PAD_LEFT);

            $allocation = RoomAllocation::create([
                'allocation_id' => $allocationId,
                'patient_id' => $data['patient_id'],
                'room_id' => $data['room_id'],
                'admission_date' => $data['admission_date'],
                'estimated_stay_days' => $data['estimated_stay_days'],
                'estimated_discharge_date' => Carbon::parse($data['admission_date'])->addDays($data['estimated_stay_days']),
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
                'total_amount' => $data['total_amount'], 
                'paid_amount' => 0,
                'status' => 'active',
                'allocated_by' => Auth::id()
            ]);

            $room->increment('occupied');
            if ($room->occupied >= $room->capacity) {
                $room->update(['status' => 'occupied']);
            }

            DB::commit();

            return redirect()->route('reception.room-allocations')
                ->with('success', 'Room allocated successfully! Allocation ID: ' . $allocationId);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Room allocation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to allocate room. Please try again.');
        }
    }

    public function index()
    {
        $allocations = RoomAllocation::with(['room', 'patient'])
            ->latest()
            ->paginate(10);

        return view('reception.room-allocations', compact('allocations'));
    }

    public function edit($id)
    {
        $allocation = RoomAllocation::with(['room', 'patient'])->findOrFail($id);

        if ($allocation->status !== 'active') {
            return redirect()->route('reception.room-allocations')
                ->with('error', 'Only active allocations can be edited.');
        }

        $availableRooms = Room::where('status', 'available')
            ->whereColumn('occupied', '<', 'capacity')
            ->get();
            
        $patientsWithActiveAllocations = RoomAllocation::where('status', 'active')
            ->where('patient_id', '!=', $allocation->patient_id)
            ->pluck('patient_id')
            ->toArray();
            
        $patients = Patient::whereNotIn('id', $patientsWithActiveAllocations)->get();

        return view('reception.room-allocation-edit', compact('allocation', 'availableRooms', 'patients'));
    }

    public function update(UpdateRoomAllocationRequest $request, $id)
    {
        $data = $request->validated();
        $allocation = RoomAllocation::findOrFail($id);

        if ($allocation->status !== 'active') {
            return redirect()->route('reception.room-allocations')
                ->with('error', 'Only active allocations can be updated.');
        }

        try {
            DB::beginTransaction();

            $newRoom = Room::findOrFail($data['room_id']);
            $oldRoom = $allocation->room;

            if ($allocation->room_id != $data['room_id']) {
                if ($newRoom->status !== 'available' || $newRoom->occupied >= $newRoom->capacity) {
                    return back()->with('error', 'Selected room is not available.')->withInput();
                }

                $oldRoom->decrement('occupied');
                if ($oldRoom->occupied < $oldRoom->capacity && $oldRoom->status === 'occupied') {
                    $oldRoom->update(['status' => 'available']);
                }

                $newRoom->increment('occupied');
                if ($newRoom->occupied >= $newRoom->capacity) {
                    $newRoom->update(['status' => 'occupied']);
                }
            }

            $allocation->update([
                'room_id' => $data['room_id'],
                'admission_date' => $data['admission_date'],
                'estimated_stay_days' => $data['estimated_stay_days'],
                'estimated_discharge_date' => Carbon::parse($data['admission_date'])->addDays($data['estimated_stay_days']),
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
                'total_amount' => $data['total_amount'], 
            ]);

            DB::commit();

            return redirect()->route('reception.room-allocations')
                ->with('success', 'Room allocation updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Room allocation update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update room allocation. Please try again.');
        }
    }

    public function destroy($id)
    {
        $allocation = RoomAllocation::findOrFail($id);

        try {
            DB::beginTransaction();

            if ($allocation->status !== 'active') {
                return redirect()->route('reception.room-allocations')
                    ->with('error', 'Only active allocations can be deleted.');
            }

            $room = $allocation->room;

            $room->decrement('occupied');
            if ($room->occupied < $room->capacity && $room->status === 'occupied') {
                $room->update(['status' => 'available']);
            }

            $allocation->delete();

            DB::commit();

            return redirect()->route('reception.room-allocations')
                ->with('success', 'Room allocation deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Room allocation deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete room allocation. Please try again.');
        }
    }

    public function discharge(DischargeRoomAllocationRequest $request, $id)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();
            
            $allocation = RoomAllocation::findOrFail($id);

            if ($allocation->status !== 'active') {
                return back()->with('error', 'Only active allocations can be discharged.');
            }

            $allocation->update([
                'discharge_date' => $data['discharge_date'],
                'discharge_notes' => $data['discharge_notes'] ?? null,
                'status' => 'discharged',
                'actual_stay_days' => Carbon::parse($allocation->admission_date)->diffInDays($data['discharge_date']),
            ]);

            $room = $allocation->room;
            $room->decrement('occupied');
            if ($room->occupied < $room->capacity && $room->status === 'occupied') {
                $room->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('reception.room-allocations')
                ->with('success', 'Patient discharged successfully! Room is now available.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Patient discharge failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to discharge patient. Please try again.');
        }
    }

    public function addPayment(AddPaymentRequest $request, $id)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $allocation = RoomAllocation::findOrFail($id);

            $newPaidAmount = $allocation->paid_amount + $data['payment_amount'];

            if ($newPaidAmount > $allocation->total_amount) {
                return back()->with('error', 'Payment amount cannot exceed total amount.');
            }

            $allocation->update(['paid_amount' => $newPaidAmount]);

            if ($newPaidAmount >= $allocation->total_amount) {
                $allocation->update(['payment_status' => 'paid']);
            } else {
                $allocation->update(['payment_status' => 'partial']);
            }

            DB::commit();

            return back()->with('success', 'Payment added successfully! Current paid amount: ₹' . number_format($newPaidAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment addition failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to add payment. Please try again.');
        }
    }

    public function show($id)
    {
        $allocation = RoomAllocation::with(['room', 'patient', 'allocatedBy'])
            ->findOrFail($id);

        return view('reception.room-allocation-show', compact('allocation'));
    }
}