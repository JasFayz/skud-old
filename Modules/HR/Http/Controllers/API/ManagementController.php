<?php

namespace Modules\HR\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('management::index');
    }

}
