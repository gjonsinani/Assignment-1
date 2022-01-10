<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        return Thread::all();
    }

//    public function myThreads(Request $request)
//    {
//        $user_id = $request->user()->id;
//        $threads = Thread::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
//        return response()->json([
//            'threads' => $threads
//        ]);
//
//    }

    public function thread($id)
    {
        $thread = Thread::findOrFail($id);
        $comments = $thread->comments;
        return response()->json([
            'thread' => $thread,
            'comments' => $comments
        ]);
    }

    public function threads(Request $request)
    {
        $course_id = $request->course_id;
        $threads = Thread::where('course_id', $course_id)->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'threads' => $threads
        ]);

    }

    public function store(Request $request, $id)
    {
        try {
            $thread = new Thread();
            $thread->user_id = $request->user()->id;
            $thread->course_id = Course::findOrFail($id)->id;
            $thread->title = $request->title;
            $thread->body = $request->body;

            if ($thread->save()) {
                $response = $thread;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thread created successfully!',
                    'data' => $response]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $thread = Thread::findOrFail($id);
            $thread->title = $request->title;
            $thread->body = $request->body;

            if ($thread->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thread updated successfully!']);
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
            $thread = Thread::findOrFail($id);

            if ($thread->delete()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thread deleted successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }
}
