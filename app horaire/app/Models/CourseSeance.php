<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSeance extends Model
{
    public static function find($idCours)
    {

        return \DB::select("SELECT CourseSeance.idSeance, idCourse as title, duree,  date || 'T' || heureDebut || ':00' as start 
        FROM CourseSeance JOIN seances ON CourseSeance.idSeance=seances.idSeance where idCourse=?", [$idCours]);
    }

    public static function addSeance($idSeance, $idCours)
    {
        \DB::insert('insert into CourseSeance (idCourse, idSeance) values (?, ?)', [$idCours, $idSeance]);

        //\DB::insert('insert into CourseSeance (idCours, idSeance) values (?, ?)', [$idCours, $idSeance]);
    }

    public static function removeLink($idSeance)
    {
        \DB::delete('delete from CourseSeance where idSeance = ?', [$idSeance]);
    }

    public static function removeCourseSeance($idCours)
    {
        \DB::delete('delete from Seances where idSeance IN (select idSeance from CourseSeance where idCourse = ?)', [$idCours]);
        \DB::delete('delete from CourseSeance where idCourse = ?', [$idCours]);
    }

    public static function updateCourseSeance($idSeance, $idCourse, $newIdCourse)
    {
        DB::update(
            "UPDATE CourseSeance SET idCourse=? WHERE idSeance=? AND idCourse=?;",
            [$newIdCourse, $idSeance, $idCourse]
        );
    }
}
