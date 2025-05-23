<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_item_id',
        'type',
        'start_date',
        'end_date',
        'cost',
        'description',
        'status',
        'result',
        'created_by',
        'next_maintenance_date',
        'maintenance_interval'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2'
    ];

    public function deviceItem()
    {
        return $this->belongsTo(DeviceItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'in_progress' => 'Đang bảo trì',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }

    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'periodic' => 'Bảo trì định kỳ',
            'repair' => 'Sửa chữa',
            default => 'Không xác định'
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePeriodic($query)
    {
        return $query->where('type', 'periodic');
    }

    public function scopeRepair($query)
    {
        return $query->where('type', 'repair');
    }

    public function isOverdue()
    {
        return $this->status === 'pending' && $this->start_date->isPast();
    }

    public function calculateNextMaintenanceDate()
    {
        if ($this->type === 'periodic' && $this->maintenance_interval) {
            $this->next_maintenance_date = Carbon::parse($this->end_date)->addMonths($this->maintenance_interval);
            $this->save();
        }
    }
}
