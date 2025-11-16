<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(10)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/users');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    public function test_admin_can_view_create_user_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/admin/users', [
                'full_name' => 'New User',
                'email' => 'newuser@example.com',
                'phone' => '+1234567890',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'country' => 'USA',
                'city' => 'New York',
                'address' => '123 Main St',
                'postal_code' => '10001',
                'passport_number' => 'AB123456',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'user'
            ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'full_name' => 'New User'
        ]);
    }

    public function test_admin_can_view_user_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user');
    }

    public function test_admin_can_view_edit_user_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/users/{$user->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['full_name' => 'Old Name']);

        $response = $this->actingAs($admin)
            ->patch("/admin/users/{$user->id}", [
                'full_name' => 'Updated Name',
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'country' => $user->country,
                'city' => $user->city,
                'address' => $user->address,
                'postal_code' => $user->postal_code,
                'passport_number' => $user->passport_number,
                'role' => $user->role
            ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'full_name' => 'Updated Name'
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_admin_cannot_delete_themselves()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->delete("/admin/users/{$admin->id}");

        $this->assertDatabaseHas('users', [
            'id' => $admin->id
        ]);
    }

    public function test_regular_user_cannot_manage_users()
    {
        $user = User::factory()->create(['role' => 'user']);
        $targetUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/users');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function test_user_creation_validates_required_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/admin/users', []);

        $response->assertSessionHasErrors(['full_name', 'email', 'password']);
    }

    public function test_user_creation_validates_unique_email()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin)
            ->post('/admin/users', [
                'full_name' => 'Test User',
                'email' => 'existing@example.com',
                'phone' => '+1234567890',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'country' => 'USA',
                'city' => 'New York',
                'address' => '123 Main St',
                'postal_code' => '10001',
                'passport_number' => 'AB123456',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'user'
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_admin_can_update_user_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)
            ->patch("/admin/users/{$user->id}", [
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'country' => $user->country,
                'city' => $user->city,
                'address' => $user->address,
                'postal_code' => $user->postal_code,
                'passport_number' => $user->passport_number,
                'role' => 'manager'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'manager'
        ]);
    }
}
