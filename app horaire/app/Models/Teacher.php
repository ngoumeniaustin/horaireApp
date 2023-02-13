<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use HasFactory;

    /**
     * Create a new Teacher 
     */
    public static function createTeacher($lastname, $firstname, $acronym)
    {
        $data =  array("lastname" => $lastname, "firstname" => $firstname, "acronym" => $acronym);
        return DB::table('teacher')->insertGetId($data);
    }

    public static function findTeacherByName($name)
    {
        return DB::select('SELECT idTeacher FROM teacher WHERE acronym = ?', [$name]);
    }

    /**
     *  Return All teacher
     */
    public static function getAllTeacher()
    {
        return DB::table('teacher')->get();;
    }

    /**
     *  Get a teacher by his id 
     */
    public static function getTeacher($id)
    {
        return DB::table('Teacher')->where('idTeacher', $id)->get();
    }

    /**
     *  Update a teacher by his id 
     */
    public static function setTeacher($id, $lastname, $firstname, $acronym)
    {
        DB::table('teacher')->where('idTeacher', $id)
            ->update(['lastname' => $lastname, 'firstname' => $firstname, 'acronym' => $acronym]);
    }
    /**
     * 
     */
    public static function getAcronym($acronym){
        return DB::table('Teacher')->where('Acronym', $acronym)->get();
    }
    
    /**
     *  Delete a teacher by his id 
     */
    public static function deleteTeacher($id)
    {
        if ($id == null)
            throw new \ErrorException('id of teacher is null');
        if (Teacher::getTeacher($id) == null)
            throw new \ErrorException("teacher doesn't exist");

        $idTeacher = DB::delete('DELETE FROM TEACHER WHERE idTeacher = ?', [$id]);
        //TeacherSeance::removeTeacherSeance($id);
        return $idTeacher;
        //DB::table('teacher')->delete($id);
    }
}
