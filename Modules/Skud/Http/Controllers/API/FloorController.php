<?php

namespace Modules\Skud\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Floor;

class FloorController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:floor.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:floor.create', ['only' => 'store']);
        $this->middleware('permission:floor.update', ['only' => 'update']);
        $this->middleware('permission:floor.delete', ['only' => 'delete']);
    }

    public function indexWeb()
    {
        return view('admin::floor');
    }

    public function getFloors()
    {
        return Floor::query()->with('company')->orderBy('label')->paginate();
    }

    public function getByCompany(Company $company)
    {
        return Floor::query()->where('company_id', '=', auth()->user()->company_id)->get();
    }

    public function getAll()
    {
        return Floor::query()->with('company')->orderBy('label')->get();
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:5|unique:floors',
            'company_id' => 'nullable|numeric'
        ]);

        $floor = Floor::create($validate);

        return response()->json([
            'success' => (bool)$floor,
//            'data' => $floor
        ]);
    }

    public function update(Floor $floor, Request $request): JsonResponse
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:5|unique:floors,label,' . $request->floor->id,
            'company_id' => 'nullable|numeric'
        ]);


        return response()->json([
            'success' => $floor->update($validate),
        ]);
    }

    public function destroy(Floor $floor): JsonResponse
    {
        return response()->json([
            'success' => $floor->delete()
        ]);
    }
}
