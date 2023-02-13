<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherSeance extends Model
{
    public static function find($idTeacher)
    {

        return DB::select("SELECT TeacherSeance.idSeance, idTeacher as title, duree,  date || 'T' || heureDebut || ':00' as start 
        FROM TeacherSeance JOIN seances ON TeacherSeance.idSeance=seances.idSeance where idTeacher=?", [$idTeacher]);
    }
    
    public static function addSeance($idSeance, $idTeacher)
    {
        DB::insert('insert into TeacherSeance (idTeacher, idSeance) values (?, ?)', [$idTeacher, $idSeance]);
    }

    public static function removeLink($idSeance)
    {
        DB::delete('delete from TeacherSeance where idSeance = ?', [$idSeance]);
    }

    public static function removeTeacherSeance($idTeacher)
    {
        DB::delete('delete from Seances where idSeance IN (select idSeance from TeacherSeance where idTeacher = ?)', [$idTeacher]);
        DB::delete('delete from TeacherSeance where idTeacher = ?', [$idTeacher]);
    }


    public static function updateTeacherSeance($idSeance, $idTeacher, $newIdTeacher){
        DB::update("UPDATE TeacherSeance SET idTeacher=? WHERE idSeance=? AND idTeacher=?;", 
                [$newIdTeacher, $idSeance, $idTeacher]);
    }
}
