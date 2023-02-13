<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Group;
use App\Models\ClassRoom;
use App\Models\Type;
use App\Models\Grouping;
use App\Exceptions\InvalidSQLQueryException;
use Illuminate\Database\QueryException;
use PDOException;


class IndexController extends Controller
{

    public function index()
    {
        $courses = $this->getAllCoursesView();
        $teachers = $this->getAllTeachersView();
        $groups = $this->getGroupsView();
        $classrooms = $this->getClassroomsView();
        $groupings = $this->getGroupingView();
        $types = $this->getTypeView();
        return view('index', ["courses" => $courses, "teachers" =>
        $teachers, "groups" => $groups, "classrooms" => $classrooms, "types" => $types, "groupings" => $groupings]);
    }

    

    public static function getAllTeachersView()
    {
        try {
            $response = Teacher::getAllTeacher();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }

    public static function getGroupingView()
    {
        try {
            $response = Grouping::getOnlyRegroupement();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }


    public function getAllCoursesView()
    {
        $response = null;
        try {
            $response = Course::getAllCourses();
            return $response;
        } catch (InvalidSQLQueryException $ex) {
            return response()->json(false, 500);
        } catch (PDOException $ex) {
            return response()->json(false, 500);
        }
    }

    public static function getGroupsView()
    {
        try {
            $response = Group::getGroups();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return $response;
    }

    /**
     * Read all the class.
     */
    public static function getClassroomsView()
    {

        $response = ClassRoom::readAllClassroom();

        return $response;
    }
    public static function getTypeView()
    {
        try {
            $response  = Type::all();
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }


        return $response;
    }
}
