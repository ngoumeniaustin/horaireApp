document.getElementById('uploadconfirm').addEventListener('click', () => {
    Papa.parse(document.getElementById('uploadfile').files[0], {
        dowload: true,
        header: true,
        skipEmptyLines: true,
        complete: function (results) {
            //console.log(results);
            console.log("before post")
            $.post(
                "/api/createTeacherWithArray", {
                    result: results,
                },
                function (data, status) {
                    console.log(results)
                    console.log("")
                    console.log(status);
                    if (status == "success") {
                        console.log("successsssss")
                        console.log(data);                        
                        document.getElementById("successMessageToDisplay").style.visibility = 'visible'
                        window.setTimeout(() => {
                            console.log("AFTER WAITED")
                            document.getElementById("successMessageToDisplay").style.visibility = 'hidden'
                        }, 3000);
                        //download("export.json", data);
                    } else {
                        console.log("not success");
                    }
                   
                }
            );
        }
    })
})

function changeDisabled() {
    document.getElementById('uploadconfirm').removeAttribute('disabled');
    document.getElementById('uploadconfirm').style.backgroundColor = "#0d6efd";
}
