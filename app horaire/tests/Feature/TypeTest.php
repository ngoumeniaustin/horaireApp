<?php

namespace Tests\Feature;

use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_testStore()
    {
        $type = Type::factory()->raw();

        $this->json('post', 'api/types', $type);

        $this->assertDatabaseHas('types', $type);
    }



    public function test_testIndex()
    {

        for ($i = 0; $i < 10; $i++) {
            $type = Type::factory()->raw();

            $this->json('post', 'api/types', $type);
        }

        $response = $this->get('/api/types');

        $response->assertStatus(200);


        $result = $this->json('get', 'api/types');

        $this->assertDatabaseCount('types', 10);
        for ($i = 0; $i < 10; $i++) {
            $this->assertDatabaseHas('types', $result[$i]);
        }
    }


    public function test_show_types()
    {

        $type = null;
        for ($i = 0; $i < 10; $i++) {
            $type = Type::factory()->raw();

            $this->json('post', 'api/types', $type);
        }

        $res = $this->json('get', 'api/types/10');

        $res->assertStatus(200);

        $this->assertEquals($type['name'], $res['name']);
    }

    public function test_show_types_Cannotshow()
    {


        $res = $this->json('get', 'api/types/10');

        $res->assertStatus(500);
    }



    public function test_testDelete()
    {

        $type = null;
        for ($i = 0; $i < 10; $i++) {
            $type = Type::factory()->raw();

            $this->json('post', 'api/types', $type);
        }

        $datas = $this->json('get', 'api/types');

        $resp = $this->json('delete', route('types.destroy', ['name' => $datas[2]['name']]));

        $this->assertDatabaseMissing('types', $datas[2]);
        $resp->assertStatus(200);
    }

    public function test_testDelete_CannotDelete()
    {

        $resp = $this->json('delete', route('types.destroy', ['id' => 100]));

        $this->assertDatabaseCount('types', 0);
        $resp->assertStatus(500);
    }
}
