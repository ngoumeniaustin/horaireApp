<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Teacher;
class TeacherTest extends TestCase
{       

   use RefreshDatabase;
    /**
     * 
     */
    public function createTeacherTest(){
        $response = $this->get('/api/teacher/create');
        $response->assertStatus(201);

    }
 /** --------------------------------------------- */
    /**
     * Add Teacher
     */
    public function test_insert_single_teacher()
    {
        $teacher = Teacher::factory()->raw();
        $this->json('post', 'api/teacher/create', $teacher);
        $this->assertDatabaseHas('teacher', $teacher);
    }
    /**
     * 
     */
    public function test_select_single_teacher()
    {
        $teacher = Teacher::factory()->raw();
        $this->json('post', 'api/teacher/create', $teacher);

        $result = $this->getJson('api/getTeacher/' . $teacher['idTeacher']);
        $this->assertEquals($result[0], $teacher);
    }
    /* Unique acro...
     
     
    public function test_select_all_teacher()
    {
        for ($i = 0; $i < 10; $i++) {
            $teacher = Teacher::factory()->raw();
            $this->json('post', 'api/createTeacher', $teacher);
        }
        $result = $this->getJson('api/getTeachers');
        for ($i = 0; $i < 10; $i++) {
            $this->assertDatabaseHas('teacher', $result[$i]);
        }
        $this->assertDatabaseCount('teacher', 10);
    }
    */

    /**
     * 
     */
    public function test_delete_single_teacher()
    {
        $teacher = Teacher::factory()->raw();
        $this->json('post', 'api/teacher/create', $teacher);

        $this->json('post', 'api/teacher/delete', $teacher);
        $this->assertDatabaseMissing('teacher', $teacher);
    }
    /**
     * 
     */
    public function test_http_response_status_when_insert_teacher_ok()
    {
        $teacher = Teacher::factory()->raw();
        $response = $this->json('post', 'api/teacher/create', $teacher);
        $response->assertStatus(201);
    }
    /**
     * 
     */
    public function test_status_delete_teacher_ok()
    {
        $teacher = Teacher::factory()->raw();
        $this->json('post', 'api/teacher/create', $teacher);
        $response = $this->json('post', 'api/teacher/delete', $teacher);
        $response->assertStatus(204);
    }
    /**
     * 
     */
    public function test_status_select_single_teacher_ok()
    {
        $teacher = Teacher::factory()->raw();
        $this->json('post', 'api/teacher/create', $teacher);

        $response = $this->getJson('api/getTeachers/' . $teacher['idTeacher']);
        $response->assertStatus(200);
    }   

    /**
     * 
     */
    public function test_status_when_select_all_teacher_ok()
    {
        for ($i = 0; $i < 10; $i++) {
            $teacher = Teacher::factory()->raw();
            $this->json('post', 'api/teacher/create', $teacher);
        }

        $response = $this->getJson('api/getTeachers');
        $response->assertStatus(200);
    }
    /**
     * 
     */
    public function test_when_insert_teacher_ko()
    {
        $responseMissTeacher = $this->json('post', 'api/teacher/create', ['idTeacher' => 'missingIdTeacher']);
        $responseMissTeacher->assertStatus(500);

        $response = $this->json('post', 'api/teacher/create', ['firstname' => 'missingFirstname']);
        $response->assertStatus(500);
        $response= $this->json('post', 'api/teacher/create', ['lastname' => 'missingLastname']);
        $response->assertStatus(500);
        $response = $this->json('post', 'api/teacher/create', ['acronym' => 'missingAcronym']);
        $response->assertStatus(500);

        $responseMissingAll = $this->json('post', 'api/teacher/create', []);
        $responseMissingAll->assertStatus(500);
    }
  
    /**
     * 
     */
    public function test_status_when_delete_teacher_ko()
    {   /*
        $missingTeacher = $this->json('post', 'api/deleteTeacher',  ['idTeacher' => 'missingIdTeacher']);
        $missingTeacher->assertStatus(500);
        */
        $response = $this->json('post', 'api/teacher/delete', []);
        $response->assertStatus(500);
    }


}
