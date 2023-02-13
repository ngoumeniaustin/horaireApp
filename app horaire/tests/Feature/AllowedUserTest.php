<?php

namespace Tests\Feature;

use App\Models\AllowedUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class AllowedUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_allowed_user_insert_ok()
    {
        $user = AllowedUser::factory()->raw();
        $this->json('post', 'api/insert/allowedUser', $user);
        $this->assertDatabaseHas('AllowedUser', $user);
    }

    public function test_is_allowed_loggin_ok()
    {
        $user = AllowedUser::factory()->raw();
        $this->json('post', 'api/insert/allowedUser', $user);
        $this->assertTrue(AllowedUser::isAllowed($user['email']));
    }

    public function test_is_allowed_loggin_ko()
    {
        $user = AllowedUser::factory()->raw();
        $this->assertFalse(AllowedUser::isAllowed($user['email']));
    }
}
