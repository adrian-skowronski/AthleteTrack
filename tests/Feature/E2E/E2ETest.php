<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Training;
use App\Models\Event;
use App\Models\Category;
use App\Models\Sport;
use Illuminate\Support\Facades\Hash;

class E2ETest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_events_and_trainings()
    {
        // 1. Tworzymy sport (potrzebny do relacji trenera)
        $sport = Sport::create(['name' => 'Lekkoatletyka']);

        // 2. Tworzymy rolę i użytkownika (athlete)
        $athleteRole = Role::create(['name' => 'Athlete']);
        $user = User::create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@test.pl',
            'password' => Hash::make('password'),
            'birthdate' => '2000-01-01',
            'phone' => '600700800',
            'role_id' => $athleteRole->role_id,
            'sport_id' => $sport->sport_id, // przypisanie sportu
            'approved' => 1,
            'is_active' => 1,
        ]);

        // 3. Tworzymy kategorię i wydarzenie
        $category = Category::create([
            'name' => 'Test Category',
            'min_points' => 0,
        ]);

        $event = Event::create([
            'name' => 'Test Event',
            'description' => 'Opis wydarzenia',
            'date' => '2026-01-20',
            'start_hour' => '10:00:00',
            'required_category_id' => $category->category_id,
            'age_from' => 10,
            'age_to' => 18,
            'max_participants' => 50,
        ]);

        // 4. Tworzymy trenera i trening
        $trainerRole = Role::create(['name' => 'Trainer']);
        $trainer = User::create([
            'name' => 'Trainer',
            'surname' => 'One',
            'email' => 'trainer@test.pl',
            'password' => Hash::make('password'),
            'birthdate' => '1990-01-01',
            'phone' => '500600700',
            'role_id' => $trainerRole->role_id,
            'sport_id' => $sport->sport_id, // przypisanie sportu
            'approved' => 1,
            'is_active' => 1,
        ]);

        $training = Training::create([
            'description' => 'Test Training',
            'date' => '2026-02-01',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'trainer_id' => $trainer->user_id, // kluczowe, żeby trainer nie był null
            'max_points' => 10,
        ]);

        // 5. Logowanie użytkownika (athlete)
        $this->actingAs($user);

        // 6. Sprawdzenie publicznych widoków

        // Wydarzenia
        $this->get('/events-view')
            ->assertStatus(200)
            ->assertSee('Test Event');

        // Treningi
        $this->get('/trainings-view')
            ->assertStatus(200)
            ->assertSee('Test Training');
    }
}
