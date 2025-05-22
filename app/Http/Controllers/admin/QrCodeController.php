<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceItem;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    // Hiển thị QR code của thiết bị
    public function show($id)
    {
        $deviceItem = DeviceItem::findOrFail($id);
        
        // Tạo QR code nếu chưa có
        if (!$deviceItem->qr_code) {
            $deviceItem->generateQrCode();
        }

        return view('admin.qrcodes.show', compact('deviceItem'));
    }

    // Tạo lại QR code
    public function regenerate($id)
    {
        $deviceItem = DeviceItem::findOrFail($id);
        
        // Xóa QR code cũ nếu có
        if ($deviceItem->qr_code) {
            Storage::disk('public')->delete($deviceItem->qr_code);
        }

        $deviceItem->generateQrCode();

        return redirect()->back()->with('success', 'Đã tạo lại mã QR code thành công!');
    }

    // Xử lý khi quét QR code
    public function scan(Request $request, $token)
    {
        $deviceItem = DeviceItem::where('qr_token', $token)->firstOrFail();
        $userId = auth()->id(); // null nếu không đăng nhập

        // Ghi lại lịch sử quét
        $deviceItem->logScan('view', $userId);

        // Nếu là mobile app hoặc yêu cầu JSON
        if ($request->wantsJson()) {
            return response()->json([
                'device' => $deviceItem->load('device'),
                'can_update_status' => auth()->check()
            ]);
        }

        // Hiển thị trang web
        return view('admin.qrcodes.scan', compact('deviceItem'));
    }

    // Cập nhật trạng thái qua QR code
    public function updateStatus(Request $request, $token)
    {
        $request->validate([
            'status' => 'required|in:available,borrowed,damaged,maintenance',
            'notes' => 'nullable|string|max:500'
        ]);

        $deviceItem = DeviceItem::where('qr_token', $token)->firstOrFail();

        // Chỉ cho phép người dùng đã đăng nhập cập nhật trạng thái
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deviceItem->updateStatusViaQr(
            $request->status,
            auth()->id(),
            $request->notes
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Cập nhật trạng thái thành công',
                'device' => $deviceItem->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    // Hiển thị lịch sử quét
    public function history($id)
    {
        $deviceItem = DeviceItem::findOrFail($id);
        $scans = $deviceItem->qrScans()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.qrcodes.history', compact('deviceItem', 'scans'));
    }

    // In nhiều QR code
    public function printMultiple(Request $request)
    {
        $deviceItems = DeviceItem::whereIn('id', $request->device_items)->get();
        
        // Tạo QR code cho các thiết bị chưa có
        foreach ($deviceItems as $item) {
            if (!$item->qr_code) {
                $item->generateQrCode();
            }
        }

        return view('admin.qrcodes.print', compact('deviceItems'));
    }
} 