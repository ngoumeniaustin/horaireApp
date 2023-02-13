<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    public static function getGroups()
    {
        return DB::select('SELECT idGroupe,bloc FROM GROUPS');
    }

    public static function getGroup($id)
    {
        return DB::select('SELECT idGroupe,bloc FROM GROUPS WHERE idGroupe = ?', [$id]);
    }

    public static function findGroupByName($name)
    {
        return DB::select('SELECT idGroupe FROM GROUPS WHERE bloc = ?', [$name]);
    }

    public static function insertGroup($id, $bloc)
    {
        if ($id == null || $bloc == null) 
            throw new \ErrorException('all fields are required, cannot be null');
        \DB::table('GROUPS')->insertGetId([
            'idGroupe' => $id,
            'bloc' => $bloc
        ]);
        return $id;
    }

    public static function deleteGroup($id)
    {
        if ($id == null)
            throw new \ErrorException('id of group is null');
        if (Group::getGroup($id) == null)
            throw new \ErrorException("group doesn't exist");

        GroupSeance::removeGroupSeance($id);
        return DB::delete('DELETE FROM GROUPS WHERE idGroupe = ?', [$id]);
    }
    public static function setGroup($idGroup,$bloc){
        DB::table('GROUPS')->where('idGroupe', $idGroup)
        ->update(['bloc' => $bloc]);
    }
    public static function getSeances($idGroup){
        return GroupSeance::find($idGroup);
    }
}
