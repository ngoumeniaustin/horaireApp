<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Horaires</title>
    <!-- pop up add seance bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <!-- select multiple -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- select multiple -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="/css/prof.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/styleClassroom.css">
    <link rel="stylesheet" href="/css/styleTyperoom.css">
    <link rel="stylesheet" href="/css/selectable.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

</head>

<body>
    <header>
        <div class="container">
            <h1 class="text-center text-white">Horaires</h1>
        </div>
        @include('_user_dropdown')
    </header>
    <div id="filters" class="mt-1 ml-1">
        <select name="typeSelect" style="border-radius:10px;" id="typeSelect" autocomplete="off">
            <option value="" selected disabled hidden>Select type...</option>
            <option value="Groups" >Groups</option>
            <option value="Teachers">Teachers</option>
            <option value="Classroom">Classroom</option>
            <option value="Groupings">Groupings</option>
            <option value="course">Courses</option>
        </select>
        <select id="typeValues" style="border-radius:10px;" autocomplete="off"></select>
        <input type="button" id="buttonadd">

        <button id="undo"><img src="https://t4.ftcdn.net/jpg/01/93/23/87/360_F_193238742_IrqKIfvqf2j0K2j6VLZrYlnMqrgHtkF1.jpg" alt=""></button>
        <button id="redo"><img src="https://t4.ftcdn.net/jpg/01/93/23/87/360_F_193238742_IrqKIfvqf2j0K2j6VLZrYlnMqrgHtkF1.jpg" alt=""></button>
        <div></div>
    </div>
    <div id="calendar"></div>

    <div id="exportSection">
        @include('exportData')
        <br>
        <input type="file" id="uploadfile" accept=".csv" onclick="changeDisabled()">
        <button id="uploadconfirm" class="btn btn-secondary" disabled="disable">Upload file</button>
        <div id="successMessageToDisplay" style="visibility: hidden" class="badge bg-primary text-wrap" style="width: 100px;">Teacher added with success !</div>
        <script src="https://cdn.jsdelivr.net/npm/papaparse@5.3.1/papaparse.min.js"></script>
        <div>
            <button id="confirmDefaultSeance" class="btn btn-primary">Confirm</button>
            <input type="checkbox" id="hide" class="btn btn-primary" onclick="semaineDefaut()">Show</input>

            <label><input type="checkbox" id="checkAll" class="btn btn-primary" onclick="onCheck()">Check All</input>

                <button id="exportBtn" class="btn btn-primary" onclick="funExportBtn()">Export</button>
                <div class="modal fade" class="addSeanceModal" id="selectDuree">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 id="newSeanceH" class="modal-title">Export session</h4>
                                <button id="closeIconExport" type="button" class="close" data-dismiss="modal" onclick="funHideModal()">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="start-date" class="col-form-label">Start date:</label>
                                        <input type="date" class="form-control" id="start-date">
                                    </div>
                                    <div class="form-group">
                                        <label for="end-date" class="col-form-label">End date:</label>
                                        <input type="date" class="form-control" id="end-date">
                                    </div>
                                    <div id="filters" class="mt-1 ml-1">
                                        <select name="typeSelectExport" id="typeSelectExport" autocomplete="off" onchange="funTypeSelectExport(event)">
                                            <option value="" selected disabled hidden>Select type...</option>
                                            <option value="Groups">Groupes</option>
                                            <option value="Teachers">Enseignants</option>
                                            <option value="Locals">Local</option>
                                        </select>
                                        <select id="typeValuesExport" autocomplete="off"></select>
                                    </div>
                                </form>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="closeBtnExport" data-dismiss="modal" onclick="funHideModal()">Close</button>
                                <button type="button" id="submitButtonExport" class="btn btn-success" onclick="funSubmitButtonExport()">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div id="seance-calendar"></div>
        <!-- Add Modal -->
        <div class="modal fade" class="addSeanceModal" id="createSeanceModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 id="newSeanceH" class="modal-title">New session</h4>
                        <button id="closeIconCreate" type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="start-time" class="col-form-label">Start time:</label>
                                <input type="time" class="form-control" id="start-time">
                            </div>
                            <div class="form-group">
                                <label for="end-time" class="col-form-label">End time:</label>
                                <input type="time" class="form-control" id="end-time">
                            </div>

                            <div class="form-group">
                                <label for="course" class="col-form-label">Course :</label>
                                <select class="form-select" title="course" id="selectCourse">
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectTeacher" name="selectTeacher" class="selectpicker" title="teachers" multiple data-live-search="true">
                                </select>
                                <p id="errorTeachers" style='color: red;display:contents;'>A teacher is required</p>
                            </div>
                            <div class="form-group">
                                <select id="selectGroups" name="selectGroups" class="selectpicker" title="Groups" multiple data-live-search="true">
                                </select>
                                <p id="errorGroups" style='color: red;display:contents;'>You must fill at least one of these</p>
                            </div>
                            <div class="form-group">
                                <select id="selectLocal" name="selectLocal" class="selectpicker" title="Classrooms" multiple data-live-search="true">
                                </select>
                                <p id="errorClassrooms" style='color: red;display:contents;'>A classroom is required</p>
                            </div>
                            <div class="form-group">
                                <select id="selectGrouping" name="selectGrouping" class="selectpicker" title="Groupings" multiple data-live-search="true">
                                    @foreach($groupings as $grouping)
                                    <option value="{{$grouping->idRegroupement}}">{{ $grouping->idRegroupement }}</option>
                                    @endforeach
                                </select>
                                <p id="errorGroupings" style='color: red;display:contents;'>You must fill at least one of these</p>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="closeBtnCreate" data-dismiss="modal">Close</button>
                        <button type="button" id="submitButtonCreate" class="btn btn-success">OK</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="groupsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="groupContent">
                    <div class="modal-header">
                        <h4 class="modal-title" id="groupsModal-title">Groups</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="container-fluid">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8">
                                    <div id="groupsModal-body">
                                        <table id="groupsModal-table" class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Groupe</th>
                                                    <th score="col">Bloc</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="formModal" id="addGroupsModal-footer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Add Modal -->
        <div class="modal fade" class="addSeanceModal" id="createSeanceModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 id="newSeanceH" class="modal-title">New session</h4>
                        <button id="closeIcon" type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="start-time" class="col-form-label">Start time:</label>
                                <input type="time" class="form-control" id="start-time">
                            </div>
                            <div class="form-group">
                                <label for="end-time" class="col-form-label">End time:</label>
                                <input type="time" class="form-control" id="end-time">
                            </div>
                            <div class="form-group">
                                <label for="start-time" class="col-form-label">Course :</label>
                                <select class="form-select" title="course"></select>
                            </div>
                            <div class="form-group">
                                <select id="selectTeacher" name="selectTeacher" class="selectpicker" title="teachers" multiple data-live-search="true">
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectGroups" name="selectGroups" class="selectpicker" title="Groups" multiple data-live-search="true">
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectLocal" name="selectLocal" class="selectpicker" title="Classrooms" multiple data-live-search="true">
                                    <optgroup label="Labs" class="opgroupLabs">
                                    </optgroup>
                                    <optgroup label="theoric" class="opgrouptheoric">
                                    </optgroup>
                                </select>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">Close</button>
                        <button type="button" id="submitButtonCreate" class="btn btn-success">Add session</button>
                    </div>
                </div>
            </div>
        </div>
        <!--  classroom form  -->
        <div class="modal fade" id="classroomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="classroomContent">
                    <div class="container-fluid " id="containerClassroom">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h4 class="p-notification__title" id="classroomModal-title"> Classroom <h4 class="alert alert-danger" id="fail-alert" style="display:none"> fail </h4>
                                    <h4 class="alert alert-success" id="sucess-create-alert" style="display:none"> success </h4>
                                    <h4 class="alert alert-success " id="success-alert" style="display:none">Classroom updated</h4>
                                    <h4 class="alert alert-danger " id="deleted-alert" style="display:none">the classroom has been deleted.</h4>
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="optionClassroom()" aria-label="Close"></button>
                            </div>
                            <div class="form-group" id="createClassroomModal-body">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div id="classroomModal-body"></div>
                                    </div>
                                    <div class="col-sm-3">
                                        <form id="formClassroom" action="" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <div class="form-group">
                                                <label for="idClassRoom" class="labelClassroom"> Name </label>
                                                <input class="form-control" type="text" name="idClassRoom" id="idClassRoom" placeholder="Name..."><br>
                                            </div>
                                            <div class="form-group">
                                                <label for="places" class="labelClassroom"> Number of places </label>
                                                <input class="form-control" type="text" name="places" id="places" placeholder="number of places (not required)..."><br>
                                            </div>
                                            <div class="form-group">
                                                <label for="examPlaces" class="labelClassroom">Number of places for exams </label>
                                                <input class="form-control" type="text" name="examPlaces" id="examPlaces" placeholder="number of places for exams (not required)..."><br>
                                            </div>
                                            <div class="form-group inputTypeClassroom">
                                                <div class="selectionClassroomLabel">
                                                    <label for="classType" class="labelClassroom"> Type of classroom </label>
                                                </div>
                                                <div class="selectionClassroom">
                                                    <select class="form-control" name="classType" id="classType">
                                                        @foreach($types as $type)
                                                        <option value="{{$type->name}}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="AddTypeClassroom">
                                                    <button class="btn btn-primary" id="addLocalType" type="button">Add local type</button>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input id="addClassroomButton" class="btn btn-success" value="Add">
                                                <input class="btn btn-danger " id="delete-button-classroom" value="Delete">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="teachersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="teacherContent">
                    <div class="modal-header">
                        <h4 class="modal-title" id="teachersModal-title">Teachers <h4 class="alert alert-danger" id="fail" style="display:none"> Votre Acronym doit être unique ! </h4>
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="container-fluid">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8">
                                    <div id="teachersModal-body">
                                        <table id="teachersModal-table" class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Acronym</th>
                                                    <th scope="col">Lastname</th>
                                                    <th scope="col">Firstname</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="formModal" id="addTeachersModal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  classroom type form  -->
        <div class="modal fade" id="typeRoomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="typeRoomContent">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h4 class="p-notification__title" id="typeRoomModal-title"> Type room <h4 class="alert alert-danger" id="fail-alert" style="display:none"> fail </h4>
                                    <h4 class="alert alert-success" id="sucess-create-alert" style="display:none"> success </h4>
                                    <h4 class="alert alert-success " id="success-alert" style="display:none">Type of room updated</h4>
                                    <h4 class="alert alert-danger " id="deleted-alert" style="display:none">the type of room has been deleted.</h4>
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="typeRoomModalClose"></button>
                            </div>
                            <div class="form-group" id="createTyperoomModal-body">
                                <div class="row">

                                    <!--  type room table  -->
                                    <div class="col-sm-9">
                                        <div id="typeroomModal-body">
                                            <div id="typeRoomTableDiv" class="panel-body table-responsive">
                                                <table id="typeroomModal-table" class="table">
                                                    <tr id="type-table-header">
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Icone</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  type room form  -->
                                    <div class="col-sm-3">
                                        <form id="formTyperoom">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <div class="form-group">
                                                <label for="typeroomName" class="labelClassroom"> Name </label>
                                                <input class="form-control" type="text" name="typeroomName" id="typeroomName" placeholder="Name..."><br>
                                            </div>
                                            <div class="modal-footer">
                                                <input id="addTyperoomButton" class="btn btn-success " type="button" value="Add">
                                                <input class="btn btn-danger " id="deleteTyperoomButton" type="button" value="Delete">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div id="zone1" class="zone">
        <div class="list-group">
            <a href="#" class="list-group-item ">
                Cras justo odio
            </a>
            <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
            <a href="#" class="list-group-item">Morbi leo risus</a>
            <a href="#" class="list-group-item">Porta ac consectetur ac</a>
            <a href="#" class="list-group-item">Vestibulum at eros</a>
        </div>
    </div> -->
        <div class="hide" id="checkHide" class="stage">
            <h2>Set Default Seance</h2>
            <!-- <form id="defaultSeanceForm"> -->
            <div class="fullYear">

                <label>Janvier</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="1-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="1-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="1-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="1-4">4</label>

                <label>Février</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="2-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="2-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="2-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="2-4">4</label>

                <label>Mars</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="3-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="3-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="3-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="3-4">4</label>

                <label>Avril</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="4-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="4-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="4-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="4-4">4</label>

                <label>Mai</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="5-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="5-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="5-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="5-4">4</label><br>

                <label>Juin</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="6-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="6-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="6-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="6-4">4</label>

                <label>Juillet</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="7-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="7-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="7-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="7-4">4</label>

                <label>Aout</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="8-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="8-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="8-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="8-4">4</label>

                <label>Septembre</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="9-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="9-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="9-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="9-4">4</label>

                <label>Octobre</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="10-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="10-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="10-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="10-4">4</label>

                <label>Novembre</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="11-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="11-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="11-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="11-4">4</label>

                <label>Decembre</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="12-1">1</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="12-2">2</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="12-3">3</label>
                <label><input class="myCheck" type="checkbox" name="Semaines" value="12-4">4</label>
            </div>
            <!-- <button id="confirmDefaultSeance"type="submit">Confirm</button> -->
            <!-- </form> -->
            <!-- <button form="defaultSeanceForm" id="confirmDefaultSeance" class="btn btn-primary" >Confirm</button> -->

        </div>

        <div class="modal fade" id="groupingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="groupingsModal-title">Groupings</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body mt-0 ml-0" id="groupingsModal-body">
                        <label for="grouping_select">Grouping name : </label>
                        <div id="groupingsMain">
                            <form id="formGrouping" action="" method="POST">
                                <!--<form action="/api/grouping/insert" method="post" onsubmit="return displayGroupingModal();">-->
                                <input id="groupingName" name="idRegroupement" type="text">
                                <select id="idGroup" name="idGroup[]" class="selectpicker" title="Choose your groups" multiple data-live-search="true">
                                    @foreach ($groups as $group)
                                    <option value="{{$group->idGroupe}}">{{$group->idGroupe}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer" id="groupingModal-footer">
                        <button id="closeGroupingButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button id="addGroupingButton" type="submit" class="btn btn-success">Add</button>
                    </div>
                    <!-- </form>-->
                </div>
            </div>
        </div>

        <!--  Course form  -->
        <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="courseContent">
                    <div class="container-fluid " id="containerCourse">
                        <div class="modal-body">

                            <div class="modal-header">
                                <h4 class="p-notification__title" id="CoursemModal-title"> Course <h4 class="alert alert-danger" id="fail-alert" style="display:none"> fail </h4>
                                    <h4 class="alert alert-success" id="sucess-create-alert" style="display:none"> success </h4>
                                    <h4 class="alert alert-success " id="success-alert" style="display:none">Course updated</h4>
                                    <h4 class="alert alert-danger " id="deleted-alert" style="display:none">the course has been deleted.</h4>
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="optionCourse()" aria-label="Close"></button>
                            </div>

                            <div class="form-group" id="createCourseModal-body">



                                <div class="row">
                                    <div class=" col-sm-9">
                                        <div id="courseModal-body"></div>
                                    </div>


                                    <div class="  col-sm-3">
                                        <form id="formCourse" action="" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <div class="form-group">
                                                <label for="acro" class="labelCours">Acronym</label>
                                                <input required type="text" name="acro" id="acro" placeholder="Acronym..." class="acro"><br>
                                            </div>

                                            <div class="form-group">
                                                <label for="libelle" class="labelCours">Libelle</label>
                                                <input required type="text" name="libelle" id="libelle" placeholder="Libelle..." class="libelle"><br>
                                            </div>


                                            <div class="form-group ">
                                                <div class="selectionCourseLabel">
                                                    <label for="classType" class="labelCourse"> Type of course </label>
                                                </div>
                                                <div class="selectionCourse">
                                                    <select class="form-control" name="typeCourse" id="typeCourse">
                                                        @foreach($types as $type)
                                                        <option value="{{$type->name}}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <input id="addCourseButton" typeclass="btn btn-success" type="submit" value="Add">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="groupingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="groupingsModal-title">Groupings</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body mt-0 ml-0" id="groupingsModal-body">
                        <label for="grouping_select">Grouping name : </label>
                        <div id="groupingsMain">
                            <form id="formGrouping" action="" method="POST">
                                <!--<form action="/api/grouping/insert" method="post" onsubmit="return displayGroupingModal();">-->
                                <input id="groupingName" name="idRegroupement" type="text">
                                <select id="idGroup" name="idGroup[]" class="selectpicker" title="Choose your groups" multiple data-live-search="true">
                                    @foreach ($groups as $group)
                                    <option value="{{$group->idGroupe}}">{{$group->idGroupe}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer" id="groupingModal-footer">
                        <button id="closeGroupingButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button id="addGroupingButton" type="submit" class="btn btn-success">Add</button>
                    </div>
                    <!-- </form>-->
                </div>
            </div>
        </div>

        <!--  Course form  -->
        <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="courseContent">
                    <div class="container-fluid " id="containerCourse">
                        <div class="modal-body">

                            <div class="modal-header">
                                <h4 class="p-notification__title" id="CourseModal-title"> Course <h4 class="alert alert-danger" id="fail-alert" style="display:none"> fail </h4>
                                    <h4 class="alert alert-success" id="sucess-create-alert" style="display:none"> success </h4>
                                    <h4 class="alert alert-success " id="success-alert" style="display:none">Course updated</h4>
                                    <h4 class="alert alert-danger " id="deleted-alert" style="display:none">the course has been deleted.</h4>
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="optionCourse()" aria-label="Close"></button>
                            </div>

                            <div class="form-group" id="createCourseModal-body">



                                <div class="row">
                                    <div class=" col-sm-9">
                                        <div id="courseModal-body"></div>
                                    </div>


                                    <div class="  col-sm-3">
                                        <form id="formCourse" action="" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <div class="form-group">
                                                <label for="acro" class="labelCours">Acronym</label>
                                                <input required type="text" name="acro" id="acro" placeholder="Acronym..." class="acro"><br>
                                            </div>

                                            <div class="form-group">
                                                <label for="libelle" class="labelCours">Libelle</label>
                                                <input required type="text" name="libelle" id="libelle" placeholder="Libelle..." class="libelle"><br>
                                            </div>


                                            <div class="form-group ">
                                                <div class="selectionCourseLabel">
                                                    <label for="classType" class="labelCourse"> Type of course </label>
                                                </div>
                                                <div class="selectionCourse">
                                                    <select class="form-control" name="typeCourse" id="typeCourse">
                                                        @foreach($types as $type)
                                                        <option value="{{$type->name}}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <input id="addCourseButton" typeclass="btn btn-success" type="submit" value="Add">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- select multiple -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js">
        </script>
        <!-- select multiple-->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="/js/undoRedo.js"></script>
        <script src="/js/prof.js"></script>
        <script src="/js/global.js"></script>
        <script src="/js/groups.js"></script>
        <script src="/js/classroom.js"></script>
        <script src="/js/typeRoom.js"></script>
        <script src="/js/seanceForm.js"></script>
        <script src="/js/defaultSeance.js"></script>
        <script src="/js/export.js"></script>
        <script src="/js/grouping.js"></script>
        <script src="/js/course.js"></script>
        <script src="/js/importProf.js"></script>

</body>
<script>
    $(document).ready(function() {
        // $('.monthDefaultSeance').checkboxrange();
        $('.fullYear').checkboxrange();
    });
</script>