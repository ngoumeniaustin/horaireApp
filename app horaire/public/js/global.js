$(() => {
  //init
  window.calendar = new FullCalendar.Calendar(
    document.getElementById("calendar"),
    {
      initialView: "timeGridWeek",
      height: $("#calendar").height(),
      firstDay: 1,
      hiddenDays: [0],
      slotMinTime: "08:00",
      slotMaxTime: "18:00",
      selectable: true,
      editable: true,
      locale: "fr",
      select: function (info) {
        $("#createSeanceModal").modal("show");
        hideErrors(); 
         
        $("#createSeanceModal").on("hidden.bs.modal", function (e) {
          handlerCloseForm();
        });
        setUpHour(info);
        setUpDate(info);
        setUpSelect(info);
        onTimerChange(info);
        $("#submitButtonCreate").on("click", function () {
          handleEventCreate(info);
        });

        $("#closeBtnCreate").on("click", function () {
          handlerCloseForm();
        });

        $("#closeIconCreate").on("click", function () {
          handlerCloseForm();
        });
      },
      eventContent: function (arg) {
        return setEventContent(arg);
      },

      eventClick: function (info) {
        $("#createSeanceModal").modal("show");
        hideErrors(); 
        setSelectOptions(info);
        setUpSelect(info, "update");
        onTimerChange(info, "update");
        $("#createSeanceModal").on("hidden.bs.modal", function (e) {
          handlerCloseForm();
        });

        $("#submitButtonCreate").on("click", function () {
          handleEventClick(info);
          setTimeout(function () { refreshCalendar(); }, 200);
        });

        $("#closeBtnCreate").on("click", function () {
          handlerCloseForm();
        });

        $("#closeIconCreate").on("click", function () {
          handlerCloseForm();
        });
      },
      eventChange: function (info) {
        handleEventUpdate(info);
        //refreshCalendar();
      },
    }
  );

  //functions

  window.emptyCalendar = () => {
    window.calendar.removeAllEvents();
  };

  function refreshCalendar() {
    let week = getWeek();
    $.ajax({
      url: `/api/getSeancesWithLinks`,
      dataType: "json",
      data: {
        startWeek: week[0],
        endWeek: week[1],
        filter: $("#typeSelect").val(),
        valueFilter: $("#typeValues").val(),
      }
    }).then((data) => {
      console.log(data);
      setSeances(data);
    });
  }

  window.calendar.render();

  //listeners
  $(window).on("resize", () => {
    window.calendar.updateSize();
  });

  $("body").on("click", "button.fc-prev-button", function () {
    refreshCalendar();
  });

  $("body").on("click", "button.fc-next-button", function () {
    refreshCalendar();
  });

  $(document).on("change", "#typeValues", function () {
    refreshCalendar();
  });


  $(document).on("change", "#typeSelect", (e) => {
    $("#filters #typeValues")
      .css("display", "inline-block")
      .html(
        `<option disabled hidden selected value="">Select ${e.target.value}...</option>`
      );
    //add a s to classroom !
    if (e.target.value == "Classroom") {
      $("#filters #buttonadd")
        .addClass("btn btn-secondary btn-sm")
        .css("display", "inline-block")
        .val(`Gérer ${e.target.value}s`)
        .on("click", (event) => {
          $(`#${e.target.value.toLowerCase()}Modal`).css(
            "display",
            "inline-block"
          );

          displayClassRoomModal();
        })
        .attr("data-bs-target", `#${e.target.value.toLowerCase()}Modal`)
        .attr("data-bs-toggle", "modal");
    } else {
      $("#filters #buttonadd")
        .addClass("btn btn-secondary btn-sm")
        .css("display", "inline-block")
        .val(`all ${e.target.value}`)
        .on("click", (event) => {
          $(`#${e.target.value.toLowerCase()}Modal`).css(
            "display",
            "inline-block"
          );
          if (e.target.value == "Groups") {
            displayGroupModal();
          }
          if (e.target.value == "Classroom") {
            displayClassRoomModal();
          }
          if (e.target.value == "Teachers") {
            displayTeacherModal();
          }
          if (e.target.value == "Groupings") {
            displayGroupingModal();
          }
          if (e.target.value == "course") {
            displayCourseModal();
          }

        })
        .attr("data-bs-target", `#${e.target.value.toLowerCase()}Modal`)
        .attr("data-bs-toggle", "modal");
    }

    let Classrooms = e.target.value;
    $.getJSON(`/api/get${e.target.value}`).then((data) => {
      if (e.target.value == "Groups") {
        for (let val of data) {
          $("#typeValues").append(
            `<option val="${val.idGroupe}">${val.idGroupe}</option>`
          );
        }
      }
      if (e.target.value == "Classroom") {
        for (let val of data) {
          $("#typeValues").append(
            `<option val="${val.idClassRoom}">${val.idClassRoom}</option>`
          );
        }
      }
      if (e.target.value == "Teachers") {
        for (let val of data) {
          $("#typeValues").append(`<option val="${val.idTeacher}">${val.acronym}</option>`)
        }
      }
      if (e.target.value == "Groupings") {
        $.getJSON(`/api/getRegroupement`).then((data) => {
          for (let val of data) {
            $("#typeValues").append(`<option val="${val.idRegroupement}">${val.idRegroupement}</option>`)
          }
        })
      }
      if (e.target.value == "course") {
        for (let val of data) {
          $("#typeValues").append(`<option val="${val.acro}">${val.acro}</option>`);
        }
      }

    });
  });
});




