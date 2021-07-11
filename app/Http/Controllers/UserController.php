<?php

namespace App\Http\Controllers;

use App\Mail\NewLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens;

    public static function generateToken(User $user): string
    {
        $token = explode('|', $user->createToken($user->email)->plainTextToken)[1];

        return "Bearer $token";
    }

    /**
     *  Login
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (empty($user))
        {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 401);
        }

        if (Hash::check($request->password, $user->password))
        {
            Mail::to($user->email)->send(new NewLogin);

            return response()->json([
                'token' => $this->generateToken($user)
            ], 200);
        }

        return response()->json([
            'message' => 'Senha incorreta'
        ], 401);
    }

    /**
     *  Create a new user
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (empty($user))
        {
            $user = User::factory()->create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

            return response()->json([
                'user' => $user,
                'token' => $this->generateToken($user)
            ], 201);
        }

        return response()->json([
            'message' => 'Este usuário já existe'
        ], 400);
    }
}
