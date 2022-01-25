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
        'Э/Э' => 'Кв*ч',
        'ХВС' => 'куб.м',
        'ГВС' => 'куб.м'
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

    public function lastUpdate()
    {
        return $this->updates()->latest()->get();
    }

    public int $val;
    public int $diff;
}
