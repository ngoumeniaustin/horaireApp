// classroom 
function displayClassRoomModal() {

    $.getJSON(`/api/readAllClassroom`).then((data) => {
        $(`#classroomModal-table`).remove();
        $(`#div-tab-classroom`).remove();
        $(`#classroomModal-body`).append(`
      <div id="div-tab-classroom"class="panel-body table-responsive" style="max-height:570px; overflow:auto;">
      <table id="classroomModal-table" class="table" >
        <thead>
          <tr id="classHeader">
            <th scope="col"># </th>
            <th scope="col">Name </th>
            <th scope="col">Places </th>
            <th scope="col">Exams </th>
            <th scope="col">Type </th>
            <th scope="col">Icone </th>
          </tr>

        </thead>

      </table>
      </div>
      `);

        let cpt = 1;
        for (let val of data) {
            let examPlace = val.examPlaces;
            let place = val.places;
            if (!examPlace) {
                examPlace = '';
            }
            if (!place) {
                place = '';
            }
            $(`#classroomModal-table`).append(`
        <tr id="row-${val.idClassRoom}"> 
        <td>${cpt++}</td>
        <td>${val.idClassRoom}</td> 
        <td>${place}</td> 
          <td>${examPlace}</td>
          <td>${val.classType}</td>
          <td class="text-right">
            <div class="btn-group" role="group" aria-label="btn-group-modal">
              <button type="button" id="${val.idClassRoom}-delete-btn" class="btn btn-secondary"><i class=' fa fa-trash text-danger'></i></button>
              <button value="${cpt}" type="button" id="${val.idClassRoom}-edit-btn" class="btn btn-secondary"><i class='fas fa-edit text-warning'></i></button>
            </div>
          </td>
        </tr>
      `)
            $(`#${val.idClassRoom}-delete-btn`).on('click', (event) => {
                console.log("cliqué");
                console.log(val.idClassRoom);
                $.post('/api/deleteClassroom', { 'idClassRoom': val.idClassRoom })
                $("#classroomModal-title").hide();
                setTimeout(function () { $("#classroomModal-title").show(); }, 1300);
                $("#deleted-alert").show();
                setTimeout(function () { $("#deleted-alert").hide(); }, 1300);
                displayClassRoomModal();
            })



            $(`#${val.idClassRoom}-edit-btn`).on('click', (event) => {
                $.getJSON(`/api/readClassroom/${val.idClassRoom}`).then((data) => {
                    $(`#formUpdateClassroom`).remove();
                    $(`#rowForm`).remove();

                    var tableRow = document.getElementById("classroomModal-table");
                    var btnEdit = document.getElementById(`${val.idClassRoom}-edit-btn`);
                    var rowForm = tableRow.insertRow(btnEdit.value);
                    rowForm.id = 'rowForm';
                    let examPlace = data[0].examPlaces;
                    let place = data[0].places;
                    if (!examPlace) {
                        examPlace = '';
                    }
                    if (!place) {
                        place = '';
                    }

                    $('#rowForm').append(`
            <div class="col">
            <form  id="formUpdateClassroom" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <label for="id" style="padding-left: 100px;" >Name:<input style="margin-left: 10px;  border-radius:10px;" type="text" id="idClassRoom" name="idClassRoom" value="${data[0].idClassRoom}" readonly></label>
              <br>
              <label for="place" style="padding-left: 100px;">Places:<input style="margin-left: 10px;  border-radius:10px;" type="text" id="places" name="places" value="${place}"></label>
              <br>
              <label for="examPlace" style="padding-left: 100px;">ExamPlaces:<input style="margin-left: 10px;  border-radius:10px;" type="text" id="examPlaces" name="examPlaces" value="${examPlace}"></label>
              <br>
              <label for="class" style="padding-left: 100px;">ClassType:<select  style="margin-left: 10px;  border-radius:10px;" type="text" id="classType" name="classType" value="${data[0].classType}" >
             
              </select>
              </label>
              <br>
              <p style="padding-left: 150px;">
              <button id ="updateClassroom" class="btn btn-success" type="submit" >update</button>
              </p>
            </form>
          </div>


            
          `);
                    $.getJSON(`api/typesgetAll`).then((datatypes) => {
                        for (let typeclass of datatypes) {
                            $('#classType').append(`
              <option value="${typeclass.name}"> ${typeclass.name}</option>`);
                        }
                    });


                    var fm = $('#formUpdateClassroom');

                    fm.submit(function (e) {

                        e.preventDefault();

                        $.ajax({
                            type: fm.attr('method'),
                            url: "api/updateClassroom",
                            data: fm.serialize(),
                            success: function (data) {
                                console.log('Submission was successful.');
                                console.log(data);
                                $("#classroomModal-title").hide();
                                setTimeout(function () { $("#classroomModal-title").show(); }, 1300);
                                $("#success-alert").show();
                                setTimeout(function () { $("#success-alert").hide(); }, 1300);
                                displayClassRoomModal();

                            },
                            error: function (data) {
                                console.log('An error occurred.');
                                console.log(data);
                                $("#classroomModal-title").hide();
                                setTimeout(function () { $("#classroomModal-title").show(); }, 1300);
                                $("#fail-alert").show();
                                setTimeout(function () { $("#fail-alert").hide(); }, 1300);
                            },
                        });
                    });
                })
            })

        }


    })




}

$(document).ready(function() {
$('#delete-button-classroom').on('click', (event) => {
    let classroom = document.getElementById("idClassRoom").value;
    console.log(classroom);
    if (classroom) {
        $.post('/api/deleteClassroom', { 'idClassRoom': classroom })
        $("#classroomModal-title").hide();
        setTimeout(function () { $("#classroomModal-title").show(); }, 1300);
        $("#deleted-alert").show();
        setTimeout(function () { $("#deleted-alert").hide(); }, 1300);
        displayClassRoomModal();
    }
}

)

var frm = $('#formClassroom');

$("#addClassroomButton").click(function(e) {

    e.preventDefault();

    $.ajax({
        type: frm.attr('method'),
        url: "api/createClassroom",
        data: frm.serialize(),
        success: function (data) {
            console.log('Submission was successful.');
            console.log(data);
            $("#classroomModal-title").hide();
            setTimeout(function () { $("#classroomModal-title").show(); }, 1300);
            $("#sucess-create-alert").show();
            setTimeout(function () { $("#sucess-create-alert").hide(); }, 1300);
            displayClassRoomModal();

        },
        error: function (data) {
            console.log('An error occurred.');
            console.log(data);
            /*$("#classroomModal-title").hide();
            setTimeout(function() { $("#classroomModal-title").show(); }, 1300);
            $("#fail-alert").show();
            setTimeout(function() { $("#fail-alert").hide(); }, 1300);*/ //error 
        },
    });
});
});
function optionClassroom(){
    $.getJSON(`/api/getClassroom`).then((data) => {
        $("#typeValues").empty();
        for (let val of data) {
          $("#typeValues").append(
            `<option id="opt" val="${val.idClassRoom}">${val.idClassRoom}</option>`
          );
        }
      });
}
$("#classroomModal").on("click", function (event) {
  var obj = $("#containerClassroom");
  if (!$(event.target).closest(obj).length) {
    optionClassroom();
  }
});
  //finish classroom 


   
