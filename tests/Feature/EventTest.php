<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\Category;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_event()
    {
        $category = Category::create([
            'name' => 'Test category',
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

        $this->assertDatabaseHas('events', [
            'name' => 'Test Event'
        ]);
    }
}
