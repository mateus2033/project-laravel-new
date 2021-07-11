<?php

namespace App\Http\Controllers;

use App\Models\Goals;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class GoalsController extends Controller
{
    /**
     *  Get all user goals
     */
    public function index(Request $request)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken('Authorization'));

        if ($personalAccessToken->tokenable_type === 'App\\Models\\User')
        {
            $user = User::where('id', $personalAccessToken->tokenable_id)->first();

            $goals = Goals::where('user_id', $user->id)->get();

            return response()->json($goals, 200);
        }
    }

    /**
     *  Create a new goal
     */
    public function store(Request $request)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken('Authorization'));

        if ($personalAccessToken->tokenable_type === 'App\\Models\\User')
        {
            $user = User::where('id', $personalAccessToken->tokenable_id)->first();
        }

        $goal = Goals::factory()->create([
            "title" => $request->title,
            "value" => $request->value,
            "value_obtained" => $request->value_obtained,
            "deadline" => $request->deadline,
            "user_id" => $user->id
        ]);

        return response()->json($goal, 201);
    }
}
