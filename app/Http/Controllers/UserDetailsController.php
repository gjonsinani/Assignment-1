<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class UserDetailsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $u_details = new UserDetails();
            $u_details->user_id = $request->user()->id;
            $u_details->birthdate = $request->birthdate;
            $u_details->address = $request->address;
            $u_details->phone = $request->phone;

            if ($u_details->save()) {
                $response = $u_details;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Details added successfully!',
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
            $user = $request->user();
            $u_details = UserDetails::findOrFail($id);
            $u_details->birthdate = $request->birthdate;
            $u_details->address = $request->address;
            $u_details->phone = $request->phone;

            if ($u_details->save()) {
                $response = $u_details;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Details updated successfully!',
                    'user' => $user, 'details' => $response]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }

    public function userProfile(Request $request)
    {
        $user = $request->user();
        $details = UserDetails::where('user_id', $user->id)->get();
        return response()->json(['user' => $user, 'details' => $details, 'role' => $user->role->name]);
    }

    public function userDetails(Request $request)
    {
        $user = $request->user();
        $details = UserDetails::where('user_id', $user->id)->get();
        return response()->json([
            'details' => $details
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {

            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);

            if (empty($name) or empty($email) or empty($password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You must fill all the fields']);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You must enter a valid email']);
            }

            if (User::where('email', '=', $email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User already exists with this email']);
            }

            if ($user->save()) {
                $response = $user;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile updated successfully!',
                    'user' => $user,
                    'Profile' => $response]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }

    }
}
