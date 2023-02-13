<?php

namespace Tests\Feature;

use App\Models\Grouping;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_grouping_success_http()
    {
        $response = $this->get('/api/getGroupings');
        $response->assertStatus(200);
    }

    public function test_get_grouping_id_http()
    {
        $grouping = Grouping::factory()->raw();
        $this->post('/api/grouping/insert', $grouping);
        $response = $this->get('/api/getGrouping/' . $grouping['idRegroupement']);
        $response->assertStatus(200);
    }

    public function test_get_group_not_in_grouping_id_http()
    {
        $grouping = Grouping::factory()->raw();
        $grouping2 = Grouping::factory()->raw();
        $this->post('/api/grouping/insert', $grouping);
        $this->post('/api/grouping/insert', $grouping2);
        $response = $this->get('/api/getGroupsNotInGrouping/' . $grouping['idRegroupement']);
        $response->assertStatus(200);
    }


    public function test_grouping_insert_http()
    {
        $grouping = Grouping::factory()->raw();
        $response = $this->post('/api/grouping/insert', $grouping);
        $response->assertStatus(201);
    }

    public function test_grouping_insert_failure_http()
    {

        $responseMissingGroup = $this->post('/api/grouping/insert', ['idRegroupement' => 'missing']);
        $responseMissingGroup->assertStatus(500);

        $responseMissingId = $this->post('/api/grouping/insert', ['idGroup' => 'missing']);
        $responseMissingId->assertStatus(500);

        $responseMissingAll = $this->post('/api/grouping/insert', []);
        $responseMissingAll->assertStatus(500);
    }

    public function test_delete_grouping_http()
    {
        $grouping = Grouping::factory()->raw();
        $this->post('/api/grouping/insert', $grouping);
        $response = $this->post('/api/grouping/delete', $grouping);
        $response->assertStatus(204);
    }

    public function test_delete_group_in_grouping_http()
    {
        $grouping = Grouping::factory()->raw();
        $this->post('/api/grouping/insert', $grouping);
        $response = $this->post('/api/grouping/deleteGroupFromGrouping', [
            'idRegroupement' => $grouping['idRegroupement'],
            'idGroup' => $grouping['idGroup'][0]
        ]);
        $response->assertStatus(204);
    }

    public function test_delete_grouping_failure()
    {

        $responseMissingAll = $this->post('/api/grouping/delete', []);
        $responseMissingAll->assertStatus(500);
    }

    public function test_delete_group_in_grouping_failure()
    {
        $response = $this->post('/api/grouping/deleteGroupFromGrouping');
        $response->assertStatus(500);
    }

    public function test_get_groups_not_in_grouping()
    {
        $response = $this->get('/api/getGroupsNotInGrouping/E');
        $response->assertStatus(200);
    }

    public function test_get_all_grouping()
    {
        for ($i = 0; $i < 50; $i++) {
            $grouping = Grouping::factory()->raw();
            $this->json('post', 'api/grouping/insert', $grouping);
        }
        $result = $this->getJson('api/getGroupings');
        for ($i = 0; $i < 50; $i++) {
            $this->assertDatabaseHas('grouping', $result[$i]);
        }
        $this->assertDatabaseCount('grouping', 50);
    }

    public function test_get_single_grouping()
    {
        $grouping = Grouping::factory()->raw();
        $this->post('api/grouping/insert', $grouping);
        $result = $this->getJson('api/getGrouping/' . $grouping['idRegroupement']);

        $result->assertJsonFragment([
            'idRegroupement' => $grouping['idRegroupement'],
            'idGroup' => $grouping['idGroup'][0],
        ]);
    }

    public function test_insert_single_grouping()
    {
        $grouping = Grouping::factory()->raw();
        $this->post('api/grouping/insert', $grouping);
        $groupingNoArray = ['idRegroupement' => $grouping['idRegroupement'], 'idGroup' => $grouping['idGroup'][0]];
        $this->assertDatabaseHas('grouping', $groupingNoArray);
    }
    public function test_delete_single_group()
    {

        $grouping = Grouping::factory()->raw(
            [
                'idRegroupement' => 'Y',
                'idGroup' => 'Y102',
            ]
        );
        $this->post('api/grouping/insert', $grouping);
        $this->get('api/grouping/delete', $grouping);
        $this->assertDatabaseMissing('grouping', $grouping);
    }

    // public function test_get_group_not_in_grouping_id_()
    // {
    //     $grouping = Grouping::factory()->raw();
    //     $grouping2 = Grouping::factory()->raw();
    //     $this->post('api/grouping/insert', $grouping);
    //     $this->post('api/grouping/insert', $grouping2);
    //     $result = $this->getJson('api/getGroupsNotInGrouping/' . $grouping['idRegroupement']);
    //     $result->assertJsonFragment([
    //         'idGroupe' => $grouping2['idGroup'][0],
    //     ]);
    // }
}
