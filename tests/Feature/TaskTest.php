<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_api_returns_successfully(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Task::factory()->count(3)->create([
            'created_by' => $user->id,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'tasks',
        ]);
    }

    public function test_task_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    public function test_task_can_be_created_through_api(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $project = Project::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'project_id' => $project->id,
            'created_by' => $user->id,
            'assignee_id' => null,
            'assignment_status' => 'pending',
            'title' => 'API Test Task',
            'description' => 'This task was created through API testing.',
            'due_date' => '2026-10-30',
            'priority' => 'medium',
            'status' => 'to_do',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'task',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'API Test Task',
        ]);
    }

    public function test_task_can_be_viewed_through_api(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'created_by' => $user->id,
        ]);

        $response = $this->getJson(
            "/api/tasks/{$task->id}"
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'task',
        ]);
    }

    public function test_task_can_be_updated_through_api(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'created_by' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/tasks/{$task->id}",
            [
                'title' => 'Updated API Task',
                'description' => 'Updated task description.',
                'priority' => 'high',
                'status' => 'in_progress',
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated API Task',
        ]);
    }

    public function test_task_status_can_be_updated_through_api(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'created_by' => $user->id,
            'status' => 'to_do',
        ]);

        $response = $this->patchJson(
            "/api/tasks/{$task->id}/status",
            [
                'status' => 'completed',
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_task_can_be_deleted_through_api(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'created_by' => $user->id,
        ]);

        $response = $this->deleteJson(
            "/api/tasks/{$task->id}"
        );

        $response->assertStatus(200);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }
}