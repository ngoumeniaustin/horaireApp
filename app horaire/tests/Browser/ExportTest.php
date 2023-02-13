<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;

class ExportTest extends DuskTestCase
{
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
    public function exportBtn() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Export')
                ->press('Export');
            $browser->whenAvailable('.modal-title', function ($modal) {
                $modal->assertSee('Export session')
                ->press('#closeBtnExport');
            });
        });
    }

    /** @test */
    public function exportIconBtn() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Export')
                ->press('Export');
            $browser->whenAvailable('.modal-title', function ($modal) {
                $modal->assertSee('Export session')
                ->press('#closeIconExport');
            });
        });
    }
}
