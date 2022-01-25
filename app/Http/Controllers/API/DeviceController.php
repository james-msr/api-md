<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\Update;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        return DeviceResource::collection(Device::all());
    }

    public function store(DeviceRequest $request)
    {
        $validated = $request->validated();

        Device::query()->create([
            'number' => $validated['number'],
            'type' => $validated['type'],
            'storage_address' => $validated['storage_address']
        ]);
        return 201;
    }

    public function show($id)
    {

    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();

        $device = Device::query()->findOrFail(['number' => $validated['number'], 'type' => $validated['type']]);

        $lastUpdate = $device->updates()->latest();
        if ($lastUpdate->date > $validated['data']) {
            return abort('302', 'Введенная дата не может быть после даты последнего обновления.');
        }
        if ($lastUpdate->value > $validated['value'] || $validated['value'] - $lastUpdate->value >= 99) {
            return abort('302', 'Введенные показания не могут быть меньше предыдущих и не могут быть больше, чем на 99.');
        }

        Update::query()->create([
            'value' => $validated['value'],
            'device_num' => $device->number,
            'device_type' => $device->type,
            'measurement' => Device::MEASUREMENT[$device->type],
            'date' => $validated['date']
        ]);
        return new DeviceResource($device);
    }

    public function destroy($number)
    {

    }
}
