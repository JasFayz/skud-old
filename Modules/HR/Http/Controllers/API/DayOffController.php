<?php

namespace Modules\HR\Http\Controllers\API;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Entities\DayOff;
use Modules\HR\Entities\DayOffType;

class DayOffController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user.indexWeb', ['only' => 'indexWeb']);
    }

    public function indexWeb()
    {
        return view('management::day-off');
    }

    public function getDayOffTypes()
    {
        return DayOffType::getTypes();
    }

    public function getDayOffs(): LengthAwarePaginator
    {
        return DayOff::query()
            ->when(auth()->user()->company_id, function (Builder $builder) {
                $builder->where('company_id', auth()->user()->company_id);
            })
            ->with('user')->paginate();
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'user_id' => 'required|numeric',
                'type' => 'required|numeric',
                'dateRange' => 'required|array|min:2',
                'comment' => 'nullable|string'
            ]);

            $validate['from'] = array_shift($validate['dateRange']);
            $validate['to'] = end($validate['dateRange']);

            $dayOff = DayOff::create($validate);

            return ['success' => !!$dayOff, 'message' => 'Успешно создан'];

        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function update(DayOff $dayOff, Request $request)
    {
        $validate = $request->validate([
            'user_id' => 'required|numeric',
            'type' => 'required|numeric',
            'dateRange' => 'required|array|min:2',
            'comment' => 'nullable|string'
        ]);
        $validate['from'] = array_shift($validate['dateRange']);
        $validate['to'] = end($validate['dateRange']);

        return ['success' => $dayOff->update($validate), 'message' => 'Успешно обновлен'];
    }

    public function destroy(DayOff $dayOff)
    {
        return ['success' => $dayOff->delete(), 'message' => 'Успешно удален'];
    }

}
