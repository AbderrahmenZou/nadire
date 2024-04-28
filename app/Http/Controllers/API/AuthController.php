<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    private function returnCodeAccordingToInput($validator) {
        // قم بتعيين رمز الخطأ هنا بناءً على الأخطاء في المدخلات
        return 400; // مثلاً، يمكنك استخدام 400 للأخطاء في المدخلات غير الصحيحة
    }

    private function returnValidationError($code, $validator) {
        return response()->json([
            'code' => $code,
            'errors' => $validator->errors(),
        ], $code);
    }

    public function login(Request $request){
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $credentials = request(['email', 'password']);
            $token = Auth::guard("api")->attempt($credentials);
            
            if(!$token){
                return response()->json(['msg' => 'errore']);
            }
            $user = Auth::guard("api")->user();
            $user->token = $token;
            return response()->json(['msg'=>$user]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        response()->json(['msg' => 'not found']);
    }

    public function register(Request $request){
     
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user){
            return $this->login($request);
        }
        return response()->json(['msg' => 'errore']);
    }

    public function logout(Request $request){
        try {
            JWTAuth::invalidate($request->token);
            return response()->json(['msg' => 'Success logout']);
        } catch(\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function refresh(Request $request){
        try {
            $token = JWTAuth::refresh($request->token);
            return response()->json(['token' => $token]);
        } catch(\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    
}
