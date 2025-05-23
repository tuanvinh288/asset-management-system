<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'description'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function borrows()
    {
        return $this->hasMany(RoomBorrow::class);
    }

    public function getStatusAttribute()
    {
        $hasActiveBorrow = $this->borrows()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return $hasActiveBorrow ? 'in_use' : 'available';
    }
}
