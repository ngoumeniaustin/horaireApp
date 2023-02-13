<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;

class Seance extends Model
{

    public static function getSeances()
    {
        return \DB::select('SELECT * FROM seances');
    }

    public static function getSeance($id)
    {
        return DB::select('SELECT * FROM seances WHERE idSeance = ?', [$id]);
    }

    public static function getSeanceData()
    {
        $data = [];
        $data['teachers'] = \DB::select('SELECT * FROM teacher');
        $data['groups'] = \DB::select('SELECT * FROM groups');
        $data['classrooms'] = \DB::select('SELECT * FROM classroom');
        $data['courses'] = \DB::select('SELECT * FROM course');
        return $data;
    }
    public static function getBelongGroupings($idGroup)
    {
        return DB::table('groups')->join('grouping', 'groups.idGroupe', '=', 'grouping.idGroup')->where('idGroupe','=',$idGroup)->pluck("idRegroupement");
        //return DB::select('SELECT idRegroupement from groups gr join grouping g on gr.idGroupe = g.idGroup where idGroupe = ?', [$idGroup]);
    }

    public static function getSeancesWithLinks($startWeek, $endWeek, $filter, $valuefilter, $belongGrouping = null)
    {
        $groupings = "";
        $attribut = "";
        if ($belongGrouping != null) {
            foreach ($belongGrouping as $value) {
                $groupings = $groupings . "'" . $value . "'" .",";
            }
            $groupings = chop($groupings,",");
            if($groupings==""){
                $groupings="'FillWithUselessStuffToAvoidCrash'";
            }            
            $attribut = "groupingSeance.idRegroupement IN (" . $groupings . ") OR g.idGroupe = '" . $valuefilter . "'";
        }
        if ($filter == "Teachers") {
            $attribut = "t.acronym = '".$valuefilter."'";
        } else if ($filter == "Groups") {
            $attribut = "g.idGroupe = ".$valuefilter."";
        } else if ($filter == "Classroom") {
            $attribut = "cl.idClassRoom = '".$valuefilter."'";
        } else if ($filter == "course") {
            $attribut = "c.acro = '".$valuefilter."'";
        } else if ($filter == "Groupings" && $belongGrouping == null) {
            $attribut = "groupingSeance.idRegroupement = '".$valuefilter."'";
        }
        return \DB::select(
            "SELECT s.idSeance, s.date, s.duree, s.heureDebut, s.heureFin,c.acro,cl.idClassRoom,GROUP_CONCAT(DISTINCT(t.acronym)) as teacherAcro,GROUP_CONCAT(DISTINCT(g.idGroupe)) as groupe,GROUP_CONCAT(DISTINCT(groupingSeance.idRegroupement)) as grouping
                FROM seances s 
                JOIN CourseSeance cs on cs.idSeance = s.idSeance
                JOIN Course c on c.idCourse = cs.idCourse
                JOIN TeacherSeance ts on ts.idSeance = s.idSeance
                JOIN teacher t on t.idTeacher = ts.idTeacher
                JOIN ClassroomSeance class on class.idSeance = s.idSeance
                JOIN classroom cl on cl.idClassRoom = class.idClassRoom
                LEFT JOIN GroupSeance gs on gs.idSeance = s.idSeance
                LEFT JOIN groups g on g.idGroupe = gs.idGroupe
                LEFT JOIN GroupingSeance groupingSeance on groupingSeance.idSeance =  s.idSeance
                WHERE (" . $attribut . ") AND s.date BETWEEN ? AND ?
                GROUP BY s.idSeance,s.date, s.heureDebut",
        [$startWeek,$endWeek]);
    }

    public static function insertSeance(
        $heureDebut,
        $heureFin,
        $duree,
        $date,
        $idGroup,
        $idTeacher,
        $idClassroom,
        $idCourse,
        $idGrouping,
    ) {
        $idSeance = DB::table('seances')->insertGetId([
            'heureDebut' => $heureDebut,
            'heureFin' => $heureFin,
            'duree' => $duree,
            'date' => $date,
        ]);

        if ($idGroup != null) {
            foreach ($idGroup as $value) {
                GroupSeance::addSeance($idSeance, $value);
            }
        }
        if ($idGrouping != null) {
            foreach ($idGrouping as $value) {
                GroupingSeance::addSeance($idSeance, $value);
            }
        }
        foreach ($idTeacher as $value) {
            TeacherSeance::addSeance($idSeance, $value);
        }
        foreach ($idClassroom as $value) {
            ClassroomSeance::addSeance($idSeance, $value);
        }
        CourseSeance::addSeance($idSeance, $idCourse);
        return $idSeance;
    }

