<?php

namespace Modules\HR\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HR\Entities\Meeting;


class MeetingController extends Controller
{
    public function index()
    {
        return Meeting::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
        ]);
        $meeting = Meeting::create([
            'name' => $request->name,
            'date' => $request->date,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => (bool)$meeting]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
        ]);

        $meeting = $meeting->update([
            'name' => $request->name,
            'date' => $request->date,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => $meeting]);
    }

    public function destroy(Meeting $meeting)
    {
        $deleted = $meeting->delete();
        return response()->json(['success' => $deleted]);
    }
}
