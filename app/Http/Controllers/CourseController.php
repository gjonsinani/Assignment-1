<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::all();
    }

    public function myCourses(Request $request)
    {
        $user_id = $request->user()->id;
        $courses = Course::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'courses' => $courses
        ]);

    }

    public function myCourse($id)
    {
        $course = Course::findOrFail($id);
        $threads = $course->threads;
        return response()->json([
            'course' => $course,
            'threads' => $threads
        ]);
    }

    public function store(Request $request)
    {
        try {
            $course = new Course();
            $course->user_id = $request->user()->id;
            $course->name = $request->name;
            $course->description = $request->description;

            if ($course->save()) {
                $response = $course;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Course created successfully!',
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
            $course = Course::findOrFail($id);
            $course->title = $request->title;
            $course->body = $request->body;

            if ($course->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Course updated successfully!']);
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
            $course = Course::findOrFail($id);

            if ($course->delete()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Course deleted successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }
}
