<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Tasks::all();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Tasks::findOrFail($id);
        return response()->json($task);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'project_id' => 'required|integer',
            'assigned_to' => 'nullable|integer',
            'position' => 'nullable|integer',
            'due_date' => 'nullable|date',
        ]);

        $task = Tasks::create($validated);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status_id' => 'sometimes|required|integer',
            'project_id' => 'sometimes|required|integer',
            'assigned_to' => 'sometimes|nullable|integer',
            'position' => 'sometimes|nullable|integer',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $task = Tasks::findOrFail($id);
        $task->update($validated);
        return response()->json($task);
    }

    public function delete($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}