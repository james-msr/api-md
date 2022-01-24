<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Models\Update;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'type' => 'required',
            'storage_address' => 'required'
        ]);

        $newDevice = Device::query()->create([
            'number' => $request->get('number'),
            'type' => $request->get('type')
        ]);
        return response()->json($newDevice);
    }

    public function show($id)
    {
        $device = Device::query()->findOrFail($id);
        return response()->json($device);
    }

    public function update(Request $request, $id)
    {
        $device = Device::query()->findOrFail($id);

        $request->validate([
            'value' => 'required'
        ]);
        $lastUpdate = $device->updates()->first();
        if ($lastUpdate->value > $request->get('value') || $request->get('value') - $lastUpdate->value <= 99) {
            return abort('302', 'Введенные показания не могут быть меньше предыдущих и не могут быть больше, чем на 99');
        }
        $device->number = $request->get('number');
        $device->type = $request->get('type');
        $device->save();
        $newUpdate = Update::query()->create([
            'value' => $request->get('value'),
            'device_num' => $device->number,
            'device_type' => $device->type,
        ]);
        $newUpdate->measurement = Device::MEASUREMENT[$device->type];
        $newUpdate->save();
        return response()->json($device);
    }

    public function destroy($id)
    {
        $device = Device::query()->findOrFail($id);
        $device->delete();

        return response()->json($device);
    }
}
