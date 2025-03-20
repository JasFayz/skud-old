<?php

namespace Modules\HR\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HR\Entities\PositionGrade;

class PositionGradeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:position-grade.create', ['only' => 'store']);
        $this->middleware('permission:position-grade.update', ['only' => 'update']);
        $this->middleware('permission:position-grade.destroy', ['only' => 'destroy']);
    }

    public function getGrades()
    {
        return PositionGrade::query()
            ->owned()
            ->paginate();
    }

    public function getAll()
    {
        return PositionGrade::query()
            ->owned()
            ->get();
    }

    public function store(Request $request)
    {
        $request_data = $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'int']
        ]);
        $grade = PositionGrade::create([...$request_data, 'company_id' => auth()->user()->company_id]);

        return ['success' => (bool)$grade];
    }

    public function update(Request $request, PositionGrade $grade)
    {
//        $this->authorize('update', $grade);

        $request_data = $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'int']
        ]);
        $grade = $grade->update($request_data);

        return ['success' => (bool)$grade];
    }

    public function destroy(PositionGrade $grade)
    {
        $this->authorize('destroy', $grade);

        $deleted = $grade->delete();
        return ['success' => (bool)$deleted];
    }
}
