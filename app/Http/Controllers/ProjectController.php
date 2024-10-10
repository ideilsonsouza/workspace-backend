<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
     // Listar todos os projetos
     public function index()
     {
         return Project::all();
     }
 
     // Criar um novo projeto
     public function store(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'description' => 'nullable|string',
             'definers' => 'nullable|json',
         ]);
 
         $project = Project::create($validated);
         return response()->json($project, 201);
     }
 
     // Mostrar um projeto específico
     public function show(Project $project)
     {
         return response()->json($project);
     }
 
     // Atualizar um projeto
     public function update(Request $request, Project $project)
     {
         $validated = $request->validate([
             'name' => 'sometimes|required|string|max:255',
             'description' => 'nullable|string',
             'definers' => 'nullable|json',
         ]);
        
         $project->update($validated);
         return response()->json($project);
     }
 
     // Deletar um projeto
     public function destroy(Project $project)
     {
         $project->delete();
         return response()->json(null, 204);
     }
}
