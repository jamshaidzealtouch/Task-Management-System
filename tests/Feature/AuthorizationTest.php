<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_organizations(): void
    {
        $response = $this->getJson('/api/organizations');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_organizations(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/organizations');

        $response->assertStatus(200);
    }

    public function test_user_can_create_organization(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/organizations', [
            'name' => 'Test Organization',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('organizations', [
            'name' => 'Test Organization',
            'owner_id' => $user->id,
        ]);
    }

    public function test_user_cannot_access_organizations_without_authentication(): void
    {
        $response = $this->getJson('/api/organizations');

        $response->assertUnauthorized();
    }
}