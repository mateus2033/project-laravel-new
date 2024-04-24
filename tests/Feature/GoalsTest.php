<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\Goals;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GoalsTest extends TestCase
{
    public function test_create_new_goal()
    {
        $user = User::factory()->definition();

        $user = User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $response = $this->withHeaders([
            'Authorization' => UserController::generateToken($user)
        ])
            ->post("/api/goals/store", Goals::factory()->definition());

        $response->assertStatus(201)
            ->assertJsonStructure([
                "title",
                "value",
                "value_obtained",
                "deadline",
                "user_id",
                "updated_at",
                "created_at",
                "id",
            ]);
    }

    public function test_get_all_user_goals()
    {
        $user = User::factory()->definition();

        $user = User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        Goals::factory(4)->create([
            "user_id" => $user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => UserController::generateToken($user)
        ])
            ->get("/api/goals");

        $response->assertStatus(200)
            ->assertJsonCount(4);
    }

    public function test_update_new_goal()
    {
        $user = User::factory()->definition();

        $user = User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $goal = Goals::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => UserController::generateToken($user)
        ])
            ->put("/api/goals/update", Goals::factory()->definition());

        $response->assertStatus(204);
    }
}
