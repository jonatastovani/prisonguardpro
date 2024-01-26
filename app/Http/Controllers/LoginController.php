<?php

namespace App\Http\Controllers;

use App\Common\CommonsFunctions;
use App\Common\RestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function auth(Request $request)
    {
        // Regras de validação
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        
        $attributesNames = [
            'username' => 'Usuário',
            'password' => 'Senha',
        ];

        CommonsFunctions::validacaoRequest($request, $rules,$attributesNames);

        $credenciais = [
            'username' => $request->username,
            'password' => $request->password
        ];
        // $credenciais = $request->validate([
        //     'username' => ['required'],
        //     'password' => ['required'],
        // ], [
        //     'username.required' => 'O campo Usuário é obrigatório!',
        //     'password.required' => 'O campo Senha é obrigatório!',
        // ]);


        if (Auth::attempt($credenciais, $request->remember)) {

            $response = RestResponse::createSuccessResponse(['redirect' => route('site.index')],200);
            return response()->json($response->toArray(), $response->getStatusCode());

            // return response()->json([
            //     'message' => 'Authorized.',
            //     'status' => 200,
            //     'data' => [
            //         // 'token' => $request->user()->createToken('token')->plainTextToken,
            //         'redirect' => route('site.index'), // URL para onde redirecionar
            //     ],
            // ], 200);
        } else {
            $response = RestResponse::createErrorResponse(200,'Login ou senha incorretos');
            return response()->json($response->toArray(), $response->getStatusCode());

            // return response()->json([
            //     'message' => 'Unauthorized.',
            //     'status' => 403,
            //     'data' => [],
            // ], 403);
        }
    }
    
    public function authToken(Request $request)
    {
        $credenciais = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'O campo Usuário é obrigatório!',
            'password.required' => 'O campo Senha é obrigatório!',
        ]);

        if (Auth::attempt($credenciais, $request->remember)) {
            return response()->json([
                'message' => 'Authorized.',
                'status' => 200,
                'data' => [
                    'token' => $request->user()->createToken('token')->plainTextToken,
                    // 'redirect' => route('site.index'), // URL para onde redirecionar
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

    public function logout(Request $request) {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect(route('site.index'));

    }

    public function create() {

        return view('login.create');

    }

}
