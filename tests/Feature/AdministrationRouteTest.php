<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdministrationRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the administration.index route exists and is accessible
     */
    public function test_administration_index_route_exists()
    {
        // Create an admin user
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        // Test that the route exists
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('administration.index'),
            'The administration.index route should exist'
        );

        // Test that the route resolves to the correct URL
        $url = route('administration.index');
        $this->assertEquals('/administration', $url);
    }

    /**
     * Test that the administration.index route is accessible by admin users
     */
    public function test_administration_index_accessible_by_admin()
    {
        // Create an admin user
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        // Test accessing the route as an authenticated admin
        $response = $this->actingAs($user)->get(route('administration.index'));

        // Should not throw RouteNotFoundException and should be successful
        $response->assertStatus(200);
    }
}