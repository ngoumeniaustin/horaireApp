function handlerCloseForm() {
    $("#closeBtnCreate").unbind("click");
    $("#closeIconCreate").unbind("click");
    $("#submitButtonCreate").unbind("click");
    $("#createSeanceModal").modal("hide");
}

function hideErrors() {
    $("#errorGroups").hide(); 
    $("#errorGroupings").hide();
    $("#errorTeachers").hide(); 
    $("#errorClassrooms").hide();
}

function verifyForm(){
    valid = true;
    if ($("#selectGroups").val().length == 0 && $("#selectGrouping").val().length == 0) {
        $("#errorGroups").show();
        $("#errorGroupings").show();
        valid = false;
    }
    if ($("#selectTeacher").val().length == 0) {
        $("#errorTeachers").show();
        valid = false;
    }
    if ($("#selectLocal").val().length == 0) {
        $("#errorClassrooms").show();
        valid = false;
    }
    return valid;
}

function getSelectedOptions() {
    let groups = $("#selectGroups option:selected").map(function () {
        return $(this).val();
    }).get();
    let teachers = $("#selectTeacher option:selected").map(function () {
        return $(this).data("acronym");
    }).get();
    let classrooms = $("#selectLocal option:selected").map(function () {
        return $(this).data("classe");
    }).get();
    let groupings = $("#selectGrouping option:selected").map(function () {
        return $(this).val();
    }).get();
    let course = $("#selectCourse").find(':selected').data('acro');

    let groupsStr = "" + groups
    let teacherStr = ""
    let classroomStr = ""
    let groupingsStr = "" + groupings
    for (let i = 0; i < teachers.length; ++i) {
        if (i != teachers.length - 1) {
            teacherStr += teachers[i] + ","
        } else {
            teacherStr += teachers[i]
        }
    }
    for (let i = 0; i < classrooms.length; ++i) {
        if (i != classrooms.length - 1) {
            classroomStr += classrooms[i] + ","
        } else {
            classroomStr += classrooms[i]
        }
    }

    var options = { groups: groupsStr, teachers: teacherStr, classrooms: classroomStr, course: course, groupings: groupingsStr };
    return options;
}


function setSelectOptions(info) {
    let formattedTime = getFormattedTime(info.event);
    $("#start-time").val(formattedTime[0]);
    $("#end-time").val(formattedTime[1]);
    $('.selectpicker').selectpicker('deselectAll');
    $.each(info.event.extendedProps['groups'].split(","), function (i, e) {
        $("#selectGroups option[value='" + e + "']").prop("selected", true);
    });
    $.each(info.event.extendedProps['teachers'].split(","), function (i, e) {
        $("#selectTeacher option[data-acronym='" + e + "']").prop("selected", true);
    });
    $.each(info.event.extendedProps['classrooms'].split(","), function (i, e) {
        $("#selectLocal option[data-classe='" + e + "']").prop("selected", true);
    });
    $.each(info.event.extendedProps['groupings'].split(","), function (i, e) {
        $("#selectGrouping option[value='" + e + "']").prop("selected", true);
    });
    $('#selectCourse').val($("#selectCourse option[data-acro='" + info.event.title + "']").val())
    $('.selectpicker').selectpicker('render');
}

function handleCreateSuccess(data, date) {
    var selected = getSelectedOptions();
    window.calendar.addEvent({
        id: data.split(':')[1].split('}')[0],
        title: selected["course"],
        start: date + "T" + $("#start-time").val() + ":00",
        end: date + "T" + $("#end-time").val() + ":00",
        color: "#3d85c6",
        groups: selected["groups"],
        classrooms: selected["classrooms"],
        teachers: selected["teachers"],
        groupings: selected["groupings"]
    });
}

