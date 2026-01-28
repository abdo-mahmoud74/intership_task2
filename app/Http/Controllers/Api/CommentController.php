<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments=CommentResource::collection(Comment::all());
        return response()->json([
            'msg'=>'Comments retrieved successfully',
            'status'=>200,
            'comments'=>$comments
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'task_id'=>'required|integer|exists:tasks,id',
            'body'=>'required|string'
        ]);
        $comment=Comment::create([
            'body'=>$request->body,
            'task_id'=>$request->task_id,
            'user_id'=>auth()->id()
        ]);
        return response()->json([
            'msg'=>'Comment created successfully',
            'status'=>201,
            'comment'=>new CommentResource($comment)
        ]);
    }
    public function delete($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            // authorization: only the comment owner can delete
            if (auth()->id() !== $comment->user_id) {
                return response()->json([
                    'msg' => 'Not authorized to delete this comment',
                    'status' => 403
                ]);
            }
            $comment->delete();
            return response()->json([
                'msg' => 'Comment deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'msg' => 'Comment not found to delete',
                'status' => 404
            ]);
        }
    }
}
