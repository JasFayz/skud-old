<?php

namespace Modules\Skud\Http\Controllers\API;

use App\Http\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Skud\Action\Zone\CreateZoneAction;
use Modules\Skud\Action\Zone\DeleteZoneAction;
use Modules\Skud\Action\Zone\UpdateZoneAction;
use Modules\Skud\DTOs\ZoneDTO;
use Modules\Skud\Entities\Zone;
use Modules\Skud\FilterData\ZoneFilterData;
use Modules\Skud\Http\Requests\ZoneRequest;
use Modules\Skud\ViewModels\ZoneViewModel;

class ZoneController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:zone.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:zone.create', ['only' => 'store']);
        $this->middleware('permission:zone.update', ['only' => 'update']);
        $this->middleware('permission:zone.delete', ['only' => 'delete']);
    }

    public function indexWeb()
    {
        return view('admin::zone');
    }

    public function getZones(Request $request)
    {
        $filters = new ZoneFilterData(
            name: $request->query('name'),
            is_attend: $request->query('is_attend'),
            zone_type: $request->query('zone_type'),
            floor_id: $request->query('floor_id'),
            company_id: $request->query('company_id')
        );
        $viewModel = new ZoneViewModel(auth()->user(), $filters);

//        $viewModel->getAll()->map(function ($zone) {
//            $zone->update([
//                'company_id' => $zone->floor?->company_id
//            ]);
//        });

        return $viewModel->getPaginate();
    }

    public function getAll(Request $request)
    {
        $zone_type = $request->query('zone_type');

        return Zone::initQuery()
            ->with('children', 'activeTerminals')
            ->select('id', 'name', 'floor_id', 'zone_type', 'is_calc_attend')
            ->whereType($zone_type)
            ->withFloor()
            ->withTerminals()
            ->orderBy('name')
            ->get();


        return $zones;
    }

    public function getAllByType(Request $request)
    {
        $type = $request->query('type');

        return Zone::query()->with('activeTerminals')->where('zone_type', '=', $type)->get();
    }

    public function store(ZoneRequest $request, CreateZoneAction $createZoneAction)
    {
        $dto = ZoneDTO::fromRequest($request);
        return ResponseHelper::handle($createZoneAction($dto));
    }

    public function update(ZoneRequest $request, Zone $zone, UpdateZoneAction $updateZoneAction)
    {
        $dto = ZoneDTO::fromRequest($request);

        return ResponseHelper::handle($updateZoneAction($zone, $dto));
    }

    public function destroy(Zone $zone, DeleteZoneAction $deleteZoneAction)
    {
        return ResponseHelper::handle($deleteZoneAction($zone));

    }
}
