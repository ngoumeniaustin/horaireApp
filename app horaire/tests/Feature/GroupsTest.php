<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Group;

class GroupsTest extends TestCase
{
    use RefreshDatabase;
    public function test_insert_single_group()
    {
        $group = Group::factory()->raw();
        $this->json('post', 'api/group/create', $group);
        $this->assertDatabaseHas('groups', $group);
    }
    public function test_select_single_group()
    {
        $group = Group::factory()->raw();
        $this->json('post', 'api/group/create', $group);

        $result = $this->getJson('api/group/' . $group['idGroupe']);
        $this->assertEquals($result[0], $group);
    }

    public function test_select_all_groups()
    {
        for ($i = 0; $i < 50; $i++) {
            $group = Group::factory()->raw();
            $this->json('post', 'api/group/create', $group);
        }
        $result = $this->getJson('api/getGroups');
        for ($i = 0; $i < 50; $i++) {
            $this->assertDatabaseHas('groups', $result[$i]);
        }
        $this->assertDatabaseCount('groups', 50);
    }

    public function test_delete_single_group()
    {
        $group = Group::factory()->raw();
        $this->json('post', 'api/group/create', $group);

        $this->json('post', 'api/group/delete', $group);
        $this->assertDatabaseMissing('groups', $group);
    }

    public function test_http_response_status_when_insert_group_ok()
    {
        $group = Group::factory()->raw();
        $response = $this->json('post', 'api/group/create', $group);
        $response->assertStatus(201);
    }

    public function test_http_response_status_when_delete_group_ok()
    {
        $group = Group::factory()->raw();
        $this->json('post', 'api/group/create', $group);

        $response = $this->json('post', 'api/group/delete', $group);
        $response->assertStatus(204);
    }

    public function test_http_response_status_when_select_single_group_ok()
    {
        $group = Group::factory()->raw();
        $this->json('post', 'api/group/create', $group);

        $response = $this->getJson('api/group/' . $group['idGroupe']);
        $response->assertStatus(200);
    }

    public function test_http_response_status_when_select_all_groups_ok()
    {
        for ($i = 0; $i < 50; $i++) {
            $group = Group::factory()->raw();
            $this->json('post', 'api/group/create', $group);
        }

        $response = $this->getJson('api/getGroups');
        $response->assertStatus(200);
    }


    public function test_http_response_status_when_insert_group_ko()
    {
        $responseMissingBloc = $this->json('post', 'api/group/create', ['idGroupe' => 'missingBloc']);
        $responseMissingBloc->assertStatus(500);

        $responseMissingIdGroupe = $this->json('post', 'api/group/create', ['bloc' => 'missingIdGroupe']);
        $responseMissingIdGroupe->assertStatus(500);

        $responseMissingAll = $this->json('post', 'api/group/create', []);
        $responseMissingAll->assertStatus(500);
    }

    public function test_http_response_status_when_delete_group_ko()
    {
        $responseMissingIdGroupe = $this->json('post', 'api/group/delete', ['idGroupe' => 'missingIdGroupe']);
        $responseMissingIdGroupe->assertStatus(500);

        $responseIdGroupeEmpty = $this->json('post', 'api/group/delete', []);
        $responseIdGroupeEmpty->assertStatus(500);
    }
}
