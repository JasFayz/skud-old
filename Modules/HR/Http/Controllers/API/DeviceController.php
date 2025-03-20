<?php

namespace Modules\HR\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HR\Entities\Device;

class DeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:device.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:device.create', ['only' => 'store']);
        $this->middleware('permission:device.update', ['only' => 'update']);
        $this->middleware('permission:device.delete', ['only' => 'delete']);
    }


    public function getDevices()
    {
        return Device::query()
            ->owned()
            ->get();
    }

    public function getFreeDevices(Request $request)
    {
        return Device::where('user_id', '=', null)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'type' => ['required', 'string'],
                'trademark' => ['required', 'string'],
                'serial_number' => ['required', 'string'],
                'extra_info' => ['required', 'string'],
                'comment' => ['required', 'string']
            ]
        );
        $device = Device::create([...$data, 'company_id' => auth()->user()->company_id]);
        return ['success' => (bool)$device, 'message' => 'Успешно создан'];
    }

    public function update(Request $request, Device $device)
    {
        $this->authorize('update', $device);

        $data = $request->validate(
            [
                'type' => ['required', 'string'],
                'trademark' => ['required', 'string'],
                'serial_number' => ['required', 'string'],
                'extra_info' => ['required', 'string'],
                'comment' => ['required', 'string']
            ]
        );

        return ['success' => $device->update($data), 'message' => 'Успешно обновлен'];
    }

    public function destroy(Device $device)
    {
        $this->authorize('destroy', $device);

        return ['success' => $device->delete(), 'message' => 'Успешно удален'];
    }
}
