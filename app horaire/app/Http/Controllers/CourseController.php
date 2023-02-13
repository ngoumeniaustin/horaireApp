<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseSeance;
use App\Exceptions\InvalidSQLQueryException;
use Illuminate\Database\QueryException;
use PDOException;




class CourseController extends Controller
{
    public static function getAllCourses() {
        $response = null;
        try{
            $response = Course::getAllCourses();
            return response()->json($response, 200);
        }catch(InvalidSQLQueryException $ex){
            return response()->json(false, 500);
        }catch(PDOException $ex) {
            return response()->json(false, 500);
        }
    }

    public static function getCourse($idCourse) {
        $response = null;
        try{
            $response = Course::getCourse($idCourse);
            return response()->json($response, 200);
        }catch(InvalidSQLQueryException $ex){
            return response()->json(false, 500);
        }catch(PDOException $ex) {
            return response()->json(false, 500);
        }
    }

    public static function findCourseByName($name)
    {
        try {
            $response = Course::findCourseByName($name);
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }

    public static function addCourse(Request $request) {
        $validated = $request->validate([
            'acro' => 'required|unique:course,acro|string|min:1|max:10',
            'libelle' => 'string',
            'typeCourse' => 'string',
        ]);
        $acro = $validated['acro'];
        $libelle = $validated['libelle'];
        $typeCourse = $validated['typeCourse'];
        try{
            $idCourse = Course::addCourse($acro, $libelle, $typeCourse);
            return response()->json(["idCourse"=>$idCourse], 201);
        }catch(QueryException $ex){
            return response()->json(['message' => 'cant excecute query', 'data' => $request->post()], 500);
        }
    }
    public static function updateCourseSeance($idSeance, $idCourse, $newIdCourse){
               CourseSeance::updateCourseSeance($idSeance, $idCourse, $newIdCourse);
            }
        

    public static function deleteCourse(Request $request)
    {
        $idCourse = $request->post('idCourse');
        $response = null;
        try{
            $response = Course::deleteCourse($idCourse);         
            return response()->json($response, 200);
        }catch(QueryException $ex){
            return response()->json($ex->getMessage(), 500);
        }
    }

    public static function updateCourse(Request $request)
    {
        $idCourse = $request->post('idCourse');
        $acro = $request->post('acro');
        $libelle = $request->post('libelle');
        $typeCourse = $request->post('typeCourse');
        $response = null;
        try{
            $response = Course::updateCourse($idCourse, $acro, $libelle, $typeCourse);
            return response()->json($response, 201);
        }catch(QueryException $ex){
            return response()->json(['message' => 'cant excecute query', 'data' => $request->post()], 500);
        }
    }
    public static function getSeances($idGroup){
        return CourseSeance::find($idGroup);
    }
}
