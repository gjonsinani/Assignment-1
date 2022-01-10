<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function registerInstructor(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if (empty($name) or empty($email) or empty($password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must fill all the fields']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'staus' => 'error',
                'message' => 'You must enter a valid email']);
        }

        if (strlen($password) < 6) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password should be min 6 characters']);
        }

        if (User::where('email', '=', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'UserController already exists with this email']);
        }

        try {
            $instructor = new User();
            $instructor->name = $request->name;
            $instructor->email = $request->email;
            $instructor->password = app('hash')->make($request->password);
            $instructor->role_id = $request->role_id = 2;

            if ($instructor->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Instructor added successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }

}