function handleUpdateSuccess(data, date) {
    var selected = getSelectedOptions();
    window.calendar.addEvent({
        id: data,
        title: selected["course"],
        start: date + "T" + $("#start-time").val() + ":00",
        end: date + "T" + $("#end-time").val() + ":00",
        color: "#3d85c6",
        groups: selected["groups"],
        classrooms: selected["classrooms"],
        teachers: selected["teachers"],
        groupings: selected["groupings"]
    })
}

function diff_hours(dt2, dt1) {
    var diff = (dt2.getTime() - dt1.getTime())
    return Math.abs(diff);
}

function calculateDuration(start, end) {
    var startTime = new Date();
    startTime.setHours(start[0]);
    startTime.setMinutes(start[1])
    var endTime = new Date();
    endTime.setHours(end[0]);
    endTime.setMinutes(end[1])

    let diff = diff_hours(startTime, endTime);
    return new Date(diff).toISOString().slice(11, 16);
}

function postUpdate(info) {
    let date = info.event.startStr.split("T")[0];
    let duree = calculateDuration($("#start-time").val().split(':'), $("#end-time").val().split(':'));
    if(verifyForm()){
        $.post({
            url: "api/seance/update",
            data: {
                heureDebut: $("#start-time").val(),
                heureFin: $("#end-time").val(),
                duree: duree,
                date: date,
                idSeance: info.event.id,
                idGroupe: $("#selectGroups").val(),
                idTeacher: $("#selectTeacher").val(),
                idClassRoom: $("#selectLocal").val(),
                idCourse: $("#selectCourse").val()[0],
                idRegroupement: $("#selectGrouping").val(),
                eventClick: true,
            },
            success: function (data) {
                info.event.remove();
                handleUpdateSuccess(data, date);
            },
        });
        return true;
    }
    return false;
}

function handleEventCreate(info) {
    if (postCreate(info)) {
        $("#submitButtonCreate").unbind("click");
        $("#createSeanceModal").modal("hide");
    }
}

function handleEventClick(info) {
    if (postUpdate(info)) {
        $("#submitButtonCreate").unbind("click");
        $("#createSeanceModal").modal("hide");
    }
}

// function handleEventUpdate(info) {
//     let formattedTime = getFormattedTime(info.event);
//     let duree = calculateDuration(formattedTime[0].split(':'), formattedTime[1].split(':'));
//     console.log(info.event.id);
//     $.post({
//         url: "api/seance/update",
//         data: {
//             idSeance: info.event.id,
//             heureDebut: formattedTime[0],
//             heureFin: formattedTime[1],
//             duree: duree,
//             date: formattedTime[2],
//             eventChange: true,
//         },
//     });
// }

function setEventContent(arg) {
    return {
        html:
            arg.event.startStr.match(
                /(?!T)[0-9][0-9]:[0-9][0-9](?!$)/
            ) +
            " - " +
            arg.event.endStr.match(
                /(?!T)[0-9][0-9]:[0-9][0-9](?!$)/
            ) +
            "<br>" +
            arg.event.title +
            " - " +
            arg.event.extendedProps["teachers"] +
            " - " +
            arg.event.extendedProps["classrooms"] +
            "<br>" +
            arg.event.extendedProps["groups"] +
            "<br>" +
            arg.event.extendedProps["groupings"],
    };
}

function getFormattedTime(event) {
    let start = new Date(event.start);
    let end = new Date(event.end);
    let startHours = ("0" + start.getHours()).slice(-2);
    let startMinutes = ("0" + start.getMinutes()).slice(-2);
    let endHours = ("0" + end.getHours()).slice(-2);
    let endMinutes = ("0" + end.getMinutes()).slice(-2);
    let startHourFormatted = startHours + ":" + startMinutes;
    let endHourFormatted = endHours + ":" + endMinutes;
    let date = event.startStr.split("T")[0];
    let response = [startHourFormatted, endHourFormatted, date];
    return response;
};

