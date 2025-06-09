<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBorrow;
use App\Models\DeviceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReturnReminder;
use Illuminate\Support\Facades\Mail;

class RoomBorrowController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $roomBorrows = RoomBorrow::with(['user', 'room'])->latest()->get();
        } else {
            // Nếu là teacher hoặc student thì chỉ hiển thị danh sách mượn phòng của họ
            $roomBorrows = RoomBorrow::with(['user', 'room'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }
        return view('admin.room-borrows.index', compact('roomBorrows'));
    }

    public function create()
    {
        $rooms = Room::whereDoesntHave('borrows', function($query) {
            $query->whereIn('status', ['pending', 'approved']);
        })->get();

        return view('admin.room-borrows.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after:borrow_date',
            'reason' => 'required|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Tạo phiếu mượn phòng
            $roomBorrow = RoomBorrow::create([
                'room_id' => $validated['room_id'],
                'user_id' => auth()->id(),
                'borrow_date' => $validated['borrow_date'],
                'return_date' => $validated['return_date'],
                'reason' => $validated['reason'],
                'status' => 'pending'
            ]);

            DB::commit();
            return redirect()->route('room-borrows.index')
                ->with('success', 'Phiếu mượn phòng đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function show(RoomBorrow $roomBorrow)
    {
        $roomBorrow->load(['room', 'user', 'staff']);
        return view('admin.room-borrows.show', compact('roomBorrow'));
    }

    public function approve($id)
    {
        try {
            $roomBorrow = RoomBorrow::findOrFail($id);

            if ($roomBorrow->status !== 'pending') {
                return back()->with('error', 'Chỉ có thể duyệt phiếu mượn đang chờ duyệt.');
            }

            $roomBorrow->update([
                'status' => 'approved',
                'staff_id' => auth()->id()
            ]);

            return back()->with('success', 'Phiếu mượn phòng đã được duyệt.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function markReturned($id)
    {
        DB::beginTransaction();
        try {
            $roomBorrow = RoomBorrow::findOrFail($id);

            if ($roomBorrow->status !== 'approved') {
                return back()->with('error', 'Chỉ có thể đánh dấu trả cho phiếu mượn đã được duyệt.');
            }

            $roomBorrow->update([
                'status' => 'returned',
                'actual_return_date' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Phiếu mượn phòng đã được đánh dấu là đã trả.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $roomBorrow = RoomBorrow::findOrFail($id);

            if ($roomBorrow->status !== 'pending') {
                return back()->with('error', 'Chỉ có thể hủy phiếu mượn đang chờ duyệt.');
            }

            $roomBorrow->update([
                'status' => 'cancelled'
            ]);

            DB::commit();
            return back()->with('success', 'Phiếu mượn phòng đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function sendReturnReminder(RoomBorrow $roomBorrow)
    {
        try {
            // Load các relationship cần thiết
            $roomBorrow->load(['user', 'room']);
            
            // Gửi email thông báo
            Mail::to($roomBorrow->user->email)->send(new ReturnReminder($roomBorrow, 'room', false));

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi thông báo thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi thông báo: ' . $e->getMessage()
            ], 500);
        }
    }
}
