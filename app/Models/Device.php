<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'unit_id',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function deviceItems()
    {
        return $this->hasMany(DeviceItem::class);
    }

    public function maintenances()
    {
        return $this->hasManyThrough(Maintenance::class, DeviceItem::class);
    }
}
