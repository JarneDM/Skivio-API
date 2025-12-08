<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'team_id' => 'nullable|integer',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $project = Project::findOrFail($id);
        $project->update($validated);
        return response()->json($project);
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        $projectTasks = DB::table('tasks')->where('project_id', $id)->delete();
        return response()->json(null, 204);
    }
}