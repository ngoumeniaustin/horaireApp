<?php

namespace Tests\Browser;
use App\Models\User;
use Database\Seeders\GroupSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GroupTest extends DuskTestCase
{
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class, GroupSeeder::class);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }

  public function test_click_on_select_see_groupes()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('#typeSelect')
                ->assertSee("Groups")
                ->screenshot('groups/test_click_on_select_see_groupes');
        });
    }
    public function testSeeAllGroupsModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->assertSee('Groups');
        });
    }
  
    
    public function testOpenGroupModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Groups"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Groupe')
                ->assertValue('input[id="btnAdd"]', "Submit");
        });
    }

    public function testAddGroup(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Groups"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Bloc')
                ->assertValue('input[id="btnAdd"]', "Submit")
                ->type('input[id="idGroupe"]', "c111")
                ->type('input[id="bloc"]', "3")
                ->assertValue('#btnAdd','Submit')
                ->script('$("input[id=btnAdd]").click()');
                $browser->pause(4000)
                ->assertSee('c111')
                ->assertSee('3')
                ->screenshot('Groups/addGroup');
                
        });
    }
    
    public function deleteGroup(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Groups"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Bloc')
                ->assertValue('input[id="btnAdd"]', "Submit")
                ->type('input[id="idGroupe"]', "c111")
                ->type('input[id="bloc"]', "3")
                ->script('$("input[id=btnAdd]").click()');
                $browser->pause(2000)
                ->assertSee('c111')
                ->assertSee('3')
                ->click('button[id="c111-delete-btn"]')
                ->assertDontSee("c111")
                ->assertDontSee("3")
                ->screenshot('Groups/deleteGroup');
        });
    }

}
