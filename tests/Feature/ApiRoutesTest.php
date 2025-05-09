<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register_endpoint_works()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201); // or 200 based on your controller
    }

    public function test_login_endpoint_works()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    public function test_guest_can_view_tasks()
    {
        $response = $this->getJson('/api/guest/tasks');
        $response->assertStatus(200);
    }


    public function test_authenticated_user_can_access_tasks()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/tasks');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_protected_tasks()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    public function test_guest_can_update_guest_task_status()
    {
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'Just a test',
            'status' => 'pending',
        ]);

        $response = $this->putJson('/api/guest/tasks/update-status/' . $task->id, [
            'status' => 'cancelled'
        ]);

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_update_task_status()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'Just a test',
            'status' => 'pending',
            'user_id' => $user->id
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/tasks/update-status/' . $task->id, [
                'status' => 'done'
            ]);

        $response->assertStatus(200);
    }
}
