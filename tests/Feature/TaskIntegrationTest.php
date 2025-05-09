<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response['token'] ?? null;

        return ['user' => $user, 'token' => $token];
    }

    public function test_user_can_register_and_login()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201);
    }

    public function test_authenticated_user_can_create_task()
    {
        $auth = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/tasks', [
                'title' => 'New Task',
                'description' => 'Test task description',
                'status' => 'pending'
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Task']);
    }

    public function test_authenticated_user_can_update_task_status()
    {
        $auth = $this->authenticate();

        $task = Task::factory()->create([
            'user_id' => $auth['user']->id,
            'title' => 'Initial',
            'description' => 'Just a test',
            'status' => 'pending'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->putJson('/api/tasks/update-status/' . $task->id, [
                'status' => 'done'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done'
        ]);
    }

    public function test_authenticated_user_can_list_tasks()
    {
        $auth = $this->authenticate();

        Task::factory()->count(3)->create([
            'user_id' => $auth['user']->id,
            'title' => 'Initial',
            'description' => 'Just a test',
            'status' => 'pending'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data'); // if using pagination or ResourceCollection
    }

    public function test_guest_can_list_guest_tasks()
    {
        Task::factory()->count(2)->create([
            'title' => 'Public Task',
            'description' => 'Just a test',
            'status' => 'pending'
        ]);

        $response = $this->getJson('/api/guest/tasks');

        $response->assertStatus(200);
    }

    public function test_guest_can_update_guest_task_status()
    {
        $task = Task::factory()->create([
            'title' => 'Guest Task',
             'description' => 'Just a test',
            'status' => 'pending'
        ]);

        $response = $this->putJson('/api/guest/tasks/update-status/' . $task->id, [
            'status' => 'done'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'done']);
    }
}
