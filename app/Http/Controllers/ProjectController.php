<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statuses;

class ProjectController extends Controller
{
    public function index()
    {
        $statuses = Statuses::all();
        return response()->json($statuses);
    }

    public function show($id)
    {
        $status = Statuses::findOrFail($id);
        return response()->json($status);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'position' => 'nullable|integer',
            'project_id' => 'required|integer',
        ]);

        $status = Statuses::create($validated);
        return response()->json($status, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|nullable|string|max:7',
            'position' => 'sometimes|nullable|integer',
            'project_id' => 'sometimes|required|integer',
        ]);

        $status = Statuses::findOrFail($id);
        $status->update($validated);
        return response()->json($status);
    }

    public function delete($id)
    {
        $status = Statuses::findOrFail($id);
        $status->delete();
        return response()->json(null, 204);
    }
}