<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use Exception;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    public static function getSeances()
    {
        try {
            $response = Seance::getSeances();
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function getSeancesWithLinks(Request $request)
    {
        if ($request->get('filter') == "Groups") {
            $belongGrouping = Seance::getBelongGroupings($request->get('valueFilter'));
            $response = Seance::getSeancesWithLinks($request->get('startWeek'), $request->get('endWeek'), "Groupings", $request->get('valueFilter'), $belongGrouping);
        } else {
            $response = Seance::getSeancesWithLinks($request->get('startWeek'), $request->get('endWeek'), $request->get('filter'), $request->get('valueFilter'));
        }
        return response()->json($response, 200);
    }

    public static function getSeance($id)
    {
        try {
            $response = Seance::getSeance($id);
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function insertSeance(Request $request)
    {
        $id = -1;
        try {
            $id = Seance::insertSeance(
                $request->post("heureDebut"),
                $request->post("heureFin"),
                $request->post("duree"),
                $request->post("date"),
                $request->post("idGroupe"),
                $request->post("idTeacher"),
                $request->post("idClassRoom"),
                $request->post("idCourse"),
                $request->post("idRegroupement"),
            );
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(['idSeance' => $id], 201);
    }

    public static function deleteSeance(Request $request)
    {
        try {
            Seance::deleteSeance($request->post("idSeance"));
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 200);
    }

    public static function updateSeance(Request $request)
    {
        $id = -1;
        try {
            if ($request->post("eventClick") == true) {
                $id = Seance::updateSeance(
                    $request->post("idSeance"),
                    $request->post("heureDebut"),
                    $request->post("heureFin"),
                    $request->post("duree"),
                    $request->post("date"),
                    $request->post("idGroupe"),
                    $request->post("idTeacher"),
                    $request->post("idClassRoom"),
                    $request->post("idCourse"),
                    $request->post("idRegroupement"),
                );
                return response()->json($id, 200);
            }
            if ($request->post("eventChange") == true) {
                Seance::updateSeanceLine(
                    $request->post("idSeance"),
                    $request->post("heureDebut"),
                    $request->post("heureFin"),
                    $request->post("duree"),
                    $request->post("date")
                );
                return response()->json(true, 200);
            }
        } catch (Exception $ex) {
            return response()->json(false, 500);
        }
    }

    public static function getSeancesbetweenTwoDate(Request $request)
    {
        $datas = Seance::getSeancesbetweenTwoDate($request->post("dateDebut"), $request->post("dateFin"), $request->post("categorie"), $request->post("choice"));
        return response()->json($datas, 200);
    }

    public static function getSeancesbetweenTwoDateIcs(Request $request)
    {
        $datas = Seance::getSeancesbetweenTwoDateIcs($request->post("dateDebut"), $request->post("dateFin"), $request->post("categorie"), $request->post("choice"));
        return response()->json($datas, 200);
    }
    
    public static function getBusyGroupTeacherClassroom(Request $request)
    {
        try {
            $response = Seance::getBusyGroupTeacherClassroom(
                $request->post("date"),
                $request->post("heureDebut"),
                $request->post("heureFin")
            );
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }
    public static function getSeanceData()
    {
        try {
            $response = Seance::getSeanceData();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }
}
