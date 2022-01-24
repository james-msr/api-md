<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'device_num',
        'device_type',
        'measurement',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_num', 'number');
    }
}
