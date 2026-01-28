<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class ProjectController extends Controller
{
    public function index()
    {
        $projects=ProjectResource::collection(Project::all());
        $data=[
            'msg'=> 'Projects retrieved successfully',
            'status' => 200,
            'projects' => $projects
        ];
        return response()->json($data);
    }
    public function show($id)
    {
        $Showproject= Project::find($id);
        if(!$Showproject){
            $data=[
                'msg'=> 'Project not found',
                'status' => 404,
                'project' => null
            ];
            return response()->json($data);
        }else{
          $data=[
            'msg'=> ' show one Project retrieved successfully',
            'status' => 200,
            'project' => new ProjectResource($Showproject)
        ];
        return response()->json($data);
    }
    }
    public function delete($id)
    {
        $project = Project::find($id);
        if (!$project) {
            $data = [
                'msg' => 'Project not found to delete',
                'status' => 404,
            ];
            return response()->json($data);
        } else {
            $project->delete();
            $data = [
                'msg' => ' Project deleted successfully',
                'status' => 200,
            ];
            return response()->json($data);
        }
    }
    public function store(Request $request){
          $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
           
        ]);
        if($validator->fails()) {
            $data=[
                'msg'=> 'Validation failed',
                'status' => 422,
                'errors' => $validator->errors()
            ];
            return response()->json($data);
        }
        $projectNew=Project::create([
            'name'=>$request->name,
        ]);
        $data=[
            'msg'=> ' Project created successfully',
            'status' => 200,
            'project' => new ProjectResource($projectNew)
        ];
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) {
            $data = [
                'msg' => 'Project not found to update',
                'status' => 404,
            ];
            return response()->json($data);
        } else {
            $validator = FacadesValidator::make($request->all(), [
                'name' => 'required|string|max:255',

            ]);
            if ($validator->fails()) {
                $data = [
                    'msg' => 'Validation failed',
                    'status' => 422,
                    'errors' => $validator->errors()
                ];
                return response()->json($data);
            } else {
                $project->update([
                    'name' => $request->name,
                ]);
                $data = [
                    'msg' => ' Project updated successfully',
                    'status' => 200,
                    'project' => new ProjectResource($project)
                ];
                return response()->json($data);
            }
        }
    }
}

