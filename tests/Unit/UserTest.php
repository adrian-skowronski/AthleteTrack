<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_user_is_admin()
    {
        $user = new User();
        $user->role_id = 1;

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isCoach());
        $this->assertFalse($user->isAthlete());
    }
}
