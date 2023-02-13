<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use ErrorException;

class Grouping extends Model
{
    use HasFactory;

    public static function getGroupings()
    {
        return DB::select('SELECT * FROM GROUPING');
    }
    
    public static function getOnlyRegroupement()
    {
        return DB::select('SELECT distinct(idRegroupement) FROM GROUPING');
    }

    public static function getGroupsNotInGrouping($idRegroupement)
    {
        if($idRegroupement == null || $idRegroupement == ""){
            throw new ErrorException("The idRegroupement is null or empty");
        }
        return DB::select('SELECT idGroupe FROM GROUPS WHERE idGroupe not in 
        (select idGroup from GROUPING where idRegroupement = ?)', [$idRegroupement]);
    }
    
    public static function getGrouping($idRegroupement)
    {
        if($idRegroupement == null || $idRegroupement == ""){
            throw new ErrorException("The idRegroupement is null or empty");
        }
        return DB::select('SELECT idRegroupement, idGroup FROM GROUPING WHERE idRegroupement = ?', [$idRegroupement]);
    }

    public static function insertGrouping($idRegroupement, array $idGroups)
    {
        if($idRegroupement == null || $idRegroupement == ""){
            throw new ErrorException("The idRegroupement is null or empty");
        }
        foreach ($idGroups as $idGroup) {
            if($idGroup == null || $idGroup == ""){
            throw new ErrorException("The idGroup is null or empty");
        }
            // Si le groupe n'est pas présent pas le foreing key ne fonctionne pas donc il faut test que le group est déjà présent 
            //sinon on le rajoute OU dans le front-end le client pourra uniquement prendre un gorupe qui  est déjà ajouter 
            if(Group::getGroup($idGroup) == null){
                Group::insertGroup($idGroup,3);
            }
            DB::insert('INSERT INTO GROUPING (idRegroupement,idGroup) VALUES (?,?)', [$idRegroupement, $idGroup]);
        }
    }

    public static function deleteGrouping($idRegroupement)
    {
        if($idRegroupement == null || $idRegroupement == ""){
            throw new ErrorException("The idRegroupement is null or empty");
        }
        DB::delete('DELETE FROM GROUPING WHERE idRegroupement = ?', [$idRegroupement]);
    }

    public static function deleteGroupFromGrouping($idRegroupement, $idGroup)
    {
        if($idRegroupement == null || $idRegroupement == "" 
        || $idGroup == null || $idGroup == ""){
            throw new ErrorException("The idRegroupement is null or empty");
        }
        DB::delete('DELETE FROM GROUPING WHERE idRegroupement = ? and idGroup = ?', [$idRegroupement, $idGroup]);
    }
}
