<?php

namespace Tests\Feature;

use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Tests\TestCase;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

//Done !
class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();
        Course::factory()->create([
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo',
        ]);
        Course::factory()->create([
            'acro' => 'DON',
            'libelle' => 'Persistance de Données',
            'typeCourse' => 'Auditoire',
        ]);
    }

    public function test_api_getCourses(){
        $response = $this->getJson('/api/courses');
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'acro' => 'PRJG',
                'acro' => 'DON',
            ]);
    }

    public function test_api_getCourse(){
    $response = $this->getJson('/api/course/1');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'acro' => 'PRJG',
                'libelle' => 'Projet Logiciel',
                'typeCourse' => 'Labo',
            ]);
    }   

    public function test_api_getCourses_when_empty()
    {
        $this->postJson('/api/deleteCourse', ['idCourse' => 1]);
        $this->postJson('api/deleteCourse', ['idCourse' => 2]);
        $this->assertDatabaseCount('course', 0);
        $response = $this->getJson('/api/courses');
        $this->assertFalse(filter_var($response->getContent(), FILTER_VALIDATE_BOOLEAN));
        $response->assertStatus(200);   
    }

    public function test_api_getCourse_when_Id_not_existing()
    {
        $this->assertDatabaseCount('course', 2);
        $response = $this->getJson('/api/course/20');
        $this->assertFalse(filter_var($response->getContent(), FILTER_VALIDATE_BOOLEAN));
        $response->assertStatus(500);
    }

    public function test_api_deleteCourse()
    {
        $response = $this->postJson('api/deleteCourse', ['idCourse' => 1]);
        $this->assertDatabaseMissing('course', [
            'acro' => 'PRJG',
        ]);
        $this->assertDatabaseCount('course', 1);
    }

    public function test_api_deleteCourse_when_Id_not_existing()
    {
        $this->assertDatabaseCount('course', 2);
        $response = $this->postJson('api/deleteCourse', ['idCourse' => 13]);
        $this->assertDatabaseCount('course', 2);
        $response->assertStatus(500);
    }


    public function test_api_addCourse(){
        $response = $this->postJson('/api/addCourse',
         ['acro' => 'ERPGL', 'libelle' => 'Progiciel de gestion integré', 'typeCourse' => 'Labo']);
        $response->assertStatus(201);
        $this->assertDatabaseCount('course', 3);
        $this->assertDatabaseHas('course', [
            'idCourse' => 3,
            'acro' => 'ERPGL',
            'libelle' => 'Progiciel de gestion integré',
            'typeCourse' => 'Labo',
        ]);
    }

    public function test_api_addCourse_when_error()
    {
        $response = $this->postJson('/api/addCourse', ['acro' => 'ERPGL']);
        $this->assertDatabaseCount('course', 2);
        $response->assertStatus(500);
    }

    public function test_api_updateCourse()
    {
        $this->postJson('/api/updateCourse', ['idCourse' => 1, 'acro' => "ATLG",
                                     'libelle' => "Atelier logiciel", 'typeCourse' => "Labo"]);
        $this->assertDatabaseCount('course', 2);
        $this->assertDatabaseHas('course', [
            'acro' => 'ATLG',
            'libelle' => 'Atelier logiciel',
            'typeCourse' => 'Labo',
        ]);
        $this->assertDatabaseMissing('course', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo',
        ]);
    }

    public function test_api_updateCourse_error_when_no_arguments()
    {
        $response = $this->postJson('/api/updateCourse');
        $this->assertDatabaseCount('course', 2);
        $this->assertDatabaseHas('course', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo',
        ]);
        $response->assertStatus(500);
    }

    public function test_api_updateCourse_error_when_not_all_arguments()
    {
        $response = $this->postJson('/api/updateCourse', ['idCourse' => 1, 'acro' => 'LOL']);
        $this->assertDatabaseCount('course', 2);
        $this->assertDatabaseHas('course', [
            'idCourse' => 1,
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo',
        ]);
        $response->assertStatus(500);
    }
}
