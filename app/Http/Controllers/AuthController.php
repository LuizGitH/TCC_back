<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:users,cpf'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefone' => ['required', 'string', 'size:11'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
        ]);

        $user = User::create([
            'name' => $validated['nome'],
            'cpf' => $validated['cpf'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Conta criada com sucesso!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['senha'], $user->password)) {
            return response()->json([
                'message' => 'E-mail ou senha incorretos.'
            ], 401);
        }

        if ($user->role !== 'responsavel') {
            return response()->json([
                'message' => 'Esta conta não possui acesso ao login de responsável.'
            ], 403);
        }

        $token = $user->createToken('responsavel-token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 200);
    }
}
