<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupingSeance extends Pivot
{
    public static function find($groupingId){

        return \DB::select("SELECT groupingseance.idSeance, idRegroupement as title, duree,  date || 'T' || heureDebut || ':00' as start 
        FROM groupingseance 
        JOIN seances ON groupingseance.idSeance=seances.idSeance 
        JOIN GROUPING on groupseance.idRegroupement = grouping.idRegroupement where idRegroupement=?", [$groupingId]);
    }

    public static function addSeance($idSeance, $idRegroupement){
        \DB::insert('insert into GroupingSeance (idRegroupement, idSeance) values (?, ?)', [$idRegroupement, $idSeance]);
    }

    public static function removeLink($idSeance){
        \DB::delete('delete from GroupingSeance where idSeance = ?', [$idSeance]);
    }

    public static function removeGroupingSeance($idRegroupement){
        \DB::delete('delete from Seances where idSeance IN (select idSeance from GroupingSeance where idRegroupement = ?)', [$idRegroupement]);
        \DB::delete('delete from GroupingSeance where idRegroupement = ?', [$idRegroupement]);
    }

    public static function updateGroupingSeance($idSeance, $idRegroupement, $newidRegroupement){
        DB::update("UPDATE GroupingSeance SET idRegroupement=? WHERE idSeance=? AND idRegroupement=?;", 
                [$newidRegroupement, $idSeance, $idRegroupement]);
    }
}
