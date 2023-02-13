<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModalAddSessionTest extends DuskTestCase
{
   use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class); // Seed the database
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }
/*
    /**
     * A Dusk test for the modal.
     *
     * @return void
     */
  public function testSeeAllComponentsOfTheModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->script([
                    'window.$("#createSeanceModal").modal("show");',
                ]);
            $browser->whenAvailable('#createSeanceModal', function ($modal) {
                $modal->assertSee('New session')
                    ->assertSee('Start time:')
                    ->assertSee('End time:')
                    ->assertSee('Course :')
                    ->assertSee('Close')
                    ->assertSee('OK');
            })->screenshot("/modalAddSessionScreen/modal");
        });
    }
}
