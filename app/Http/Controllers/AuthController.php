<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkRegistration(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:users,cpf'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefone' => ['required', 'string', 'size:11'],
        ]);

        return response()->json([
            'message' => 'Dados válidos. Pode continuar para a criação da senha.',
            'data' => $validated,
        ], 200);
    }
}