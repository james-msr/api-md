<?php

namespace App\Http\Resources;

use App\Models\Device;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'address' => $this->address,
            'devices' => DeviceResource::collection(Device::query()->where('storage_address', '=', $this->address)->get())
        ];
    }
}
