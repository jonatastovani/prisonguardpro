<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function auth(Request $request)
    {
        $credenciais = $request->validate([
            'usuario' => ['required'],
            'password' => ['required'],
        ], [
            'usuario.required' => 'O campo user é obrigatório!',
            'password.required' => 'O campo password é obrigatório!',
        ]);

        if (Auth::attempt($credenciais, $request->remember)) {
        // if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->senha], $request->remember)) {
            return response()->json([
                'message' => 'Authorized.',
                'status' => 200,
                'data' => [
                    'token' => $request->user()->createToken('token')->plainTextToken,
                ],
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized.',
                'status' => 403,
                'data' => [],
            ], 403);
        }
    }


    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful.',
            'status' => 200,
            'data' => [],
        ], 200);
    }

    public function create() {

        return view('login.create');

    }

}
