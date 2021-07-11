<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_create_new_user(): void
    {
        $response = $this->post("/api/login/store", User::factory()->definition());

        $response->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    "name",
                    "email",
                    "updated_at",
                    "created_at",
                    "id",
                ],
                "token"
            ]);
    }

    public function test_login(): void
    {
        $user = User::factory()->definition();

        User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $response = $this->post("/api/login", [
            "email" => $user["email"],
            "password" => $user["password"]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "token"
            ]);
    }

    public function test_try_login_with_incorrect_password(): void
    {
        $user = User::factory()->definition();

        User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $response = $this->post("/api/login", [
            "email" => $user["email"],
            "password" => $this->faker->password(10, 10)
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                "message"
            ]);
    }

    public function test_try_login_with_non_existent_email(): void
    {
        $user = User::factory()->definition();

        User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $response = $this->post("/api/login", [
            "email" => $this->faker->unique()->safeEmail(),
            "password" => $user["password"]
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                "message"
            ]);
    }

    public function test_try_using_a_registered_email_again(): void
    {
        $user = User::factory()->definition();

        User::factory()->create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"])
        ]);

        $response = $this->post("/api/login/store", [
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => $user["password"]
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                "message"
            ]);
    }
}
