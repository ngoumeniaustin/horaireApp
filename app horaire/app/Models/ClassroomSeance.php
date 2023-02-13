<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomSeance extends Model
{
    public static function find($idClassroom){

        return \DB::select("SELECT ClassroomSeance.idSeance, idClassroom as title, duree,  date || 'T' || heureDebut || ':00' as start 
        FROM ClassroomSeance JOIN seances ON ClassroomSeance.idSeance=seances.idSeance where idClassroom=?", [$idClassroom]);
    }

    public static function addSeance($idSeance, $idClassroom){
        \DB::insert('insert into ClassroomSeance (idClassroom, idSeance) values (?, ?)', [$idClassroom, $idSeance]);
    }

    public static function removeLink($idSeance){
        \DB::delete('delete from ClassroomSeance where idSeance = ?', [$idSeance]);
    }

    public static function removeClassroomSeance($idClassroom){
        \DB::delete('delete from Seances where idSeance IN (select idSeance from ClassroomSeance where idClassroom = ?)', [$idClassroom]);
        \DB::delete('delete from ClassroomSeance where idClassroom = ?', [$idClassroom]);
    }

    public static function updateClassRoomSeance($idSeance, $idClassRoom, $newIdClassRoom){
        DB::update("UPDATE ClassRoomSeance SET idClassRoom=? WHERE idSeance=? AND idClassRoom=?;", 
                [$newIdClassRoom, $idSeance, $idClassRoom]);
    }
}
