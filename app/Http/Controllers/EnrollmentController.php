<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $id)
    {
        try {
                $enrollment = new Enrollment();
                $enrollment->course_id = Course::findOrFail($id)->id;
                $enrollment->user_id = $request->user()->id;
                $enrollment->save();
                return response()->json([
                    'status'=> 'success',
                    'message'=>'Enrolled successfully!'
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }


}
