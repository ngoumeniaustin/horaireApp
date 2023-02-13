$("#addGroupsModal-footer").append(`
        <form id="formAddGroup" method="POST" action="">
            <label for="idGroupe">Groupe:</label>
            <input type="text" id="idGroupe" name="idGroupe"><br><br>
            <label for="bloc">Bloc:</label>
            <input type="text" id="bloc" name="bloc"><br><br>
            <input class="btn btn-success" id="btnAdd" type="submit" value="Submit">
        </form>`);

function displayGroupModal() {
    $.getJSON(`/api/getGroups`).then((data) => {
        $(`#groupsModal-table`).remove();
        $(`#groupsModal-body`).append(`
                <table id="groupsModal-table" class="table">
                  <thead id="thead-group">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Groupe</th>
                      <th scope="col">Bloc</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                </table>
            `);

        let cpt = 1;
        console.log(data);
        for (let val of data) {
            $(`#groupsModal-table`).append(`
                  <tr id="row-${val.idGroupe}"> 
                    <td>${cpt++}</td>
                    <td>${val.idGroupe}</td> 
                    <td>${val.bloc}</td>
                    <td class="text-right">
                      <div class="btn-group" role="group" aria-label="btn-group-modal">
                        <button type="button" id="${
                            val.idGroupe
                        }-delete-btn" class="btn btn-secondary"><i class=' fa fa-trash text-danger'></i></button>
                        <button value="${cpt}" type="button" id="${
                val.idGroupe
            }-edit-btn" class="btn btn-secondary"><i class='fas fa-edit text-warning'></i></button>
                      </div>
                    </td>
                  </tr>
              `);

            $(`#${val.idGroupe}-delete-btn`).on("click", (event) => {
                $.post("/api/group/delete", { idGroupe: val.idGroupe });
                let dics = {idGroupe: val.idGroupe, bloc: val.bloc}
                addToUndo("create","group",dics)
                console.log("delete");
                console.log(val.idGroupe);
                displayGroupModal();
                $.getJSON(`/api/getGroups`).then((data) => {
                    $("#typeValues").empty();
                    for (let val of data) {
                        $("#typeValues").append(
                            `<option id="opt" val="${val.idGroupe}">${val.idGroupe}</option>`
                        );
                    }
                });
            });

            $(`#${val.idGroupe}-edit-btn`).on("click", (event) => {
                $.getJSON(`/api/group/${val.idGroupe}`).then((data) => {
                    $(`#formUpdateGroup`).remove();
                    $(`#rowForm`).remove();

                    var tableRow = document.getElementById(
                        "groupsModal-table"
                    );
                    var btnEdit = document.getElementById(
                        `${val.idGroupe}-edit-btn`
                    );
                    var rowForm = tableRow.insertRow(btnEdit.value);
                    rowForm.id = "rowForm";

                    //$(`#teachersModal-table`).insertRow($(`#${val.idTeacher}-edit-btn`).value()).id = "rowForm";
                    //console.log(data);
                    $("#rowForm").append(`
                            <div id="divForm">
                              <form class="form-inline" id="formUpdateGroup" method="POST">
                                <label for="idGroupe" style="padding-left: 100px;">Groupe:<input style="margin-left: 10px;" type="text" id="idGroupe" name="idGroupe" value="${val.idGroupe}" disabled></label>
                                <br>
                                <label for="bloc" style="padding-left: 100px;">Bloc:<input style="margin-left: 10px;" type="text" id="bloc" name="bloc" value="${val.bloc}"></label>
                                <br>
                                <p style="padding-left: 150px;"><button id="btnUp" onclick="updateViewAfterChange()" class="btn btn-secondary" type="submit" value="Update">Update<i class='fas fa-edit text-warning'></i></button></p>
                              </form>
                            </div>
                          `);

                    var fm = $("#formUpdateGroup");
                    fm.submit(function (e) {
                        e.preventDefault();

                        $.ajax({
                            type: "POST",
                            url: "api/group/update",
                            data: {
                                idGroupe: $("#formUpdateGroup input#idGroupe").val(),
                                bloc: $("#formUpdateGroup input#bloc").val()
                            },
                            success: function (data) {
                                let dics = {idGroupe: val.idGroupe, bloc: val.bloc}
                                addToUndo("update","group",dics)
                                $.getJSON(`/api/getGroups`).then((data) => {
                                    $("#typeValues").empty();
                                    for (let val of data) {
                                        $("#typeValues").append(
                                            `<option id="opt" val="${val.idGroupe}">${val.idGroupe}</option>`
                                        );
                                    }
                                });
                                displayGroupModal();
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

        $(`#btnAddGroup`)
            .unbind()
            .on("click", (event) => {
                displayGroupModal();
            });

        $(`#btnCloseUpdate`).on("click", (event) => {
            $(`#formUpdateGroup`).remove();
            $(`#rowForm`).remove();
        });
    });
}
function updateViewAfterChange() {
    console.log("update");
    displayGroupModal();
}

var fm = $("#formAddGroup");
fm.submit(function (e) {
    e.preventDefault();

    $.ajax({
        type: fm.attr("method"),
        url: "/api/group/create",
        data: fm.serialize(),
        success: function (data) {
            var idgroup = document.getElementById("idGroupe").value;
            var thebloc = document.getElementById("bloc").value;
            let dics = {idGroupe: idgroup, bloc: thebloc}
            addToUndo("delete","group",dics)
            console.log("Submission was successful.");
            $.getJSON(`/api/getGroups`).then((data) => {
                $("#typeValues").empty();
                for (let val of data) {
                    $("#typeValues").append(
                        `<option id="opt" val="${val.idGroupe}">${val.idGroupe}</option>`
                    );
                }
            });

            $("#idGroupe").val("");
            $("#bloc").val("");
            displayGroupModal();
        },
        error: function (data) {
            console.log("An error occurred.");
            console.log(data);
        },
    });
});
