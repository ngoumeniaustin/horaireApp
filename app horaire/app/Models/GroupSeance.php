<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupSeance extends Pivot
{
    public static function find($groupId){

        return \DB::select("SELECT groupseance.idSeance, idgroupe as title, duree,  date || 'T' || heureDebut || ':00' as start 
        FROM groupseance JOIN seances ON groupseance.idSeance=seances.idSeance 
        JOIN GROUPING on groupseance.idgroupe = grouping.idGroup where idGroupe=?", [$groupId]);
    }

    public static function addSeance($idSeance, $idGroup){
        \DB::insert('insert into GroupSeance (idGroupe, idSeance) values (?, ?)', [$idGroup, $idSeance]);
    }

    public static function removeLink($idSeance){
        \DB::delete('delete from GroupSeance where idSeance = ?', [$idSeance]);
    }

    public static function removeGroupSeance($idGroup){
        \DB::delete('delete from Seances where idSeance IN (select idSeance from GroupSeance where idGroupe = ?)', [$idGroup]);
        \DB::delete('delete from GroupSeance where idGroupe = ?', [$idGroup]);
    }

    public static function updateGroupSeance($idSeance, $idGroup, $newIdGroup){
        DB::update("UPDATE GroupSeance SET idGroupe=? WHERE idSeance=? AND idGroupe=?;", 
                [$newIdGroup, $idSeance, $idGroup]);
    }
}
