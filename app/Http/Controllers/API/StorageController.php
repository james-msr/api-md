<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::all();
        return response()->json($storages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
        ]);

        $newStorage = Storage::query()->create([
            'address' => $request->get('address'),
        ]);
        $newStorage->save();
        return response()->json($newStorage);
    }

    public function show($id)
    {
        $storage = Storage::query()->findOrFail($id);
        $storageItems = [];
        foreach ($storage->devices() as $device) {
            $deviceDetails = [
                'number' => $device->number,
                'type' => $device->type,
                'last_value' => $device->updates()->first()->value,
            ];
            array_push($storageItems, $deviceDetails);
        }
        return response()->json($storageItems);
    }

    public function update(Request $request, $id)
    {
        $storage = Storage::query()->findOrFail($id);

        $storage->address = $request->get('address');
        $storage->save();
        return response()->json($storage);
    }

    public function destroy($id)
    {
        $storage = Storage::query()->findOrFail($id);
        $storage->delete();

        return response()->json($storage);
    }
}
