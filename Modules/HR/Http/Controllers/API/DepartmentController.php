<?php

namespace Modules\HR\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Division;


class DepartmentController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('permission:department.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:department.create', ['only' => 'store']);
        $this->middleware('permission:department.update', ['only' => 'update']);
        $this->middleware('permission:department.delete', ['only' => 'delete']);
    }

    public function indexWeb()
    {
        return view('management::department');
    }

    public function getDepartments(Request $request)
    {
        $departments = Department::scoped(['company_id' => auth()->user()->company_id])
            ->with(['children', 'positions', 'descendants'])
            ->whereIsRoot()
            ->latest();
//        $departments->fixTree();

//        dd($departments->get());
        return $departments->get();
    }

    public function getAll(Request $request)
    {
        return Department::query()
            ->owned($request->query('company_id'))
            ->get();
    }

    public function getDepartmentTree(Request $request)
    {
        return Department::query()
            ->owned($request->query('company_id'))
            ->get()->toTree();
    }

    public function store(Request $request)
    {
        Department::scoped(['company_id' => auth()->user()->company_id])->fixTree();
        try {
            $requestData = $request->post();
            $department = Department::create([...$requestData, 'company_id' => \Auth::user()?->company_id ?? 1]);

            if ($requestData['parent_id']) {
                $department->parent_id = $requestData['parent_id'];
                $department->save();
            }
            return ['success' => (bool)$department, 'message' => 'Успешно создан'];
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);
        Department::scoped(['company_id' => auth()->user()->company_id])->fixTree();

        $requestPost = $request->post();

        try {
            $status = $department->update([...$requestPost, 'company_id' => \Auth::user()?->company_id ?? 1]);

            $parent = Department::find($requestPost['parent_id']);
            if ($parent) {
                $department->parent()->associate($parent)->save();
            } else {
                $department->saveAsRoot();
            }
            return ['success' => (bool)$status, 'message' => 'Успешно обновлен'];
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }

    }

    public function destroy(Department $department)
    {
        $this->authorize('destroy', $department);

        $deleted = $department->delete();
        return ['success' => (bool)$deleted, 'message' => 'Успешно удален'];
    }

    public function getByDivisionId(Division $division)
    {
        return $division->department;
    }

    public function getByCompanyId()
    {
        return Department::where('company_id', '=', auth()->user()?->company_id)->get();
    }

    public function attachPosition(Department $department, Request $request)
    {
        $requestData = $request->validate(['positions' => 'required', 'array']);

        $department->positions()->sync($requestData['positions']);

        return ['success' => true, 'message' => 'Успешно прикреплен'];
    }

    public function changeNode(Request $request)
    {
        Department::fixOwnedTree();
        $requestValid = $request->validate([
            'parent_id' => 'required',
            'changedNode' => 'required'
        ]);

        $parent = Department::findOrFail($requestValid['parent_id']);
        $changedNode = Department::findOrFail($requestValid['changedNode']);
//        dd($parent);
        try {
            $parent->appendNode($changedNode);
            Department::fixOwnedTree();
            return ['success' => true, 'message' => 'Успешно изменен'];
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

}
