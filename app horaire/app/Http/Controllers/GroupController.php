<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupSeance;

class GroupController extends Controller
{
    public static function getGroups()
    {
        try {
            $response = Group::getGroups();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function getGroup($id)
    {
        try {
            $response = Group::getGroup($id);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function findGroupByName($name)
    {
        try {
            $response = Group::findGroupByName($name);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }

    public static function insertGroup(Request $request)
    {
        try {
            $idGroup = Group::insertGroup($request->post("idGroupe"), $request->post("bloc"));
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(["idGroup"=>$idGroup], 201);    
    }

    public static function deleteGroup(Request $request)
    {
        try {
            Group::deleteGroup($request->post("idGroupe"));
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 204);
    }
    /**
     * Set attributes of a group with an id.
     */
    public static function setGroup(Request $request)
    {
        try {
            $idGroup = $request->input('idGroupe');
            $bloc = $request->input('bloc');
            Group::setGroup($idGroup, $bloc);
            return back();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
    }

    public static function getSeances($idGroup){
        return GroupSeance::find($idGroup);
    }

    public static function updateGroupSeance($idSeance, $idGroup, $newIdGroup){
        GroupSeance::updateGroupSeance($idSeance, $idGroup, $newIdGroup);
    }
}
