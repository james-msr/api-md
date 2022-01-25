<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageRequest;
use App\Http\Resources\StorageResource;
use App\Models\Device;
use App\Models\Storage;
use App\Models\Update;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        return StorageResource::collection(Storage::all());
    }

    public function store(StorageRequest $request)
    {
        $validated = $request->validated();

        Storage::query()->create([
            'address' => $validated['address'],
        ]);
        return response('Новое помещение успешно добавлено', 201);
    }

    public function show($address)
    {
        return new StorageResource(Storage::query()->with('devices')->findOrFail(['address' => $address]));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($address)
    {
        $storage = Storage::query()->findOrFail(['address' => $address]);
        $storage->delete();

        return response('Помещение удалено успешно', 201);
    }

    public function yearReport($address, $year)
    {
        $from = Carbon::createFromDate($year, 1, 1);
        $to = Carbon::createFromDate($year, 1, 1)->addYear();

        $devices = Device::query()->where(['storage_address' => $address]);

        foreach ($devices->get() as $device) {
            if (Update::query()->where('date', '<', $from)->where(['device_num' => $device->number])->exists()) {
                $device->val = Update::query()->where('date', '<', $from)->find(['device_num' => $device->number])->latest()->get()->value;
            } else {
                $device->val = 0;
            }
        }

        $updates = Update::query()
            ->whereHas('device', function ($q) use ($address) {
                $q->where(['storage_address' => $address]);
            })->where('date', '>=', $from)
                ->where('date', '<', $to)->get()->groupBy('device_type');
        $report = [];
        foreach ($updates as $key => $values) {
            $temp = Carbon::createFromDate($year, 1, 1);
            $from = Carbon::createFromDate($year, 1, 1);
            $monthReport = [];
            while ($temp <= $to) {
                $temp->addMonth();
                $lastUpdates = $values->where('date', '>=', $from)
                            ->where('date', '<', $temp);
                $sum = 0;
                foreach ($devices->get() as $device) {
                    if ($lastUpdates->where(['device_num' => $device->number])->isNotEmpty()) {
                        $newVal = $lastUpdates->find(['device_num' => $device->number])->latest()->get()->value;
                        $device->diff = $newVal - $device->val;
                        $device->val = $newVal;
                    } else {
                        $device->diff = 0;
                    }
                    $sum += $device->diff;
                }
                $monthReport += [$from->monthName => $sum];
                $from->addMonth();
            }
            $report += [$key => $monthReport];
        }
        return $report;
    }
}
