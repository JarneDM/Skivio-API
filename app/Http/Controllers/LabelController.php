<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Labels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Labels::all();
        return response()->json($labels);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'project_id' => 'nullable|integer',
        ]);

        $label = Labels::create($validated);
        return response()->json($label, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|required|string|max:7',
        ]);

        $label = Labels::findOrFail($id);
        $label->update($validated);
        return response()->json($label);
    }

    public function delete($id)
    {
        $label = Labels::findOrFail($id);
        $label->delete();
        return response()->json(null, 204);
    }
}