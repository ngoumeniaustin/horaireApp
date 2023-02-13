function displayCourseModal() {

  $.getJSON(`/api/courses`).then((data) => {
    $(`#courseModal-table`).remove();
    $(`#div-tab-course`).remove();
    $(`#courseModal-body`).append(`
      <div id="div-tab-course"class="panel-body table-responsive" style="max-height:570px; overflow:auto;">
      <table id="courseModal-table" class="table" >
        <thead>
          <tr id="classHeader">
            <th scope="col"># </th>
            <th scope="col">Acronyme </th>
            <th scope="col">Libelle </th>
            <th scope="col">Type </th>
            <th scope="col">Icone </th>
          </tr>

        </thead>
      </table>
      </div>
      `);
    let cpt = 1;
    for (let val of data) {
      $(`#courseModal-table`).append(`
        <tr id="row-${val.idCourse}"> 
        <td>${cpt++}</td> 
        <td>${val.acro}</td> 
        <td>${val.libelle}</td> 
          <td>${val.typeCourse}</td>
          <td class="text-right">
            <div class="btn-group" role="group" aria-label="btn-group-modal">
              <button type="button" id="${val.idCourse}-delete-btn" class="btn btn-secondary"><i class=' fa fa-trash text-danger'></i></button>
              <button value="${cpt}" type="button" id="${val.idCourse}-edit-btn" class="btn btn-secondary"><i class='fas fa-edit text-warning'></i></button>
            </div>
          </td>
        </tr>
      `)
      $(`#${val.idCourse}-delete-btn`).on('click', (event) => {
        console.log("cliqué");
        console.log(val.idCourse);
        $.post('/api/deleteCourse', { 'idCourse': val.idCourse })
        $("#courseModal-title").hide();
        setTimeout(function () { $("#courseModal-title").show(); }, 1300);
        $("#deleted-alert").show();
        setTimeout(function () { $("#deleted-alert").hide(); }, 1300);
        displayCourseModal();

      })



      $(`#${val.idCourse}-edit-btn`).on('click', (event) => {
        $.getJSON(`/api/course/${val.idCourse}`).then((data) => {
          $(`#formUpdateCourse`).remove();
          $(`#rowForm`).remove();
          console.log(data);
          var tableRow = document.getElementById("courseModal-table");
          var btnEdit = document.getElementById(`${val.idCourse}-edit-btn`);
          var rowForm = tableRow.insertRow(btnEdit.value);
          rowForm.id = 'rowForm';

          $('#rowForm').append(`
            <div class="col">
              <form  id="formUpdateCourse" method="POST">
              <input hidden type="text" id="idCourse" name="idCourse" value=${val.idCourse}>
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                  <label for="acros" style="padding-left: 100px;" >Acronyme:<input style="margin-left: 10px;  border-radius:10px;" type="text" id="acro" name="acro" value="${data[0].acro}" readonly></label>
                  <br>
                  <label for="libelles" style="padding-left: 100px;">Libelle:<input style="margin-left: 10px;  border-radius:10px;" type="text" id="libelle" name="libelle" value="${data[0].libelle}"></label>
                  <br>
                  <label for="typeCourses" style="padding-left: 100px;">Type of course:<select  style="margin-left: 10px;  border-radius:10px;" type="text" id="typeCourse" name="typeCourse" value="${data[0].typeCourse}" >
                
                  </select>
                  </label>
                  <br>
                  <p style="padding-left: 150px;">
                  <button id ="updateCourse" class="btn btn-success" type="submit" >update</button>
                  </p>
              </form>
            </div>


            
          `);
          $.getJSON(`api/typesgetAll`).then((datatypes) => {
            for (let typeCourse of datatypes) {
              $('#typeCourse').append(`
              <option value="${typeCourse.name}"> ${typeCourse.name}</option>`);
            }
          });


          var fm = $('#formUpdateCourse');

          fm.submit(function (e) {

            e.preventDefault();

            $.ajax({
              type: fm.attr('method'),
              url: "api/updateCourse",
              data: fm.serialize(),
              success: function (data) {
                console.log('Submission was successful.');
                console.log(data);
                $("#courseModal-title").hide();
                setTimeout(function () { $("#courseModal-title").show(); }, 1300);
                $("#success-alert").show();
                setTimeout(function () { $("#success-alert").hide(); }, 1300);
                displayCourseModal();

              },
              error: function (data) {
                console.log('An error occurred.');
                console.log(data);
                $("#courseModal-title").hide();
                setTimeout(function () { $("#courseModal-title").show(); }, 1300);
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




$(document).ready(function () {
  $('#delete-button-course').on('click', (event) => {
    let course = document.getElementById("idCourse").value;
    console.log(course);
    if (course) {
      $.post('/api/deleteCourse', { 'idCourse': course })
      $("#courseModal-title").hide();
      setTimeout(function () { $("#courseModal-title").show(); }, 1300);
      $("#deleted-alert").show();
      setTimeout(function () { $("#deleted-alert").hide(); }, 1300);
      displayCourseModal();

    }
  }

  )

  var frm = $('#formCourse');

  frm.submit(function (e) {
    

    e.preventDefault();

    $.ajax({
      type: frm.attr('method'),
      url: "api/addCourse",
      data: frm.serialize(),
      success: function (data) {
        console.log('Submission was successful.');
        console.log(data);
        $("#courseModal-title").hide();
        setTimeout(function () { $("#courseModal-title").show(); }, 1300);
        $("#sucess-create-alert").show();
        setTimeout(function () { $("#sucess-create-alert").hide(); }, 1300);
        displayCourseModal();

      },
      error: function (data) {
        console.log('An error occurred.');
        console.log(data);

      },
    });
  });
});
function optionCourse() {
  $.getJSON(`/api/getcourse`).then((data) => {
    $("#typeValues").empty();
    for (let val of data) {
      $("#typeValues").append(
        `<option id="opt" val="${val.acro}">${val.acro}</option>`
      );
    }
  });
}
$("#courseModal").on("click", function (event) {
  var obj = $("#containerCourse");
  if (!$(event.target).closest(obj).length) {
    optionCourse();
  }
});