function setSeances(data) {
    window.emptyCalendar();
    for (let seance of data) {
        if(seance.groupe == null){
            seance.groupe = "";
        }
        if(seance.grouping == null){
            seance.grouping = "";
        }
        window.calendar.addEvent({
            id: seance.idSeance,
            title: seance.acro,
            start: seance.date + "T" + seance.heureDebut,
            end: seance.date + "T" + seance.heureFin,
            color: "#3d85c6",
            groups: seance.groupe,
            classrooms: seance.idClassRoom,
            teachers: seance.teacherAcro,
            groupings: seance.grouping
        });
    }
};

function getWeek() {
    let startDayWeek = calendar.view.activeStart;
    let endDayWeek = calendar.view.activeEnd;

    var firstDay = new Date(startDayWeek);
    firstDay.setDate(firstDay.getDate() + 1);
    var lastDay = new Date(endDayWeek);

    dayStartWeek = firstDay.toISOString().substring(0, 10);
    dayEndWeek = lastDay.toISOString().substring(0, 10);
    let week = [dayStartWeek, dayEndWeek];
    return week
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
            "0" +
            info.start.getHours() +
            ":" +
            "0" +
            info.start.getMinutes()
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

    if (info.start.getMonth().toString().length != 2) {
        month = "0" + info.start.getMonth();
    } else {
        month = info.start.getMonth();
    }
    var strDate = day + "/" + month + "/" + year;
    var date = info.start.toString();
    var splitedDate = date.split(" ");
    $("#newSeanceH").append(
        '<p id="date">' + splitedDate[0] + " - " + strDate + "</p>"
    );
}
function getAllBusySeance(info, str) {
    let date;
    let busySeance = [];
    if (str == "update") {
        date = getFormattedTime(info.event);
        $.ajax({
            url: "api/getBusy",
            type: "post",
            dataType: "json",
            data: {
                "date": date[2],
                "heureDebut": date[0],
                "heureFin": date[1],
            },
            success: function (data) {
                for (let row of data) {
                    busySeance.push(
                        {
                            "idTeacher": row.idTeacher,
                            "idGroupe": row.idGroupe,
                            "idClassRoom": row.idClassRoom
                        }
                    )
                }
            }
        });
    } else {
        date = info.startStr.split("T")[0];
        $.ajax({
            url: "api/getBusy",
            type: "post",
            dataType: "json",
            data: {
                "date": date,
                "heureDebut": $("#start-time").val(),
                "heureFin": $("#end-time").val()
            },
            success: function (data) {

                for (let row of data) {
                    busySeance.push(
                        {
                            "idTeacher": row.idTeacher,
                            "idGroupe": row.idGroupe,
                            "idClassRoom": row.idClassRoom
                        }
                    )
                }

            }
        });
    }
    return busySeance;
}

function setUpSelectData(info, str) {
    let busySeance = getAllBusySeance(info, str);
    $.ajax({
        url: "api/getSeanceData",
        type: "post",
        dataType: "json",
        success: function (data) {
            $("#selectGroups").empty();
            $("#selectTeacher").empty();
            $("#selectLocal").empty();
            $("#selectCourse").empty();
            for (let group of data['groups']) {
                $("#selectGroups").append(
                    `<option value="${group.idGroupe}"> ${group.idGroupe}</option>`
                );
                for (let seance of busySeance) {
                    if (seance.idGroupe == group.idGroupe) {
                        $(`#selectGroups option[value=${group.idGroupe}]`)
                            .addClass("text-white bg-danger")
                    }
                }
            }

            for (let teacher of data['teachers']) {
                $("#selectTeacher").append(
                    `<option data-acronym="${teacher.acronym}" value="${teacher.idTeacher}">${teacher.acronym}</option>`
                );
                for (let seance of busySeance) {
                    if (seance.idTeacher == teacher.idTeacher) {
                        $(`#selectTeacher option[value=${teacher.idTeacher}]`)
                            .addClass("text-white bg-danger")
                    }
                }
            }

            for (let classroom of data['classrooms']) {
                $("#selectLocal").append(

                    `<option data-classe="${classroom.idClassRoom}" value="${classroom.idClassRoom}">${classroom.idClassRoom}</option>`
                );
                for (let seance of busySeance) {
                    if (seance.idClassRoom == classroom.idClassRoom) {
                        $(`#selectLocal option[value=${classroom.idClassRoom}]`)
                            .addClass("text-white bg-danger")
                    }
                }
            }
            for (let course of data['courses']) {
                $("#selectCourse").append(
                    `<option data-acro="${course.acro}" value="${course.idCourse}">${course.acro}</option>`
                );
            }

            $(".selectpicker").selectpicker("refresh");
        }
    });
}

