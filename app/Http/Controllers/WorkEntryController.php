<?php

namespace App\Http\Controllers;

use App\Models\WorkEntry;
use Illuminate\Http\Request;

class WorkEntryController extends Controller
{
    // Listar todas as entradas de trabalho
    public function index()
    {
        return WorkEntry::with(['user', 'project'])->get();
    }

    // Criar uma nova entrada de trabalho
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'duration' => 'nullable|integer',
            'comments' => 'nullable|json',
            'summary' => 'nullable|string',
        ]);

        $workEntry = WorkEntry::create($validated);
        return response()->json($workEntry, 201);
    }

    // Mostrar uma entrada de trabalho específica
    public function show($id)
    {
        $workEntry = WorkEntry::with(['user', 'project'])->findOrFail($id);
        return response()->json($workEntry);
    }

    // Atualizar uma entrada de trabalho
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_time' => 'sometimes|required|date',
            'end_time' => 'nullable|date|after:start_time',
            'duration' => 'nullable|integer',
            'comments' => 'nullable|json',
            'summary' => 'nullable|string',
        ]);

        $workEntry = WorkEntry::findOrFail($id);
        $workEntry->update($validated);
        return response()->json($workEntry);
    }

    // Deletar uma entrada de trabalho
    public function destroy($id)
    {
        $workEntry = WorkEntry::findOrFail($id);
        $workEntry->delete();
        return response()->json(null, 204);
    }

    // Adicionar comentário
    public function addComment(Request $request, $id)
    {
        $validated = $request->validate(['comment' => 'required|string']);

        $workEntry = WorkEntry::findOrFail($id);
        $comments = json_decode($workEntry->comments, true) ?: [];
        $comments[] = $validated['comment'];
        $workEntry->comments = json_encode($comments);
        $workEntry->save();

        return response()->json($workEntry);
    }

    // Remover comentário
    public function removeComment(Request $request, $id)
    {
        $validated = $request->validate(['index' => 'required|integer']);

        $workEntry = WorkEntry::findOrFail($id);
        $comments = json_decode($workEntry->comments, true) ?: [];

        if (isset($comments[$validated['index']])) {
            unset($comments[$validated['index']]);
            $workEntry->comments = json_encode(array_values($comments));
            $workEntry->save();
        }

        return response()->json($workEntry);
    }
}
