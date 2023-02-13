<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidSQLQueryException;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primarykey = 'idCourse';

    protected $fillable = [
        'acro',
        'libelle',
        'typeCourse',
    ];

    protected $table = 'Course';

    public static function findCourseByName($name)
    {
        return DB::select('SELECT idCourse FROM course WHERE acro = ?', [$name]);
    }

    public static function getAllCourses(){
        $response = DB::select("SELECT * FROM Course");
        return $response;
    }

    public static function getCourse($idCourse){
        $response = DB::select("SELECT * FROM Course WHERE idCourse = ?;", [$idCourse]);
        if(!$response){
            throw new InvalidSQLQueryException("Select query went wrong.");
        }else{return $response;}
    }

    public static function addCourse($acro, $libelle, $typeCourse){
        $idCourse =  Course::insertGetId(
            ['acro' => $acro, 'libelle' => $libelle,'typeCourse' => $typeCourse]);
        if(!$idCourse){
            throw new InvalidSQLQueryException("Insert query went wrong.");
        }else{
            return $idCourse;
        }

    }

    public static function deleteCourse($idCourse){
        $response = DB::delete("DELETE FROM Course WHERE idCourse = ?;", [$idCourse]);
        CourseSeance::removeCourseSeance($idCourse);
        if(!$response){
            throw new InvalidSQLQueryException("Delete query went wrong.");
        }else{return $response;}
    }

    public static function updateCourse($idCourse, $acro, $libelle, $typeCourse){
        $response = DB::update("UPDATE Course SET acro=?, libelle=?, typeCourse=? WHERE idCourse=?;", 
                [$acro, $libelle, $typeCourse, $idCourse]);
        if(!$response){
            throw new InvalidSQLQueryException("Update query went wrong.");
        }else{return $response;}
    }
}
