<?php

namespace Modules\HR\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Position;


class PositionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:position.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:position.create', ['only' => 'store']);
        $this->middleware('permission:position.update', ['only' => 'update']);
        $this->middleware('permission:position.delete', ['only' => 'delete']);
    }

    public function getPositions()
    {
        return Position::query()->with(['grade'])
            ->owned()
            ->latest()->paginate();
    }

    public function getAll()
    {
        return Position::query()->with(['grade'])
            ->owned()
            ->latest()->get();
    }

    public function store(Request $request)
    {
        $request_data = $request->validate([
            'name' => 'required',
//            'division_id' => 'required',
            'grade_id' => 'nullable'
        ]);

        $position = Position::create([...$request_data, 'company_id' => \Auth::user()->company_id]);

        return ['success' => (bool)$position, 'message' => 'Успешно создан'];
    }

    public function update(Request $request, Position $position)
    {
        $this->authorize('update', $position);

        $request_data = $request->validate([
            'name' => 'required',
            'division_id' => 'required',
            'grade_id' => 'required'
        ]);
        $position->update($request_data);
        return ['success' => (bool)$position, 'message' => 'Успешно обновлен'];
    }

    public function destroy(Position $position)
    {
        $deleted = $position->delete();
        return ['success' => (bool)$deleted, 'message' => 'Успешно удален'];
    }

//    public function getByPositionId(Division $division)
//    {
//        return $division->positions;
//    }

    public function getByDepartmentId(Department $department)
    {
        return $department->positions;
    }
}
