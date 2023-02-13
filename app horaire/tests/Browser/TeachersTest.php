<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Database\Seeders\UserSeeder;
use Database\Seeders\TeacherSeeder;
use App\Models\User;

class TeachersTest extends DuskTestCase
{
 use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class, TeacherSeeder::class);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }

    public function test_click_on_select_see_teachers()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect')
                ->assertSee("Teachers");
        });
    }


    public function test_open_teacher_modal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Teachers"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Acronym')
                ->assertValue('input[id="btnAdd"]', "Submit");
        });
    }

   public function test_add_teacher(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Teachers"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Acronym')
                ->assertValue('input[id="btnAdd"]', "Submit")
                ->type('input[id="acro"]', "SDR")
                ->type('input[id="first"]', "Sébastien")
                ->type('input[id="name"]', "Drobisz")
                ->assertValue('#btnAdd','Submit')
                ->script('$("input[id=btnAdd]").click()');
                $browser->pause(4000)
                ->assertSee('Sébastien')
                ->assertSee('Drobisz')
                ->assertSee('1')
                ->screenshot('teachers/addteacher');
                
        });
    }
    
    public function delete_teacher(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Teachers"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Acronym')
                ->assertValue('input[id="btnAdd"]', "Submit")
                ->type('input[id="acro"]', "SDR")
                ->type('input[id="first"]', "Sébastien")
                ->type('input[id="name"]', "Drobisz")
                ->screenshot('teachers/testDeleteTeacher')
                ->script('$("input[id=btnAdd]").click()');
                $browser->pause(2000)
                ->assertSee('Sébastien')
                ->assertSee('Drobisz')
                ->assertSee('1')
                ->click('button[id="1-delete-btn"]')
                ->assertDontSee("Sébastien")
                ->assertDontSee("Drobisz")
                ->screenshot('teachers/deleteteacher');
        });
    }
}
