<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if (empty($name) or empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['staus' => 'error', 'message' => 'You must enter a valid email']);
        }

        if (strlen($password) < 6) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 6 characters']);
        }

        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'UserController already exists with this email']);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);

            if ($user->save()) {
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) or empty($password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must fill all fields']);
        }
        $credentials = request([
            'email',
            'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid login details'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
