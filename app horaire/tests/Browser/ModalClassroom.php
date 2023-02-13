<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\ClassroomSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModalClassroom extends DuskTestCase
{
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class, ClassroomSeeder::class); // Seed the database
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_click_on_select_see_classroom()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('#typeSelect')
                ->assertSee("Classroom");
        });

    }

    public function test_open_class_roomModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Classroom"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Classroom')
                ->assertSee('#')
                ->assertSee('Places')
                ->assertSee('Exams')
                ->assertSee('Type')
                ->assertSee('Icone')
                ->assertSee('Name')
                ->assertSee('Number of places')
                ->assertSee('Number of places for exams')
                ->assertSee('Type of classroom')
                ->assertValue('input[id="AddClassroomButton"]', "Add")
                ->assertValue('input[id="delete-button-classroom"]', "Delete")
                ->assertValue('input[id="addLocalType"]', "Add local type");
        });
    }

    public function test_add_classroom(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="Classroom"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Name')
                ->assertValue('input[id="addLocalType"]', "Add")
                ->screenshot('classrooms/addClassroom')
                ->script('$("button[id=addLocalType]").click()');
               $browser->assertSee('Type room')
               ->assertSee('Name')
               ->type('input[id="nameC"]', "labo");
               
               $browser->visit('/')->assertSee('Horaires')
               ->click('select[id="typeSelect"]')
               ->click('option[value="Classroom"]')
               ->click('input[id="buttonadd"]')
               ->pause(2000)
               ->type('input[id="idClassroom"]', "003")
                ->type('input[id="places"]', "300")
                ->type('input[id="examPlaces"]', "150")
                ->click('select[id="classtype"]')
                ->click('option[value="labo"]')
                ->screenshot('teachers/ADDCLASSROOM');

                
                
                
              /*  ->type('input[id="acro"]', "SDR")
                ->type('input[id="first"]', "Sébastien")
                ->type('input[id="name"]', "Drobisz")
                ->assertValue('#btnAdd','Submit')
                ->script('$("input[id=btnAdd]").click()');
                $browser->pause(4000)
                ->assertSee('Sébastien')
                ->assertSee('Drobisz')
                ->assertSee('1')
                ->screenshot('teachers/addteacher');*/
               
        });
    }
    
    /*public function delete_teacher(){
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
*/

}
