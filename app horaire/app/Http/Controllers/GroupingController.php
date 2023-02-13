<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grouping;
use Exception;

class GroupingController extends Controller
{
    public static function getGroupings()
    {
        try {
            $response = Grouping::getGroupings();
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function getGrouping($idRegroupement)
    {
        try {
            $response = Grouping::getGrouping($idRegroupement);
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function getOnlyRegroupement()
    {
        try {
            $response = Grouping::getOnlyRegroupement();
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }



    public static function getGroupsNotInGrouping($idRegroupement)
    {
        try {
            $response = Grouping::getGroupsNotInGrouping($idRegroupement);
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function insertGrouping(Request $request)
    {
        try {
            Grouping::insertGrouping($request->idRegroupement, $request->idGroup);
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 201);
    }

    public static function deleteGrouping(Request $request)
    {        
        try {
            Grouping::deleteGrouping($request->idRegroupement);            
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 204);
    }

    public static function deleteGroupFromGrouping(Request $request)
    {
        try {
            Grouping::deleteGroupFromGrouping($request->idRegroupement,$request->idGroup);
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 204);
    }

    public static function getSeances($idGrouping){
        return GroupingSeance::find($idGrouping);
    }

    public static function updateGroupingSeance($idSeance, $idGrouping, $newIdGrouping){
        GroupingSeance::updateGroupingSeance($idSeance, $idGrouping, $newIdGrouping);
    }
}
