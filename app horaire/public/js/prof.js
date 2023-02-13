$("#addTeachersModal-footer").append(`
        <form id="formAddTeacher" method="POST" action="">
            <label for="acro">Acronym :</label>
            <input type="text" id="acro" name="acronym"><br><br>
            <label for="name">Last name:</label>
            <input type="text" id="name" name="lastname"><br><br>
            <label for="first">First name:</label>
            <input type="text" id="first" name="firstname"><br><br>
            <input class="btn btn-success" id="btnAdd" type="submit" value="Submit">
        </form>`);

function displayTeacherModal() {
    $.getJSON(`/api/getTeachers`).then((data) => {
        $(`#teachersModal-table`).remove();
        $(`#teachersModal-body`).append(`
          <table id="teachersModal-table" class="table">
            <thead id="thead-prof">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Acronym</th>
                <th scope="col">Lastname</th>
                <th scope="col">Firstname</th>
                <th scope="col"></th>
              </tr>
            </thead>
          </table>
      `);

        let cpt = 1;
        for (let val of data) {
            $(`#teachersModal-table`).append(`
            <tr id="row-${val.idTeacher}"> 
              <td>${cpt++}</td>
              <td>${val.acronym}</td> 
              <td>${val.lastname}</td>
              <td>${val.firstname}</td>
              <td class="text-right">
                <div class="btn-group" role="group" aria-label="btn-group-modal">
                  <button type="button" id="${
                      val.idTeacher
                  }-delete-btn" class="btn btn-secondary"><i class=' fa fa-trash text-danger'></i></button>
                  <button value="${cpt}" type="button" id="${
                val.idTeacher
            }-edit-btn" class="btn btn-secondary"><i class='fas fa-edit text-warning'></i></button>
                </div>
              </td>
            </tr>
        `);

            $(`#${val.idTeacher}-delete-btn`).on("click", (event) => {
                let idteacher=val.idTeacher;
                let last= val.lastname;
                let first = val.firstname;
                let acro=val.acronym;
                
                $.post("/api/teacher/delete", { idTeacher: val.idTeacher });
                
                console.log("delete");
                displayTeacherModal();
                let dics={idTeacher: idteacher, lastname: last, firstname: first, acronym: acro}
                addToUndo("create", "teacher", dics)
                $.getJSON(`/api/getTeachers`).then((data) => {
                    $("#typeValues").empty();
                    for (let val of data) {
                        $("#typeValues").append(
                            `<option id="opt" val="${val.idTeacher}">${val.acronym}</option>`
                        );
                    }
                });
            });

            $(`#${val.idTeacher}-edit-btn`).on("click", (event) => {
                $.getJSON(`/api/getTeachers/${val.idTeacher}`).then((data) => {
                    $(`#formUpdateTeacher`).remove();
                    $(`#rowForm`).remove();

                    var tableRow = document.getElementById(
                        "teachersModal-table"
                    );
                    var btnEdit = document.getElementById(
                        `${val.idTeacher}-edit-btn`
                    );
                    var rowForm = tableRow.insertRow(btnEdit.value);
                    rowForm.id = "rowForm";

                    //$(`#teachersModal-table`).insertRow($(`#${val.idTeacher}-edit-btn`).value()).id = "rowForm";
                    //console.log(data);
                    $("#rowForm").append(`
                      <div id="divForm">
                        <form class="form-inline" id="formUpdateTeacher" method="POST">
                          <input hidden type="text" id="acro" name="idTeacher" value=${val.idTeacher}>
                          <label for="acro" style="padding-left: 100px;">Acronym:<input style="margin-left: 10px;" type="text" id="acro" name="acronym" value="${val.acronym}"></label>
                          <br>
                          <label for="name" style="padding-left: 100px;">Lastname:<input style="margin-left: 10px;" type="text" id="name" name="lastname" value="${val.lastname}"></label>
                          <br>
                          <label for="first" style="padding-left: 100px;">Firstname:<input style="margin-left: 10px;" type="text" id="first" name="firstname" value="${val.firstname}"></label>
                          <br>
                          <p style="padding-left: 150px;"><button id="btnUp" onclick="updateViewAfterChange()" class="btn btn-secondary" type="submit" value="Update">Update<i class='fas fa-edit text-warning'></i></button></p>
                        </form>
                      </div>
                    `);

                    var fm = $("#formUpdateTeacher");
                    fm.submit(function (e) {
                        e.preventDefault();
                        let idteacher=val.idTeacher;
                        let last= val.lastname;
                        let first = val.firstname;
                        let acro=val.acronym;

                        $.ajax({
                            type: fm.attr("method"),
                            url: "api/teacher/update",
                            data: fm.serialize(),
                            success: function (data) {
                                let dics={idTeacher: idteacher, lastname: last, firstname: first, acronym: acro}
                                addToUndo("update", "teacher", dics)
                                $.getJSON(`/api/getTeachers`).then((data) => {
                                    $("#typeValues").empty();
                                    for (let val of data) {
                                        $("#typeValues").append(
                                            `<option id="opt" val="${val.idTeacher}">${val.acronym}</option>`
                                        );
                                    }
                                });
                                displayTeacherModal();
                            },
                            error: function (data) {
                                console.log("An error occurred.");
                                console.log(data);
                            },
                        });
                    });
                });
            });
        }

        $(`#btnAdd`)
            .unbind()
            .on("click", (event) => {
                displayTeacherModal();
            });

        $(`#btnCloseUpdate`).on("click", (event) => {
            $(`#formUpdateTeacher`).remove();
            $(`#rowForm`).remove();
        });
    });
}
function updateViewAfterChange() {
    console.log("update");
    displayTeacherModal();
}

var frm = $("#formAddTeacher");
frm.submit(function (e) {
    e.preventDefault();
    let idteacher;
    let last= document.getElementById("name").value;
    let first = document.getElementById("first").value;
    let acro=document.getElementById("acro").value;
    $.ajax({
        type: frm.attr("method"),
        url: "/api/teacher/create",
        data: frm.serialize(),
        success: function (data) {
            let dics={idTeacher:data, lastname: last, firstname: first, acronym: acro}
                addToUndo("delete", "teacher", dics)
            console.log("Submission was successful.");
            if (data == 200) {
                $("#fail").show();
                setTimeout(function () {
                    $("#fail").hide();
                }, 1400);
            }
            $.getJSON(`/api/getTeachers`).then((data) => {
                $("#typeValues").empty();
                for (let val of data) {
                    $("#typeValues").append(
                        `<option id="opt" val="${val.idTeacher}">${val.acronym}</option>`
                    );
                }
            });

            $("#acro").val("");
            $("#name").val("");
            $("#first").val("");
            displayTeacherModal();
        },
        error: function (data) {
            console.log("An error occurred.");
            console.log(data);
        },
    });
});
