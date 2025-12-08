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
    foreach ($tasks as $task) {
        $labelIds = DB::table('task_label')->where('task_id', $task->id)->get();
        $labels = DB::table('labels')->whereIn('id', $labelIds->pluck('label_id'))->get();
        $task->labels = $labels;
    }

    return response()->json($tasks);
}

    public function show($id)
    {
        $task = Tasks::findOrFail($id);
        $labelIds = DB::table('task_label')->where('task_id', $task->id)->get();
        $labels = DB::table('labels')->whereIn('id', $labelIds->pluck('label_id'))->get();
        $task->labels = $labels;
        return response()->json($task);
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
        $position = $validated['position'] ?? null;
        unset($validated['position']);

        $task = DB::transaction(function () use ($validated, $position) {
            // place at end if no explicit position provided
            $nextPos = (Tasks::where('status_id', $validated['status_id'])->max('position') ?? -1) + 1;
            $validated['position'] = $position ?? $nextPos;
            return Tasks::create($validated);
        });

        if (!empty($labels)) {
            $task->labels()->sync($labels);
        }

        return response()->json($task, 201);
    }

    public function addLabel(Request $request, $id)
    {
        $validated = $request->validate([
            'labels' => 'required|array|min:1',
            'labels.*' => 'integer',
        ]);

        $task = Tasks::findOrFail($id);

        // attach new labels, keep existing
        $ids = $validated['labels'];
        $task->labels()->syncWithoutDetaching($ids);

        return response()
            ->json(["message" => "labels added successfully", "labels" => $task->labels()->get()])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function fethchLabels($id)
    {
        $labels = DB::table('task_label')->where('task_id', $id)->get();
        // echo $labels;
        return response()
            ->json($labels)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Methods', '*');
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

        $updatedTask = DB::transaction(function () use ($validated, $task) {
            $originalStatus = $task->status_id;
            $newStatus = $validated['status_id'] ?? $originalStatus;
            $newPosition = array_key_exists('position', $validated) ? $validated['position'] : null;

            // update basic fields
            $task->fill($validated);

            // If status changes and no explicit position, append at end of new column
            if ($newStatus !== $originalStatus && $newPosition === null) {
                $newPosition = (Tasks::where('status_id', $newStatus)->max('position') ?? -1) + 1;
                $task->position = $newPosition;
            }

            $task->save();

            // Renumber old status column if changed
            if ($newStatus !== $originalStatus) {
                $this->renumberPositions($originalStatus);
            }

            // Renumber new status column, inserting this task at requested position
            $this->renumberPositions($newStatus, $task->id, $newPosition);

            return $task->fresh()->load('labels');
        });

        return response()->json($updatedTask);
    }

    private function renumberPositions(int $statusId, ?int $pinTaskId = null, ?int $pinIndex = null): void
    {
        $tasks = Tasks::where('status_id', $statusId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        // If we need to pin a task at a specific index, reorder accordingly
        if ($pinTaskId !== null && $pinIndex !== null) {
            $tasks = $tasks->filter(fn ($t) => $t->id !== $pinTaskId)->values();
            $pinIndex = max(0, min($pinIndex, $tasks->count()));
            $pinned = Tasks::find($pinTaskId);
            if ($pinned) {
                $tasks->splice($pinIndex, 0, [$pinned]);
            }
        }

        foreach ($tasks as $index => $t) {
            if ($t->position !== $index) {
                $t->update(['position' => $index]);
            }
        }
    }

    public function deleteTaskLabel($taskId, $labelId)
    {
        DB::table('task_label')
            ->where('task_id', $taskId)
            ->where('label_id', $labelId)
            ->delete();

        return response()
            ->json(null, 204)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function delete($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}