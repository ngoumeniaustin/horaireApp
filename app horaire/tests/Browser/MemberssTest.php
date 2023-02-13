<?php

namespace Tests\Browser\Pages;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;


class MemberssTest extends DuskTestCase
{
    use DatabaseMigrations;
    /** @test */
    public function test_if_see_first_name() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/members/55015')
                ->assertSee('Jeany Austin');
        });
    }
}
