<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\ClassRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassRoomTest extends TestCase
{
    use RefreshDatabase;


    public function test_classroom_create_ok()

    {
        $classroom = ClassRoom::factory()->raw();
        $response = $this->json('post', 'api/createClassroom', $classroom);
        $response->assertStatus(201);
        $classroom =   [
            'idClassRoom' => 'E13',
            'classType' => 'Théorie',
            'places' => null,
            'examPlaces' => null
        ];
        $response = $this->json('post', 'api/createClassroom', $classroom);
        $response->assertStatus(201);
    }
    public function test_classroom_create_ko()

    {
        $classroom =   [

            'classType' => 'Théorie',
            'places' => 50,
            'examPlaces' => 25
        ];
        $response = $this->post('api/createClassroom', $classroom);
        $response->assertSessionHasErrors(['idClassRoom']);

        $classroom =   [

            'idClassRoom' => 'E13',
            'places' => 50,
            'examPlaces' => 25
        ];
        $response = $this->post('api/createClassroom', $classroom);
        $response->assertSessionHasErrors(['classType']);

        $classroom =   [
            'idClassRoom' => 'E1345645655555555555555555555555555555555555555555555555555555555555555555555555555555555555555555',
            'classType' => 'Théorie'
        ];
        $response = $this->post('api/createClassroom', $classroom);
        $response->assertSessionHasErrors(['idClassRoom']);
    }
    public function test_classroom_delete_ok()
    {
        $classroom = ClassRoom::factory()->raw();
        $this->withoutExceptionHandling()->json('post', 'api/createClassroom', $classroom);

        $response = $this->withoutExceptionHandling()->json('post', 'api/deleteClassroom', $classroom);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('classroom', $classroom);
    }

    public function test_classroom_delete_ko()
    {
        $classroom = ClassRoom::factory()->raw();


        $response = $this->withoutExceptionHandling()->json('post', 'api/deleteClassroom', $classroom);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('classroom', $classroom);
    }
    public function test_classroom_update_ok()
    {
        $classroom = ClassRoom::factory()->raw();
        $this->withoutExceptionHandling()->json('post', 'api/createClassroom', $classroom);
        $classroomUpdate = [
            'idClassRoom' => $classroom['idClassRoom'],
            'classType' => 'Théorie',
            'places' => $classroom['places'],
            'examPlaces' => $classroom['examPlaces']
        ];

        $response = $this->withoutExceptionHandling()->json('post', 'api/updateClassroom', $classroomUpdate);
        $response->assertStatus(200);
        $this->assertDatabaseHas('classroom', $classroomUpdate);
    }
    public function test_classroom_read_ok()
    {
        $classroom = ClassRoom::factory()->raw();
        $this->withoutExceptionHandling()->json('post', 'api/createClassroom', $classroom);

        $result = $this->withoutExceptionHandling()->getJson('api/readClassroom/' . $classroom['idClassRoom']);
        $this->assertEquals($result[0], $classroom);
        $result->assertStatus((200));
    }
    public function test_classroom_readAll_ok()
    {

        for ($i = 0; $i < 10; $i++) {
            $classroom = ClassRoom::factory()->raw();
            $this->withoutExceptionHandling()->json('post', 'api/createClassroom', $classroom);
        }


        $result = $this->withoutExceptionHandling()->getJson('api/readAllClassroom');
        for ($i = 0; $i < 10; $i++) {
            $this->assertDatabaseHas('classroom', $result[$i]);
        }
        $this->assertDatabaseCount('classroom', 10);
    }
}
