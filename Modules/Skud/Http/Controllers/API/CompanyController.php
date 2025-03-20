<?php

namespace Modules\Skud\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Skud\Entities\Company;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:company.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:company.create', ['only' => 'store']);
        $this->middleware('permission:company.update', ['only' => 'update']);
        $this->middleware('permission:company.delete', ['only' => 'delete']);
    }

    public function indexWeb(): Factory|View|Application
    {
        return view('admin::company');
    }

    public function getCompanies(Request $request)
    {
        return Company::paginate();
    }

    public function getAll()
    {
        return Company::get();
    }

    public function store(Request $request): JsonResponse
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'inn' => 'nullable|string',
            'logo' => 'nullable|image'
        ]);

        return response()->json([
            'success' => (bool)Company::create([
                'name' => $validate['name'],
                'inn' => $request->input('inn'),
                'logo' => $request->hasFile('logo') ? $request->file('logo')->store('/uploads/company/logo') : ''
            ])
        ]);
    }

    public function update(Company $company, Request $request): JsonResponse
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'inn' => 'nullable|string',
            'logo' => 'nullable|image'
        ]);

        return response()->json([
            'success' => $company->update([
                'name' => $validate['name'],
                'inn' => $request->input('inn'),
                'logo' => $request->hasFile('logo')
                    ? $request->file('logo')->store('uploads/company/logo')
                    : $company->logo
            ])
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        return response()->json([
            'success' => $company->delete()
        ]);
    }
}
