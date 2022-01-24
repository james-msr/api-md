<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public const TYPE = [
        'electricity' => 'Э/Э',
        'cold_water' => 'ХВС',
        'hot_water' => 'ГВС'
    ];

    public const MEASUREMENT = [
        'electricity' => 'Кв*ч',
        'cold_water' => 'куб.м',
        'hot_water' => 'куб.м'
    ];

    protected $fillable = [
        'number',
        'type',
        'storage_address',
    ];

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_address', 'address');
    }

    public function updates()
    {
        return $this->hasMany(Update::class, 'device_num', 'number');
    }
}
