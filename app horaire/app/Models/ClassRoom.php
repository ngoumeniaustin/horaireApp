<?php

namespace App\Models;

use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ClassRoom extends Model
{
  use HasFactory;
  
  public $timestamps = false;

  protected $primarykey = 'idClassRoom';

  protected $fillable = [
    'idClassRoom',
    'places',
    'examPlaces',
    'classType',
  ];

  protected $table = 'classroom';

  public static function createClassroom($id, $classType, $places, $examPLaces)
  {
    $data =  array("idClassRoom" => $id, "classType" => $classType, "places" => $places, "examPlaces" => $examPLaces);
    DB::table('classroom')->insert($data);
    return $id;
  }

  public static function findClassroomByName($name)
    {
        return DB::select('SELECT idClassRoom FROM classroom WHERE classType = ?', [$name]);
    }

  public static function readClassroom($id)
  {
    return DB::table('classroom')->where('idClassRoom', $id)->get();
  }

  public static function readAllClassroom()
  {
    return  $test= DB::table('classroom')->get();
  }

  public static function deleteClassroom($id)
  {
    if ($id == null)
      throw new \ErrorException('id of classroom is null');
    if (ClassRoom::readClassroom($id) == null)
      throw new \ErrorException("classroom doesn't exist");

    ClassroomSeance::removeClassroomSeance($id);
    return DB::delete('DELETE FROM classroom WHERE idClassRoom = ?', [$id]);
  }

  public static function updateClassroom($id, $classType, $places, $examPLaces)
  {
    DB::table('classroom')->where('idClassRoom', $id)
      ->update(['idClassRoom' => $id, 'classType' => $classType, 'places' => $places, 'examPlaces' => $examPLaces]);
  }
}
