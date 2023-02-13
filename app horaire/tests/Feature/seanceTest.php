<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Teacher;
use App\Models\Group;
use App\Models\ClassRoom;
use App\Models\Course;

use Tests\TestCase;

class seanceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetSeances()
    {
        $response = $this->get('/api/getSeances');
        $response->assertStatus(200);
    }

    public function testGetGroupSeances()
    {
        $this->postJson('/api/group/create', [
            'idGroupe'=> 'E13',
            'bloc'=> 3
        ]);
        $response = $this->get('/api/getGroups/E13');
        $response->assertStatus(200);
    }
    public function test_get_teacher_seances(){
        $teacher = Teacher::factory()->raw();
        $idTeacher = $this->json('post', 'api/createTeacher', $teacher);
        $response = $this->get('/api/getTeachers/{$idTeacher}');
        $response->assertStatus(200);
    }
    public function test_get_classrooms_seances(){
        $classroom = ClassRoom::factory()->raw();
        $idClassroom = $this->postJson('/api/createClassroom', $classroom);
        $response = $this->get('/api/readClassroom/{$idClassroom}');
        $response->assertStatus(200);
    }
    public function test_get_courses_seances(){
        $idCourse = $this->postJson('/api/addCourse', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo'
        ]);
        $response = $this->get('/api/getCourses/{$idCourse}');
        $response->assertStatus(200);
    }

    public function testAddSeance()
    {
        $idGroup = [];
        $idTeacher = [];
        $idClassroom = [];
        $group = Group::factory()->raw();
        array_push($idGroup, $this->json('post', 'api/group/create', $group)->original["idGroup"]);
        $teacher = Teacher::factory()->raw();
        //array_push($idTeacher, $this->json('post', 'api/createTeacher', $teacher)->original["idTeacher"]);
        Teacher::createTeacher("Servais", "Frédéric", "SRV");
        $classroom = ClassRoom::factory()->raw();
        array_push($idClassroom, $this->postJson('/api/createClassroom', $classroom)->original["idClassroom"]);
        $idCourse = $this->postJson('/api/addCourse', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo'
        ]);
        $response = $this->postJson('/api/seance/create', [
            'heureDebut' => '12:00',
            'heureFin' => '14:00',
            'duree' => '02:00',
            'date' => '2021-05-05',
            'idGroupe' => $idGroup,
            'idTeacher'=> [1],
            'idClassRoom'=> $idClassroom,
            'idCourse'=> $idCourse['idCourse']
        ]);
        $response->assertStatus(201);
    }
    public function test_add_multiple_times_seances(){
        for ($i = 0; $i < 10; $i++) {
            $idGroup = [];
            $idTeacher = [];
            $idCourse = [];
            $idClassroom = [];
            $group = Group::factory()->raw();
            array_push($idGroup, $this->json('post', 'api/group/create', $group)->original["idGroup"]);
            $teacher = Teacher::factory()->raw();
            //array_push($idTeacher, $this->json('post', 'api/createTeacher', $teacher)->original["idTeacher"]);
            Teacher::createTeacher("Servais", "Frédéric", "SRV");
            $classroom = ClassRoom::factory()->raw();
            array_push($idClassroom, $this->postJson('/api/createClassroom', $classroom)->original["idClassroom"]);
            $idCourse = $this->postJson('/api/addCourse', [
                'acro' => 'PRJG4',
                'libelle' => 'Projet Logiciel',
                'typeCourse' => 'Labo'
            ]);
            $response = $this->postJson('/api/seance/create', [
                'heureDebut' => '12:00',
                'heureFin' => '14:00',
                'duree' => '02:00',
                'date' => '2021-05-05',
                'idGroupe' => $idGroup,
                'idTeacher'=> [1],
                'idClassRoom'=> $idClassroom,
                'idCourse'=> [1]
            ]);
        }
        $this->assertDatabaseCount('seances', 10);
    }
    public function test_adding_seance_non_existing_group(){
        $teacher = Teacher::factory()->raw();
        $idTeacher = $this->json('post', 'api/createTeacher', $teacher);
        Teacher::createTeacher("Servais", "Frédéric", "SRV");
        $classroom = ClassRoom::factory()->raw();
        $idClassroom = $this->postJson('/api/createClassroom', $classroom);
        $idCourse = $this->postJson('/api/addCourse', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo'
        ]);
        $response = $this->postJson('/api/seance/create', [
            'heureDebut' => '12:00:00',
            'duree' => '01:00:00',
            'date' => '2021-05-05',
            'idGroupe' => "https://youtu.be/dQw4w9WgXcQ",
            'idTeacher'=> [1],
            'idClassroom'=>$idClassroom['idClassroom'],
            'idCourse'=>$idCourse['idCourse']
        ]);
        $response->assertStatus(500);
    }

    public function testRemoveSeance()
    {
        $idGroup = [];
        $idTeacher = [];
        $idClassroom = [];
        $group = Group::factory()->raw();
        array_push($idGroup, $this->json('post', 'api/group/create', $group)->original["idGroup"]);
        $teacher = Teacher::factory()->raw();
        //array_push($idTeacher, $this->json('post', 'api/createTeacher', $teacher)->original["idTeacher"]);
        Teacher::createTeacher("Servais", "Frédéric", "SRV");
        $classroom = ClassRoom::factory()->raw();
        array_push($idClassroom, $this->postJson('/api/createClassroom', $classroom)->original["idClassroom"]);
        $idCourse = $this->postJson('/api/addCourse', [
            'acro' => 'PRJG',
            'libelle' => 'Projet Logiciel',
            'typeCourse' => 'Labo'
        ]);
        $response = $this->postJson('/api/seance/create', [
            'heureDebut' => '12:00',
            'heureFin' => '14:00',
            'duree' => '02:00',
            'date' => '2021-05-05',
            'idGroupe' => $idGroup,
            'idTeacher'=> [1],
            'idClassRoom'=> $idClassroom,
            'idCourse'=> $idCourse['idCourse']
        ]);
        $this->postJson('/api/seance/delete', [
            'idSeance'=>$response['idSeance']
        ])->assertStatus(200);
    }



}
