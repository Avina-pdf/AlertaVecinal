<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => $data['password'], // se hashea por cast 'hashed'
            'role' => 'user',
            'is_active' => true,
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user' => ['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'role'=>$user->role],
            'token'=> $token,
        ], 201);
    }

    public function login(Request $r)
    {
        $data = $r->validate(['email'=>'required|email','password'=>'required|string']);

        $user = User::where('email',$data['email'])->first();
        if (!$user || !\Hash::check($data['password'], $user->password)) {
            return response()->json(['message'=>'Credenciales inválidas'], 422);
        }
        if (property_exists($user,'is_active') && !$user->is_active) {
            return response()->json(['message'=>'Cuenta inactiva'], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;
        return ['token'=>$token];
    }

    public function me(Request $r) { return $r->user(); }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return ['message'=>'ok'];
    }
}
