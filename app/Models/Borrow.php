<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BorrowDetail;

class Borrow extends Model
{
    protected $fillable = [
        'user_id', 
        'staff_id', 
        'borrow_date', 
        'return_date', 
        'actual_return_date',
        'status',
        'reason',
        'note',
        'device_status_before',
        'device_image_before',
        'device_status_after',
        'device_image_after'
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'actual_return_date' => 'datetime'
    ];

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'returned' => 'Đã trả',
            'cancelled' => 'Đã hủy',
            'overdue' => 'Quá hạn',
            default => 'Không xác định'
        };
    }

    public function getDeviceStatusBeforeTextAttribute()
    {
        return match($this->device_status_before) {
            'new' => 'Mới',
            'good' => 'Tốt',
            'normal' => 'Bình thường',
            'damaged' => 'Hư hỏng',
            default => 'Không xác định'
        };
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function details() {
        return $this->hasMany(BorrowDetail::class);
    }
}