//export feature
//get selected option for export session
function funTypeSelectExport(e) {
  $("#filters #typeValuesExport")
    .css("display", "inline-block")
    .html(
      `<option disabled hidden selected value="">Select ${e.target.value}...</option>`
    );
  $("#filters #buttonadd")
    .addClass("btn btn-secondary btn-sm")
    .css("display", "inline-block")
    .val(`all ${e.target.value}`)
    .on("click", (event) => {
      $(`#${e.target.value.toLowerCase()}Modal`).css(
        "display",
        "inline-block"
      );
      if (e.target.value == "Groups") {
        displayGroupModal();
      }
    })
    .attr("data-bs-target", `#${e.target.value.toLowerCase()}Modal`)
    .attr("data-bs-toggle", "modal");
  $.getJSON(`/api/get${e.target.value}`).then((data) => {
    for (let val of data) {
      if (e.target.value == "Teachers") {
        $("#typeValuesExport").append(
          `<option value="${val.acronym}">${val.acronym}</option>`
        );
      }
      if (e.target.value == "Groups") {
        $("#typeValuesExport").append(
          `<option value="${val.idGroupe}">${val.idGroupe}</option>`
        );
      }
      if (e.target.value == "Locals") {
        $("#typeValuesExport").append(
          `<option value="${val.idClassRoom}">${val.idClassRoom}</option>`
        );
      }
    }
    $('#typeValuesExport').selectpicker('refresh');
  });
}

function funExportBtn() {
  $("#selectDuree").modal("show");
  window.$("#selectDuree").modal("show");
}

//get the selected elements to export
function funSubmitButtonExport() {
  let dateDebut = document.getElementById("start-date").value;
  let dateFin = document.getElementById("end-date").value;
  let categorie = $("#typeSelectExport").find(":selected").val();
  let choice = $("#typeValuesExport").find(":selected").val();
  console.log(dateDebut);
  console.log(dateFin);
  console.log(categorie);
  console.log(choice);

  $.post(
    "/api/getSeancesbetweenTwoDate",
    {
      dateDebut: dateDebut,
      dateFin: dateFin,
      categorie: categorie,
      choice: choice,
    },
    function (data, status) {
      console.log(status);
      if (status == "success") {
        console.log(data);
        console.log("go refresh => no !");
        download("export.json", data);
      } else {
        console.log("not success");
      }
      //avec sa on serait en asynchrone.
    }
  );
}

function download(filename, text) {
  var element = document.createElement("a");
  element.setAttribute(
    "href",
    "data:text/json;charset=utf-8," +
    encodeURIComponent(JSON.stringify(text))
  );
  element.setAttribute("download", filename);

  element.style.display = "none";
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

function funHideModal() {
  $("#selectDuree").modal("hide");
}

function setUpHour(info) {
  if (
    info.start.getHours().toString().length == 2 &&
    info.start.getMinutes().toString().length == 2
  ) {
    $("#start-time").val(
      info.start.getHours() + ":" + info.start.getMinutes()
    );
    $("#end-time").val(
      info.start.getHours() + 2 + ":" + info.start.getMinutes()
    );
  }
  if (
    info.start.getHours().toString().length == 2 &&
    info.start.getMinutes().toString().length == 1
  ) {
    $("#start-time").val(
      info.start.getHours() + ":" + "0" + info.start.getMinutes()
    );
    $("#end-time").val(
      info.start.getHours() + 2 + ":" + "0" + info.start.getMinutes()
    );
  }
  if (
    info.start.getHours().toString().length == 1 &&
    info.start.getMinutes().toString().length == 2
  ) {
    $("#start-time").val(
      "0" + info.start.getHours() + ":" + info.start.getMinutes()
    );
    $("#end-time").val(
      info.start.getHours() + 2 + ":" + info.start.getMinutes()
    );
  }
  if (
    info.start.getHours().toString().length == 1 &&
    info.start.getMinutes().toString().length == 1
  ) {
    $("#start-time").val(
      "0" + info.start.getHours() + ":" + "0" + info.start.getMinutes()
    );
    $("#end-time").val(
      info.start.getHours() + 2 + ":" + "0" + info.start.getMinutes()
    );
  }
}

function setUpDate(info) {
  $("#newSeanceH #date").empty();
  var day;
  var month;
  var year = info.start.getFullYear();
  if (info.start.getDate().toString().length != 2) {
    day = "0" + info.start.getDate();
  } else {
    day = info.start.getDate();
  }
}




