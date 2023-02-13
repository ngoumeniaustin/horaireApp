<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherSeance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Throwable;

class TeacherController extends Controller
{
    use HasFactory;
    /**
     * Create an instance of a teacher.
     */
    public static function createTeacher(Request $request)
    {
        $idTeacher=-1;
        try {

            $lastname = $request->input('lastname');
            $firstname = $request->input('firstname');
            $acronym = $request->input('acronym');
            $getAcronym = Teacher::getAcronym($acronym);
            if ($getAcronym->isEmpty()) {
                $result = Teacher::createTeacher($lastname, $firstname, $acronym);
                return response()->json($result, 201);
            } else {
                return response()->json(200);
            }
            //return back();
            // http_response_code(201);

        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
    }



    /**
     * Get all instances of teacher.
     */
    public static function getAllTeacher()
    {
        try {
            $response = Teacher::getAllTeacher();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }
    /**
     * Get the teacher with an id.
     */
    public static function getTeacher($id)
    {
        try {
            $response = Teacher::getTeacher($id);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        http_response_code(200);
        return response()->json($response);
    }

    public static function findTeacherByName($name)
    {
        try {
            $response = Teacher::findTeacherByName($name);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }

    /**
     * Set attributes of a teacher with an id.
     */
    public static function setTeacher(Request $request)
    {
        try {
            $idTeacher = $request->input('idTeacher');
            $lastname = $request->input('lastname');
            $firstname = $request->input('firstname');
            $acronym = $request->input('acronym');
            $result = Teacher::setTeacher($idTeacher, $lastname, $firstname, $acronym);

            //$response = Teacher::getTeacher($idTeacher);
            return response()->json($result, 200);
            //return response()->json($response);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
    }
    /**
     * Delete an instance of a teacher with an id.
     */
    public static function deleteTeacher(Request $request)
    {
        try {
            Teacher::deleteTeacher($request->post('idTeacher'));
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(true, 204);
    }
    
    public static function getSeances($idGroup){
        return TeacherSeance::find($idGroup);
    }


    public static function updateTeacherSeance($idSeance, $idTeacher, $newIdTeacher){
        TeacherSeance::updateTeacherSeance($idSeance, $idTeacher, $newIdTeacher);
    }
    public static function createTeacherWithArray(Request $request) {
        $arraySelected = $request->toArray()["result"];
        //dd($arraySelected);

        try {
            $idTeacher = -1;
            foreach ($arraySelected as $key => $value) {
                foreach ($value as $secondKey => $secondValue) {
                    if (TeacherController::findTeacherByName(array_values($secondValue)[0]) == null) {
                        Teacher::createTeacher(array_values($secondValue)[1], array_values($secondValue)[2], array_values($secondValue)[0]);
                    }
                }
            }
        } catch (Throwable $e) {
        }
        //dd("end method ===");
        return response()->json(200);
    }
}
