<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //!Student Login
    public function studentLogin(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'fcm_token' => 'nullable'
        ]);

        $student = Student::where('username', $request->username)->first();
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'data' => null
            ]);
        }
        if (!Hash::check($request->password, $student->password)) { //*In hash the 2nd parameter is the one in the database
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'data' => null
            ]);
        }
        //$student->tokens()->delete();
        $token = $student->createToken('student');
        $plainTextToken = $token->plainTextToken;
        $fcmToken = $request->fcm_token;
        $token->accessToken->fcm_token = $fcmToken;
        $token->accessToken->save();
        $student->save();
        $response = [
            'status' => 'success',
            'message' => 'Login successfully',
            'data' => [
                'user' => new StudentResource($student),
                'access_token' => $plainTextToken,
            ],
            'fcm_token' => $fcmToken
        ];
        return response()->json($response);
    }

    //!Admin Login
    public function adminLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'data' => null
            ]);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'data' => null
            ]);
        }
        $user->tokens()->delete();
        $token = $user->createToken('admin')->plainTextToken;
        $response = [
            'status' => 'success',
            'message' => 'Login successfully',
            'data' => [
                'user' => new UserResource($user),
                'access_token' => $token
            ]
        ];
        return response()->json($response);
    }
}
