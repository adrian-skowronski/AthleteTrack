<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Training;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TrainingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_training_with_trainer()
    {
        $trainerRole = Role::create([
            'name' => 'Trainer'
        ]);

        $trainer = User::create([
            'name' => 'Trainer',
            'surname' => 'One',
            'email' => 'trainer@test.pl',
            'password' => Hash::make('password'),
            'birthdate' => '1990-01-01',
            'phone' => '500600700',
            'role_id' => $trainerRole->role_id,
            'approved' => 1,
            'is_active' => 1,
        ]);

        $training = Training::create([
            'description' => 'Test training',
            'date' => '2026-02-01',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'trainer_id' => $trainer->user_id,
            'max_points' => 10,
        ]);

        $this->assertDatabaseHas('trainings', [
            'description' => 'Test training'
        ]);
    }
}
