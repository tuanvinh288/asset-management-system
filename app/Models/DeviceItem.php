<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DeviceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'code',
        'serial_number',
        'status',
        'is_fixed',
        'room_id',
        'supplier_id',
        'qr_code',
        'qr_token',
        'last_scanned_at'
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'is_fixed' => 'boolean'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function qrScans()
    {
        return $this->hasMany(QrScan::class);
    }

    // Tạo QR code cho thiết bị
    public function generateQrCode()
    {
        if (!$this->qr_token) {
            $this->qr_token = Str::random(32);
            $this->save();
        }

        $qrCode = QrCode::size(200)
            ->format('png')
            ->generate($this->qr_token);

        $path = 'qrcodes/' . $this->id . '.png';
        Storage::disk('public')->put($path, $qrCode);

        $this->qr_code = $path;
        $this->save();

        return $path;
    }

    // Ghi lại lịch sử quét
    public function logScan($action, $userId = null, $oldStatus = null, $newStatus = null, $notes = null)
    {
        $this->qrScans()->create([
            'user_id' => $userId,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'notes' => $notes
        ]);

        $this->last_scanned_at = now();
        $this->save();
    }

    // Cập nhật trạng thái qua QR code
    public function updateStatusViaQr($newStatus, $userId = null, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        $this->logScan('update_status', $userId, $oldStatus, $newStatus, $notes);
    }

    // Kiểm tra token QR code có hợp lệ
    public function validateQrToken($token)
    {
        return $this->qr_token === $token;
    }
}
