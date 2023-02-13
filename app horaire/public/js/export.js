// Get data from the user input to create an export file
// ics is a boolean: if it's true, it's for ics export and it's false it's for json export
function exportFile(ics) {
    $("#messageForUser").empty();
    let dateDebut = document.getElementById("start-date").value;
    let dateFin = document.getElementById("end-date").value;
    let categorie = $("#typeSelectExport").find(":selected").val();
    // get multiple choices of the user.
    let choices = $("#typeValuesExport").val();
    // check if there are an empty value
    if (!empty(dateDebut) && !empty(dateFin) && !empty(categorie) && !empty(choices)) {
        let choice;
        // if there are multiple value for the choices, create a file for each of them
        for ($index = 0; $index < choices.length; $index++) {
            choice = choices[$index];
            //for ics export.
            if (ics) {
                createEventAndDownload(dateDebut, dateFin, categorie, choice);
            } else {
                // for json file.
                funSubmitExport(dateDebut, dateFin, categorie, choice);
            }

        }

        // if there are missing parameters, display an error.
    } else if (empty(dateDebut)) {
        $("#messageForUser").append(
            ` <div class ="alert alert-danger user-error">Missing parameter, please indicate a start time</div>`
        );
    } else if (empty(dateFin)) {
        $("#messageForUser").append(
            ` <div class ="alert alert-danger user-error">Missing parameter, please indicate an end time</div>`
        );
    } else if (empty(categorie)) {
        $("#messageForUser").append(
            ` <div class ="alert alert-danger user-error">Missing parameter, please make a choice between the different categories</div>`
        );
    } else if (empty(choices)) {
        $("#messageForUser").append(
            ` <div class ="alert alert-danger user-error">Missing parameter, please make a choice between the different ` + categorie + `</div>`
        );
    }
}

// create events and download it in a ics file.
function createEventAndDownload(dateDebut, dateFin, categorie, choice) {
    $.post(
        "/api/getSeancesbetweenTwoDateIcs",
        {
            dateDebut: dateDebut,
            dateFin: dateFin,
            categorie: categorie,
            choice: choice,
        },
        function (data, status) {
            // check if the request is succesfull otherwise, display error message
            if (status == "success") {
                // enter if there are corresponding data in the database.
                if (data.length != 0) {
                    cal = ics();
                    if (categorie == 'Teachers') {
                        for ($i = 0; $i < data.length; $i++) {
                            let sjt = data[$i].CoursAcronyme + " - " + data[$i].Groupe + " - " + data[$i].Local;
                            let description = "Course: " + data[$i].Cours + " - Group: " + data[$i].Groupe + " - Local: " + data[$i].Local + " - Teacher: " + data[$i].TeacherLastName + " " + data[$i].TeacherFirstName;
                            let local = "Local: " + data[$i].Local;
                            let start = data[$i].date + " " + data[$i].heureDebut;
                            let end = data[$i].date + " " + data[$i].heureFin;
                            // create an event for the calendar
                            cal.addEvent(sjt, description, local, start, end);
                        }

                    } else if (categorie == 'Groups') {
                        for ($i = 0; $i < data.length; $i++) {
                            let sjt = data[$i].CoursAcronyme + " - " + data[$i].TeacherAcronym + " - " + data[$i].Local;
                            let description = "Course: " + data[$i].Cours + " - Teacher: " + data[$i].TeacherLastName + " " + data[$i].TeacherFirstName + " - Local: " + data[$i].Local + " - Group: " + data[$i].Groupe;
                            let local = "Local: " + data[$i].Local;
                            let start = data[$i].date + " " + data[$i].heureDebut;
                            let end = data[$i].date + " " + data[$i].heureFin;
                            // create an event for the calendar
                            cal.addEvent(sjt, description, local, start, end);
                        }

                    } else if (categorie == 'Locals') {
                        for ($i = 0; $i < data.length; $i++) {
                            let sjt = data[$i].CoursAcronyme + " - " + data[$i].TeacherAcronym + " - " + data[$i].Groupe;
                            let description = "Course: " + data[$i].Cours + " - Group: " + data[$i].Groupe + " - Local: " + data[$i].Local + " - Teacher: " + data[$i].TeacherLastName + " " + data[$i].TeacherFirstName;
                            let local = "Local: " + data[$i].Local;
                            let start = data[$i].date + " " + data[$i].heureDebut;
                            let end = data[$i].date + " " + data[$i].heureFin;
                            // create an event for the calendar
                            cal.addEvent(sjt, description, local, start, end);
                        }
                    }

                    // download file
                    cal.download('export_' + categorie + "_" + choice);
                    $("#messageForUser").append(
                        ` <div class="alert alert-success" role="alert">Successful ics export for `+categorie+" "+choice+" !"+`</div>`
                    );
                } else {
                    $("#messageForUser").append(
                        ` <div class ="alert alert-danger user-error">` + categorie + " " + choice + " " + ` has nothing to export</div>`
                    );

                }

            } else {
                $("#messageForUser").append(
                    ` <div class ="alert alert-danger user-error">Ics export fail for `+categorie+" "+choice+" !"+`</div>`
                );
            }

        }
    );
}

// Check if a var is empty or null or undefined
function empty(data) {
    if (typeof (data) == 'number' || typeof (data) == 'boolean') {
        return false;
    }
    if (typeof (data) == 'undefined' || data === null) {
        return true;
    }
    if (typeof (data.length) != 'undefined') {
        return data.length == 0;
    }
    var count = 0;
    for (var i in data) {
        if (data.hasOwnProperty(i)) {
            count++;
        }
    }
    return count == 0;
}

// Use to debug and see the code of the ics file.
makelogs = function (obj) {
    console.log('Events Array');
    console.log('=================');
    console.log(obj.events());
    console.log('Calendar With Header');
    console.log('=================');
    console.log(obj.calendar());
}

//get the selected elements to export
function funSubmitExport(dateDebut, dateFin, categorie, choice) {
    $.post(
        "/api/getSeancesbetweenTwoDate",
        {
            dateDebut: dateDebut,
            dateFin: dateFin,
            categorie: categorie,
            choice: choice,
        },
        function (data, status) {
            if (status == "success") {
                let filename = "export_" + categorie + "_" + choice + ".json";
                downloadJson(filename, data);
                $("#messageForUser").append(
                    ` <div class="alert alert-success" role="alert">Successful Json export for `+categorie+" "+choice+" !"+`</div>`
                );
            } else {
                $("#messageForUser").append(
                    ` <div class ="alert alert-danger user-error">Json export fail for `+categorie+" "+choice+" !"+`</div>`
                );
            }
            //avec sa on serait en asynchrone.
        }
    );
}

function downloadJson(filename, text) {
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


