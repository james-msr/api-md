<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'storage_address', 'address');
    }
}
