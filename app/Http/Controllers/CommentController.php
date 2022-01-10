<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            $id = Thread::findOrFail($id)->id;
            $comment = new Comment();
            $comment->user_id = $request->user()->id;
            $comment->thread_id = $id;
            $comment->comment = $request->comment;

            if ($comment->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Comment added successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            if ($comment->delete()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Comment deleted successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }
}
