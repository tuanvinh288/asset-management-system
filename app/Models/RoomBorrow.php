<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomBorrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'staff_id',
        'borrow_date',
        'return_date',
        'reason',
        'status'
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime'
    ];

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'returned' => 'Đã trả',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
