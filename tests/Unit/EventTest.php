<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Event;

class EventTest extends TestCase
{
    public function test_event_has_name()
    {
        $event = new Event();
        $event->name = "Test Event";

        $this->assertEquals("Test Event", $event->name);
    }
}
