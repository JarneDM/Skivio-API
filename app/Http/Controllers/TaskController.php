<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $projectId = $request->query('project_id') ?? $request->input('project_id');

    
    $authUser = $request->user();
    $userId = $authUser ? $authUser->id : ($request->query('user_id') ?? $request->input('user_id'));

    $query = Tasks::query();

    if ($projectId) {
        $query->where('project_id', $projectId);
    }

    if ($userId) {
        $query->where('assigned_to', $userId);
    }

    $tasks = $query->get();

    return response()->json($tasks);
}

    // public function getByProject($projectId)
    // {
    //     $tasks = Tasks::where('project_id', $projectId)->get();
    //     return response()->json($tasks);
    // }

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
            'labels' => 'nullable|array',
        ]);

        $labels = $validated['labels'] ?? [];
        unset($validated['labels']);

        $task = Tasks::create($validated);

        if (!empty($labels)) {
            $task->labels()->sync($labels);
        }

        return response()->json($task, 201);
    }

    public function addLabel(Request $request, $id)
    {
        $validated = $request->validate([
            'label_id' => 'required|integer',
        ]);

        $task = Tasks::findOrFail($id);

        DB::table('task_label')->insert([
            'task_id' => $task->id,
            'label_id' => $validated['label_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json("label add successfully", 201);
    }

    public function fethchLabels($id)
    {
        $labels = DB::table('task_label')->where('task_id', $id)->get();
        // echo $labels;
        return response()->json($labels);
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

    public function deleteTaskLabel($taskId, $labelId)
    {
        DB::table('task_label')
            ->where('task_id', $taskId)
            ->where('label_id', $labelId)
            ->delete();

        return response()->json(null, 204);
    }

    public function delete($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}