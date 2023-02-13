<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\ClassroomSeance;
use PhpParser\Builder\Class_;

class ClassRoomController extends Controller
{
    /**
     * Creation of a room by checking the validity of the parameters entered. 
     */
    public static function createClassroom(Request $request)
    {

        // $content = $request->getContent();
        // $request->replace(json_decode($content, True));
        $validated = $request->validate([
            'idClassRoom' => 'required|unique:classroom,idClassRoom|string|min:1|max:10',
            'places' => 'nullable|int',
            'examPlaces' => 'nullable|int',
            'classType' => 'required|string|min:1|max:30',
        ]);
        $id = $validated['idClassRoom'];
        $classType = $validated['classType'];
        $places = $validated['places'];
        $examPlaces = $validated['examPlaces'];
       
        $classroom = ClassRoom::createClassroom($id, $classType, $places, $examPlaces);
        return response()->json(["idClassroom"=>$classroom], 201);    
    }

    public static function findClassroomByName($name)
        {
            try {
                $response = ClassRoom::findClassroomByName($name);
            } catch (\Exception $ex) {
                return response()->json(false, 500);
            }
            return $response;
       }
    
    /**
     * Deletes a class 
     */
    public static function deleteClassroom(Request $request)
    {
        $id = $request->post('idClassRoom');
        if ($id == null)
            throw new \ErrorException('id of classroom is null');
        if (ClassRoom::readClassroom($id) == null)
            throw new \ErrorException("classroom doesn't exist");


        $classroomDelete = ClassRoom::deleteClassroom($id);


        return response()->json($classroomDelete, 200);
    }
    /**
     * Update of a room by checking the validity of the parameters entered.
     */

    public static function updateClassroom(Request $request)
    {
        $id = $request->post('idClassRoom');
        if ($id == null)
            throw new \ErrorException('id of classroom is null');
        if (ClassRoom::readClassroom($id) == null)
            throw new \ErrorException("classroom doesn't exist");

        $validated = $request->validate([
            'idClassRoom' => 'required|string|min:1|max:10',
            'classType' => 'required|string|min:1|max:30',
            'places' => 'nullable|int',
            'examPlaces' => 'nullable|int',
        ]);
        $id = $validated['idClassRoom'];
        $classType = $validated['classType'];
        $places = $validated['places'];
        $examPlaces = $validated['examPlaces'];

        $classroomUpdate = ClassRoom::updateClassroom($id, $classType, $places, $examPlaces);
        return response()->json($classroomUpdate, 200);
    }
    /**
     * Read all the class.
     */
    public static function readAllClassroom()
    {

        $response = ClassRoom::readAllClassroom();
        return response()->json($response, 200);
    }
    /**
     * Read a room.
     */
    public static function readClassroom($id)
    {
        $response = ClassRoom::readClassroom($id);

        return response()->json($response, 200);
    }
    public static function getSeances($idGroup){
        return ClassroomSeance::find($idGroup);
    }

    public static function updateClassRoomSeance($idSeance, $idClassRoom, $newIdClassRoom){
        ClassRoomSeance::updateClassRoomSeance($idSeance, $idClassRoom, $newIdClassRoom);
    }
}
