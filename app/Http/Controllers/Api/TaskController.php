<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        //N+1 Problem is not handled here 
        $tasks=TaskResource::collection(Task::all());

        return response()->json([
            'msg'=>'Tasks retrieved successfully',
            'status'=>200,
            'tasks'=>$tasks
        ]);
    }
    public function show($id){
        $task= Task::find($id);
        if($task){
            $data=[
                "msg"=>"Task retrieved successfully",
                "status"=>200,
                "task"=>new TaskResource($task)
            ];
            return response()->json($data);
        }else{
            $data=[
                "msg"=>"Task not found",
                "status"=>404,
                "task"=>null
            ];
            return response()->json($data);
        }
    }
    public function delete($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            $data = [
                'msg' => 'Task deleted successfully',
                'status' => 200
            ];
            return response()->json($data);
        } else {
            $data = [
                'msg' => 'Task not found to delete',
                'status' => 404
            ];
            return response()->json($data);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:draft,done,in_progress,archived',
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',
            'tags' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Validation faile/8472d',
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }
        $newTask=Task::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'status'=>$request->status,
            'project_id'=>$request->project_id,
            'created_by'  => auth()->id(),
            'user_id'=>$request->user_id
        ]);
         // tags logic
        if ($request->has('tags')) {
             $newTask->tags()->sync($request->tags);
        }

        return response()->json([
            'msg'=>'Task created successfully',
            'status'=>200,
            'task'=>$newTask
        ]);
    }
    public function update(Request $request,$id){
        $task=Task::find($id);
        if(!$task){
            return response()->json([
                'msg'=>'Task not found to update',
                'status'=>404
            ]);
        }
        //  authorization in controller must be the creator or assigned user
        if (
            auth()->id() !== $task->created_by &&
            auth()->id() !== $task->user_id
        ) {
            return response()->json(['msg' => 'Not authorized must be the creator or assigned user',
              'status'=>403
            ]);
        }
        // archived rule
        if ($task->status === 'archived') {

            // مسموح فقط لو بيرجعها in_progress
            if (
                !($request->has('status') && $request->status === 'in_progress')
            ) {
                return response()->json([
                    'msg' => 'Archived task cannot be edited unless returned to in_progress',
                    'status' => 403
                ]);
            }
        }

        //  done rule if the status is being changed to done, ensure at least one comment exists

        if (
            $request->status === 'done' &&
            $task->comments()->count() === 0
        ) {
            return response()->json(['msg' => 'Task needs at least one comment',
              'status'=>400
            ]);
        }


         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|string|in:draft,done,in_progress,archived',
            'project_id' => 'sometimes|integer|exists:projects,id',
            'user_id' => 'sometimes|integer|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Validation failed',
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }
         if ($request->filled('tags')) {
            $task->tags()->sync($request->tags);
        }
        $task->update($request->all());
        return response()->json([
            'msg'=>'Task updated successfully',
            'status'=>200,
            'task' => new TaskResource($task->load('tags'))
        ]);
    

        }
}