<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Training;

class TrainingTest extends TestCase
{
    public function test_training_description()
    {
        $training = new Training();
        $training->description = "Morning Run";

        $this->assertEquals("Morning Run", $training->description);
    }
}
