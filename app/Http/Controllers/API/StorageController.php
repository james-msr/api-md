<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageRequest;
use App\Http\Resources\StorageResource;
use App\Models\Storage;
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

        $newStorage = Storage::query()->create([
            'address' => $validated['address'],
        ]);
        return new StorageResource($newStorage);
    }

    public function show($address)
    {
        return new StorageResource(Storage::query()->findOrFail(['address' => $address]));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($address)
    {
        $storage = Storage::query()->findOrFail(['address' => $address]);
        $storage->delete();

        return new StorageResource($storage);
    }
}
