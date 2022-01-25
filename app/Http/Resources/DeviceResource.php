<?php

namespace App\Http\Resources;

use App\Models\Update;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'number' => $this->number,
            'type' => $this->type,
            'storage_address' => $this->storage_address,
            'last_value' => Update::query()->orderBy('date', 'desc')->where('device_num', '=', $this->number)->firstOrFail()->value,
        ];
    }
}