function setUpSelect(info, str) {
    setUpSelectData(info, str);
}

function postCreate(info) {
    let date = info.startStr.split("T")[0];
    let duree = calculateDuration($("#start-time").val().split(':'), $("#end-time").val().split(':'));
    if(verifyForm()){
        $.post({
            url: "api/getBusy",
            type: "post",
            dataType: "json",
            data: {
                "date": date,
                "heureDebut": $("#start-time").val(),
                "heureFin": $("#end-time").val()
            },
            success: function (busySeance) {
                groupe = $("#selectGroups").val();
                teacher = $("#selectTeacher").val();
                classRoom = $("#selectLocal").val();
                course = $("#selectCourse").val()[0];
                if (busySeance.find(seance => seance.idGroupe == groupe) || busySeance.find(seance => seance.idTeacher == teacher) || busySeance.find(seance => seance.idClassRoom == classRoom)) {
                    $("#calendar").append('<div id="seance-busy-alert"class="alert alert-danger ">This seance cannot be placed, here cause : </div>')
                    $("#seance-busy-alert").append('<ul id="seance-busy-list"></ul>')
                    for (let seance of busySeance) {
                        if (seance.idTeacher == teacher || seance.idGroupe == groupe || seance.idClassRoom == classRoom) {
                            $("#seance-busy-list").append(
                                `<li>Teacher ${seance.teacherName} give lessons to  ${seance.idGroupe} in ${seance.idClassRoom}  between ${seance.heureDebut} to ${seance.heureFin}</li>`
                            )
                        }
                    }
                    setTimeout(function () {
                        $("#seance-busy-alert").remove();
                    }, 5000);
                } else {
                    $.post({
                        url: "api/seance/create",
                        dataType: "text",
                        data: {
                            heureDebut: $("#start-time").val(),
                            heureFin: $("#end-time").val(),
                            duree: duree,
                            date: date,
                            idGroupe: groupe,
                            idTeacher: teacher,
                            idClassRoom: classRoom,
                            idCourse: course,
                            idRegroupement: $("#selectGrouping").val(),
                        },
                        success: function (data) {
                            handleCreateSuccess(data, date);
                        },
                    });
                }
            }
        });
        return true;
    }
    return false;
}
function postUpdate(info) {
    let date = info.event.startStr.split("T")[0];
    let duree = calculateDuration($("#start-time").val().split(':'), $("#end-time").val().split(':'));
    if(verifyForm()){
        $.post({
            url: "api/getBusy",
            type: "post",
            dataType: "json",
            data: {
                "date": date,
                "heureDebut": $("#start-time").val(),
                "heureFin": $("#end-time").val()
            },
            success: function (busySeance) {
                groupe = $("#selectGroups").val();
                teacher = $("#selectTeacher").val();
                classRoom = $("#selectLocal").val();
                course = $("#selectCourse").val()[0];
                if (busySeance.find(seance => seance.idSeance != info.event.id) && (busySeance.find(seance => seance.idGroupe == groupe) || busySeance.find(seance => seance.idTeacher == teacher) || busySeance.find(seance => seance.idClassRoom == classRoom))) {
                    $("#calendar").append('<div id="seance-busy-alert"class="alert alert-danger ">This seance cannot be placed, here cause : </div>')
                    $("#seance-busy-alert").append('<ul id="seance-busy-list"></ul>')
                    for (let seance of busySeance) {
                        if (seance.idTeacher == teacher || seance.idGroupe == groupe || seance.idClassRoom == classRoom) {
                            $("#seance-busy-list").append(
                                `<li>Teacher ${seance.teacherName} give lessons to  ${seance.idGroupe} in ${seance.idClassRoom}  between ${seance.heureDebut} to ${seance.heureFin}</li>`
                            )
                        }
                    }
                    setTimeout(function () {
                        $("#seance-busy-alert").remove();
                    }, 5000);
                } else {
                    $.post({
                        url: "api/seance/update",
                        data: {
                            heureDebut: $("#start-time").val(),
                            heureFin: $("#end-time").val(),
                            duree: duree,
                            date: date,
                            idSeance: info.event.id,
                            idGroupe: groupe,
                            idTeacher: teacher,
                            idClassRoom: classRoom,
                            idCourse: course,
                            idRegroupement: $("#selectGrouping").val(),
                            eventClick: true,
                        },
                        success: function (data) {
                            info.event.remove();
                            handleUpdateSuccess(data, date);
                        },
                    })
                }
            }
        });
        return true;
    }
    return false;
}


