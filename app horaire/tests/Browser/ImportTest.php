<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;

class ImportTest extends DuskTestCase {
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }
    /** @test */
    public function test_if_see_disabled_button() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Upload file');
        });
    }

    /** @test */
    public function test_is_disabled_button() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Upload file')
                ->assertButtonDisabled('#uploadconfirm');
        });
    }
}
