<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_can_be_listed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $organization = Organization::factory()->create([
            'owner_id' => $user->id,
        ]);

        Project::factory()->count(3)->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'projects',
        ]);
    }

    public function test_project_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $organization = Organization::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->postJson('/api/projects', [
            'title' => 'New Test Project',
            'description' => 'This is a test project.',
            'start_date' => '2026-09-24',
            'end_date' => '2026-10-24',
            'status' => 'pending',
            'organization_id' => $organization->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Project created successfully.',
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'New Test Project',
            'organization_id' => $organization->id,
        ]);
    }

    public function test_project_can_be_viewed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $organization = Organization::factory()->create([
            'owner_id' => $user->id,
        ]);

        $project = Project::factory()->create([
            'title' => 'View Test Project',
            'organization_id' => $organization->id,
        ]);

        $response = $this->getJson(
            "/api/projects/{$project->id}"
        );

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Project fetched successfully.',
        ]);
    }

    public function test_project_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $organization = Organization::factory()->create([
            'owner_id' => $user->id,
        ]);

        $project = Project::factory()->create([
            'title' => 'Old Project Title',
            'organization_id' => $organization->id,
        ]);

        $response = $this->putJson(
            "/api/projects/{$project->id}",
            [
                'title' => 'Updated Project Title',
                'description' => 'Updated description.',
                'start_date' => '2026-09-24',
                'end_date' => '2026-11-24',
                'status' => 'in_progress',
                'organization_id' => $organization->id,
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project Title',
            'organization_id' => $organization->id,
        ]);
    }

    public function test_project_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $organization = Organization::factory()->create([
            'owner_id' => $user->id,
        ]);

        $project = Project::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->deleteJson(
            "/api/projects/{$project->id}"
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }
}