function handleEventUpdate(info) {
    let formattedTime = getFormattedTime(info.event);
    let duree = calculateDuration(formattedTime[0].split(':'), formattedTime[1].split(':'));
    let date = info.event.startStr.split("T")[0];
    $.post({
        url: "api/getBusy",
        type: "post",
        dataType: "json",
        data: {
            "date": date,
            "heureDebut": formattedTime[0],
            "heureFin": formattedTime[1]
        },
        success: function (busySeance) {
            let oldGroupe = info.oldEvent._def.extendedProps.groups;
            let oldTeacher = info.oldEvent._def.extendedProps.teachers;
            let oldClassRoom = info.oldEvent._def.extendedProps.classrooms;
            console.log(busySeance);
            if (busySeance.find(seance => seance.idSeance != info.event.id) && (busySeance.find(seance => seance.idGroupe == oldGroupe) || busySeance.find(seance => seance.idTeacher == oldTeacher) || busySeance.find(seance => seance.idClassRoom == oldClassRoom))) {
                $("#calendar").append('<div id="seance-busy-alert"class="alert alert-danger ">This seance cannot be moved there cause : </div>')
                $("#seance-busy-alert").append('<ul id="seance-busy-list"></ul>')
                for (let seance of busySeance) {
                    if (seance.teacherName == oldTeacher || seance.idGroupe == oldGroupe || seance.idClassRoom == oldClassRoom && seance.idSeance != info.event.id) {
                        $("#seance-busy-list").append(
                            `<li>Teacher ${seance.teacherName} give lessons to  ${seance.idGroupe} in ${seance.idClassRoom} between ${seance.heureDebut} to ${seance.heureFin}</li>`
                        )
                    }
                }
                setTimeout(function () {
                    $("#seance-busy-alert").remove();
                }, 5000);
            }
            else {
                $.post({
                    url: "api/seance/update",
                    data: {
                        idSeance: info.event.id,
                        heureDebut: formattedTime[0],
                        heureFin: formattedTime[1],
                        duree: duree,
                        date: formattedTime[2],
                        eventChange: true,
                    },
                });

            }
        }
    });
}
function onTimerChange(info, str) {
    if (str == "update") {
        $('#start-time').on('change', function () {
            setUpSelect(info, "update")
            console.log("change start time");
        });
        $('#end-time').on('change', function () {
            setUpSelect(info, "update")
            console.log("change end time");
        });
    } else {
        $('#start-time').on('change', function () {
            setUpSelect(info)
            console.log("change start time");
        });
        $('#end-time').on('change', function () {
            setUpSelect(info)
            console.log("change end time");
        });
    }
}
