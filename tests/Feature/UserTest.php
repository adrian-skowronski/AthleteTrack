<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_user_in_database()
    {
        $role = Role::create([
            'name' => 'Athlete'
        ]);

        $user = User::create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@test.pl',
            'password' => Hash::make('password'),
            'birthdate' => '2000-01-01',
            'phone' => '600700800',
            'role_id' => $role->role_id,
            'approved' => 1,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.pl'
        ]);
    }
}
