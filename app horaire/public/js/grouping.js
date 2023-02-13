function displayGroupingModal() {
$('#groupingModal').modal('show');

$.getJSON(`/api/getRegroupement`).then((data) => {
$(`#groupingsModal-table`).remove();
$(`#groupingsMain`).append(`
  <table id="groupingsModal-table" class="table">
    <thead>
      <tr>
        <th></th>
        <th scope="col">Grouping name</th>
        <th scope="col">Groups</th>
      </tr>
    </thead>
  </table>
`);
let cpt = 1;
for (let i = 0; i < data.length; i++) {
  $(`#groupingsModal-table`).append(`
    <tr id="row-${data[i].idRegroupement}"> 
      <td>${cpt++}</td>
      <td>${data[i].idRegroupement}</td> 
      <td id="${data[i].idRegroupement}"></td>
      <td class="text-right">
        <div class="btn-group" role="group" aria-label="btn-group-modal">
          <button type="button" id="${data[i].idRegroupement}-delete-btn" class="btn btn-secondary"><i class=' fa fa-trash text-danger'></i></button>
        </div>
      </td>
    </tr>
  `)

  $(`#${data[i].idRegroupement}-delete-btn`).on('click', (event) => {
    $.post('/api/grouping/delete', { 'idRegroupement': data[i].idRegroupement })
    $.getJSON(`/api/getRegroupement`).then((data) => {
        $("#typeValues").empty();
        for (let val of data) {
            $("#typeValues").append(
                `<option id="opt" val="${val.idRegroupement}">${val.idRegroupement}</option>`
            );
        }
    });
    $.getJSON(`/api/getGroupings`).then((data) => {
        for (var val of data) {
          $(`#${val.idRegroupement}`).remove();
        }
        })
    displayGroupingModal();
  })

}
})

$.getJSON(`/api/getGroupings`).then((data) => {
for (var val of data) {
  $(`#${val.idRegroupement}`).append(`<label>${val.idGroup}<\label>`);
}
})
/*$(`#addGroupingButton`).on('click', function () {
let groupingName = $('#groupingName').val();
let selectedArray = new Array();
let selObj = document.getElementById('idGroup');
let count = 0;
for (i = 0; i < selObj.options.length; i++) {
  if (selObj.options[i].selected) {
    selectedArray[count] = selObj.options[i].value;
    count++;
  }
}
$.post('/api/grouping/insert', { 'idRegroupement': groupingName, 'idGroup': selectedArray });
displayGroupingModal();
});*/


}
$(document).ready(function() {


var frm = $("#formGrouping");
$("#addGroupingButton").click(function (e) {
    e.preventDefault();
    $.ajax({
        type: frm.attr("method"),
        url: "/api/grouping/insert",
        data: frm.serialize(),
        success: function (data) {
            
            $.getJSON(`/api/getRegroupement`).then((data) => {
                $("#typeValues").empty();
                for (let val of data) {
                    $("#typeValues").append(
                        `<option id="opt" val="${val.idRegroupement}">${val.idRegroupement}</option>`
                    );
                }
            });
            $.getJSON(`/api/getGroupings`).then((data) => {
                for (var val of data) {
                  $(`#${val.idRegroupement}`).remove();
                }
                })
            displayGroupingModal();
        },
        error: function (data) {
            console.log("An error occurred.");
            console.log(data);
        },
    });
});
});