    public static function deleteSeance($id)
    {
        GroupSeance::removeLink($id);
        TeacherSeance::removeLink($id);
        ClassroomSeance::removeLink($id);
        CourseSeance::removeLink($id);
        GroupingSeance::removeLink($id);
        DB::delete('DELETE FROM seances WHERE idSeance = ?', [$id]);
    }

    public static function updateSeanceLine($idSeance, $heureDebut, $heureFin, $duree, $date)
    {
        DB::update(
            "UPDATE seances SET heureDebut = ?, heureFin = ?, duree = ?, date= ? WHERE idSeance = ?;",
            [$heureDebut, $heureFin, $duree, $date, $idSeance]
        );
    }

    public static function updateSeance(
        $idSeance,
        $heureDebut,
        $heureFin,
        $duree,
        $date,
        $newIdGroupe,
        $newIdTeacher,
        $newIdClassRoom,
        $newIdCourse,
        $newIdGrouping,
    ) {

        Seance::deleteSeance($idSeance);
        $id = Seance::insertSeance(
            $heureDebut,
            $heureFin,
            $duree,
            $date,
            $newIdGroupe,
            $newIdTeacher,
            $newIdClassRoom,
            $newIdCourse,
            $newIdGrouping,
        );
        return $id;
    }
    public static function getSeancesbetweenTwoDate($debut, $fin, $categorie, $filtre)
    {
        $data = null;
        if ($categorie == 'Teachers') {
            $data = DB::select("SELECT Course.Libelle, classroom.idClassRoom, Teacher.acronym,  groups.idGroupe from Seances
            join  CourseSeance on Seances.idSeance = CourseSeance.idSeance
            join Course on CourseSeance.idCourse = Course.idCourse
            join TeacherSeance on Seances.idSeance = TeacherSeance.idSeance
            join Teacher on TeacherSeance.idTeacher = Teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = Seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join GroupSeance on GroupSeance.idSeance = Seances.idSeance
            join groups on groups.idGroupe = GroupSeance.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND teacher.acronym = '$filtre'");
        } else if ($categorie == 'Groups') {
            $data = DB::select("SELECT Course.Libelle, classroom.idClassRoom, Teacher.acronym from Seances
            join  CourseSeance on Seances.idSeance = CourseSeance.idSeance
            join Course on CourseSeance.idCourse = Course.idCourse
            join TeacherSeance on Seances.idSeance = TeacherSeance.idSeance
            join Teacher on TeacherSeance.idTeacher = Teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = Seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join GroupSeance on GroupSeance.idSeance = Seances.idSeance
            join groups on groups.idGroupe = GroupSeance.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND groups.idGroupe = '$filtre'");
        } else if ($categorie == 'Locals') {
            $data = DB::select("SELECT Course.Libelle, classroom.idClassRoom, Teacher.acronym, groups.idGroupe from Seances
            join  CourseSeance on Seances.idSeance = CourseSeance.idSeance
            join Course on CourseSeance.idCourse = Course.idCourse
            join TeacherSeance on Seances.idSeance = TeacherSeance.idSeance
            join Teacher on TeacherSeance.idTeacher = Teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = Seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join GroupSeance on GroupSeance.idSeance = Seances.idSeance
            join groups on groups.idGroupe = GroupSeance.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND classroom.idClassRoom = '$filtre'");
        }

        return $data;
    }

    public static function getSeancesbetweenTwoDateIcs($debut, $fin, $categorie, $filtre){
        if ($categorie == 'Teachers') {
            return DB::select("SELECT groups.idGroupe as Groupe,course.acro as CoursAcronyme,course.libelle as Cours,classroom.idClassRoom as Local,classroom.classType,teacher.acronym as TeacherAcronym,teacher.lastname as TeacherLastName,teacher.firstname as TeacherFirstName,heureDebut , heureFin, date  FROM seances
            join  CourseSeance on Seances.idSeance = CourseSeance.idSeance 
            join Course on CourseSeance.idCourse = Course.idCourse
            join TeacherSeance on Seances.idSeance = TeacherSeance.idSeance
            join Teacher on TeacherSeance.idTeacher = Teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = Seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join GroupSeance on GroupSeance.idSeance = Seances.idSeance
            join groups on groups.idGroupe = GroupSeance.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND teacher.acronym = '$filtre'");
        } else if ($categorie == 'Groups') {
            return DB::select("SELECT groups.idGroupe as Groupe,course.acro as CoursAcronyme,course.libelle as Cours,classroom.idClassRoom as Local,classroom.classType,teacher.acronym as TeacherAcronym,teacher.lastname as TeacherLastName,teacher.firstname as TeacherFirstName,heureDebut , heureFin, date  FROM seances
            JOIN TeacherSeance on seances.idSeance = TeacherSeance.idSeance 
            JOIN teacher on TeacherSeance.idTeacher = teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join CourseSeance on CourseSeance.idSeance = seances.idSeance
            join course on CourseSeance.idCourse = course.idCourse
            join GroupSeance on GroupSeance.idSeance = seances.idSeance
            join groups on GroupSeance.idGroupe = groups.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND groups.idGroupe = '$filtre'");
        } else if ($categorie == 'Locals') {
            return DB::select("SELECT groups.idGroupe as Groupe,course.acro as CoursAcronyme,course.libelle as Cours,classroom.idClassRoom as Local,classroom.classType,teacher.acronym as TeacherAcronym,teacher.lastname as TeacherLastName,teacher.firstname as TeacherFirstName,heureDebut , heureFin, date  FROM seances
            JOIN TeacherSeance on seances.idSeance = TeacherSeance.idSeance 
            JOIN teacher on TeacherSeance.idTeacher = teacher.idTeacher
            join ClassroomSeance on ClassroomSeance.idSeance = seances.idSeance
            join classroom on ClassroomSeance.idClassRoom = classroom.idClassRoom
            join CourseSeance on CourseSeance.idSeance = seances.idSeance
            join course on CourseSeance.idCourse = course.idCourse
            join GroupSeance on GroupSeance.idSeance = seances.idSeance
            join groups on GroupSeance.idGroupe = groups.idGroupe
            WHERE date BETWEEN '$debut' AND '$fin' AND classroom.idClassRoom = '$filtre'");
        }
        

    }

    public static function getBusyGroupTeacherClassroom($date, $startTime, $endTime)
    {
        if (DB::getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'mysql') {
            return \DB::select("SELECT s.idSeance,s.heureDebut,s.heureFin,t.acronym as teacherName,t.idTeacher, g.idGroupe, c.idClassRoom  FROM Seances s
            JOIN TeacherSeance ts on ts.idSeance = s.idSeance
            JOIN teacher t on t.idTeacher = ts.idTeacher
            JOIN GroupSeance gs on gs.idSeance = s.idSeance
            JOIN groups g on g.idGroupe = gs.idGroupe
            JOIN ClassroomSeance class on class.idSeance = s.idSeance
            JOIN classroom c on c.idClassRoom = class.idClassRoom
            WHERE date=? AND (((TIME_TO_SEC(heureDebut)) BETWEEN TIME_TO_SEC(?) AND TIME_TO_SEC(?))
            OR ((TIME_TO_SEC(heureFin)) BETWEEN TIME_TO_SEC(?) AND TIME_TO_SEC(?)))",
                [$date, $startTime, $endTime, $startTime, $endTime]);
        } else {

            return \DB::select("SELECT s.idSeance,s.heureDebut,s.heureFin,t.acronym as teacherName,t.idTeacher, g.idGroupe, c.idClassRoom  FROM Seances s
            JOIN TeacherSeance ts on ts.idSeance = s.idSeance
            JOIN teacher t on t.idTeacher = ts.idTeacher
            JOIN GroupSeance gs on gs.idSeance = s.idSeance
            JOIN groups g on g.idGroupe = gs.idGroupe
            JOIN ClassroomSeance class on class.idSeance = s.idSeance
            JOIN classroom c on c.idClassRoom = class.idClassRoom
            WHERE date=? AND (((strftime('%s', heureDebut)) BETWEEN strftime('%s', ?) AND strftime('%s', ?))
            OR ((strftime('%s', heureFin)) BETWEEN strftime('%s', ?) AND strftime('%s', ?)))",
                [$date, $startTime, $endTime, $startTime, $endTime]);
        }
    }

}
