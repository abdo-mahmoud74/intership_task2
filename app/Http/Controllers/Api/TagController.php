<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // all tags
    public function index()
    {
        $tags=TagResource::collection(Tag::all());
        return response()->json([
            'msg'=>'all tags',
            'status'=>200,
            'tags'=>$tags

        ]);
    }
    // single tag
    public function show($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json([
                'msg' => 'Tag not found',
                'status' => 404
            ]);
        }
        return response()->json([
            'msg' => 'Tag retrieved successfully',
            'status' => 200,
            'tag' => new TagResource($tag)
        ]);
    }
    // delete tag
    public function delete($id){
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json([
                'msg' => 'Tag not found to delete',
                'status' => 404
            ]);
        }
        $tag->delete();
        return response()->json([
            'msg' => 'Tag deleted successfully',
            'status' => 200
        ]);
    }
    // create tag
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|unique:tags,name'
        ]);
        $tag=Tag::create([
            'name'=>$request->name
        ]);
        return response()->json([
            'msg'=>'Tag created successfully',
            'status'=>201,
            'tag'=> new TagResource($tag)
        ]);
    }
    // update tag
    public function update(Request $request, $id){
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json([
                'msg' => 'Tag not found to update',
                'status' => 404
            ]);
        }
        $request->validate([
            'name'=>'required|string|unique:tags,name,'.$tag->id
        ]);
        $tag->update([
            'name'=>$request->name
        ]);
        return response()->json([
            'msg'=>'Tag updated successfully',
            'status'=>200,
            'tag'=> new TagResource($tag)
        ]);
    }
}