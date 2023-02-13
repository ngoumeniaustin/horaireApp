<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


use App\Models\User;

use Database\Seeders\UserSeeder;
use Database\Seeders\TypeSeeder;

use Database\Seeders\CourseSeeder;




class CourseTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class, CourseSeeder::class, TypeSeeder::class);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)->id);
        });
    }

    public function test_select_Course()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('#typeSelect')
                ->assertSee("Courses")
                ->screenshot('course/test_click_on_select_see_course');
        });
    }



    public function test_click_on_select_see_course()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('#typeSelect')
                ->assertSee("Courses")
                ->screenshot('course/test_click_on_select_see_course');
        });
    }
    public function testSeeAllCourseModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->assertSee('Courses')
                ->screenshot('course/test_see_all_course_modal');
        });
    }


    public function testOpenCourseModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Horaires')
                ->click('select[id="typeSelect"]')
                ->click('option[value="course"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000)
                ->assertSee('Course')
                ->assertValue('input[id="addCourseButton"]', "Add")
                ->screenshot('course/test_open_cours_modal');
        });
    }

    public function test_modalCourse()
    {
        $this->browse(function (Browser $browser) {
            $this->seed([TypeSeeder::class]);


            $browser->visit('/')
                ->click('select[id="typeSelect"]')
                ->click('option[value="course"]')
                ->click('input[id="buttonadd"]')
                ->pause(2000);

            $browser->whenAvailable('#courseModal', function ($modal) {

                $modal->assertSee('Course')
                    ->assertSee('Acronym')
                    ->type('input[id="acro"]', "Dev")
                    ->assertSee('Libelle')
                    ->type('input[id="libelle"]', "Developpement")
                    ->assertSee('Type of course')
                    ->click('#typeCourse')
                    ->pause(2000)
                    ->screenshot('course/ test_modalCourse');
            });
        });
    }
}
