<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\TypeController;

use App\Http\Controllers\SeanceController;
use App\Http\Controllers\GroupingController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AllowedUserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getGroups', [GroupController::class, 'getGroups']);
Route::get('/group/{id}', [GroupController::class, 'getGroup']);
Route::get('/getGroups/{id}', [GroupController::class, 'getSeances']);
Route::post('/group/create', [GroupController::class, 'insertGroup']);
Route::post('/group/update', [GroupController::class, 'setGroup']);
Route::post('/group/delete', [GroupController::class, 'deleteGroup']);

Route::post('/teacher/create',[TeacherController::class,'createTeacher']);
Route::get('/getAllTeacher',[TeacherController::class,'getAllTeacher']);
Route::get('/getTeachers',[TeacherController::class,'getAllTeacher']);
Route::get('/getTeacher/{id}',[TeacherController::class,'getTeacher']);
Route::get('/getTeachers/{id}', [TeacherController::class, 'getSeances']);
Route::post('/teacher/update',[TeacherController::class,'setTeacher']);
//Route::get('/getTeachers/{id}', [TeacherController::class, 'getSeances']);
Route::post('/teacher/delete',[TeacherController::class,'deleteTeacher']);

Route::post('/createClassroom',[ClassRoomController::class,'createClassroom']);
Route::post('/deleteClassroom',[ClassRoomController::class,'deleteClassroom']);
Route::get('/readClassroom/{id}', [ClassRoomController::class,'readClassroom']);
Route::post('/updateClassroom', [ClassRoomController::class,'updateClassroom']);
Route::get('/readAllClassroom', [ClassRoomController::class,'readAllClassroom']);
Route::get('/getClassroom', [ClassRoomController::class,'readAllClassroom']);
Route::get('/getLocals', [ClassRoomController::class,'readAllClassroom']);



Route::get('/types', [TypeController::class, 'index'])->name('types.index');
Route::get('/typesgetAll', [TypeController::class, 'index']);
Route::post('/types', [TypeController::class, 'store'])->name('types.store');
Route::get('/types/{id}', [TypeController::class, 'show'])->name('types.show');
Route::put('/types', [TypeController::class, 'update'])->name('types.update');
Route::delete('/types/', [TypeController::class, 'destroy'])->name('types.destroy');


Route::get('/getSeances', [SeanceController::class, 'getSeances']);
Route::get('/seance/{id}', [SeanceController::class, 'getSeance']);
Route::post('/seance/create', [SeanceController::class, 'insertSeance']);
Route::post('/seance/delete', [SeanceController::class, 'deleteseance']);
Route::post('/seance/update', [SeanceController::class, 'updateSeance']);
Route::get('/getSeancesWithLinks', [SeanceController::class, 'getSeancesWithLinks']);
Route::post('/getBusy', [SeanceController::class, 'getBusyGroupTeacherClassroom']);
Route::post('/getSeanceData', [SeanceController::class, 'getSeanceData']);

Route::get('/getGroupings', [GroupingController::class, 'getGroupings']);
Route::get('/getRegroupement', [GroupingController::class, 'getOnlyRegroupement']);
Route::get('/getGrouping/{idRegroupement}', [GroupingController::class, 'getGrouping']);
Route::get('/getGroupsNotInGrouping/{idRegroupement}', [GroupingController::class, 'getGroupsNotInGrouping']);
Route::post('/grouping/insert', [GroupingController::class, 'insertGrouping']);
Route::post('/grouping/delete', [GroupingController::class, 'deleteGrouping']);
Route::post('/grouping/deleteGroupFromGrouping', [GroupingController::class, 'deleteGroupFromGrouping']);


Route::get('/course/{idCourse}',[CourseController::class,'getCourse']);
Route::get('/getcourse',[CourseController::class,'getAllCourses']);
Route::get('/courses', [CourseController::class,'getAllCourses']);
Route::get('/getCourses/{id}', [CourseController::class, 'getSeances']);
Route::get('/coursesView', [CourseController::class,'getAllCoursesView']);
Route::post('/addCourse', [CourseController::class, 'addCourse']);
Route::post('/deleteCourse', [CourseController::class, 'deleteCourse']);
Route::post('/updateCourse', [CourseController::class, 'updateCourse']);

Route::post('/insert/allowedUser', [AllowedUserController::class, 'insertAllowedUser']);

Route::post('/getSeancesbetweenTwoDate',[SeanceController::class,'getSeancesbetweenTwoDate']);
Route::post('/getSeancesbetweenTwoDateIcs',[SeanceController::class,'getSeancesbetweenTwoDateIcs']);
Route::post('/createTeacherWithArray',[TeacherController::class,'createTeacherWithArray']);
