$("#addLocalType").on('click', e => {
    showLocalType();
})

function showLocalType() {
    $('#typeRoomModal').css("display", "inline-block").addClass('show');
    $('#typeRoomModal').attr('role', 'dialog');
    $('#classroomModal').removeClass('show');
    $("#classroomModal").css("display", "none")
    $('#classroomModal').removeAttr('role');

    $.get("api/types", function (data) {

        while ($('#typeroomModal-table').children().children().length > 1) {
            $('#typeroomModal-table').children().children().eq(1).remove()
        }
        const obj = JSON.parse(data)

        for (let val of obj) {
            $('#typeroomModal-table').append($('<tr>')
                .attr('id', val.id + '-row-typetable')
                .append($('<td>')
                    .text(val.name)
                )
                .append($('<td>')
                    .addClass('text-right')
                    .append($('<div>')
                        .addClass('btn-group')
                        .addClass('group-btn-type')
                        .attr('role', 'group')
                        .attr('aria-label', 'btn-group-modal')
                        .append($('<button>')
                            .attr('type', 'button')
                            .attr('id', val.id + 'btnDeleteType')
                            .attr('onClick', `deleteFromSmallBtnType(${val.id})`)
                            .addClass('btn btn-secondary btn-del-type')
                            .append(`<i class='fa fa-trash text-danger'></i>`)
                        )
                        .append($('<button>')
                            .attr('type', 'button')
                            .attr('id', val.id + 'btnEditType')
                            .attr('onClick', `showSmallForEditType(${val.id})`)
                            .addClass('btn btn-secondary btn-edit-type')
                            .append(`<i class='fas fa-edit text-warning'></i>`)
                        )
                    )
                )
            )
        }
    })
}

// event when close the modal
$('#typeRoomModalClose').on('click', (e) => {
    $('#typeRoomModal').css("display", "none").addClass('hide');
    $('#typeRoomModal').removeAttr('role');
    $("#classroomModal").css("display", "inline-block")
    $('#classroomModal').addClass('show');
    $('#classroomModal').attr('role', 'dialog');
})

// function when edit button is clicked
function showSmallForEditType(id) {
    $(`#rowFormType`).remove();
    $(`#${id}-row-typetable`).after("<tr id='rowFormType'></tr>")
    let text = $(`#${id}-row-typetable`).children().eq(0).text()
    $('#rowFormType').append($('<div>')
        .addClass('col')
        .append($('<form>')
            .append($('<input>')
                .attr('type', 'hidden')
                .attr('name', '_token')
                .attr('value', `{{ crsf_token() }}`)
            )
            .append($('<input>')
                .attr('type', 'hidden')
                .attr('name', 'lastTypeName')
                .attr('id', 'hiddenLastTypeName')
                .attr('value', `${text}`)
            )
            .append($('<input>')
                .attr('type', 'hidden')
                .attr('name', 'lastTypeName')
                .attr('id', 'hiddenIdLastTypeName')
                .attr('value', `${id}`)
            )
            .append($('<label>')
                .attr('for', 'name')
                .attr('id', 'formLabelNameType')
                .append('Name ')
            )
            .append($('<input>')
                .attr('type', 'text')
                .attr('id', 'inputLableNameType')
                .attr('name', 'name')
                .attr('value', `${text}`)
            )
            .append($('<button>')
                .attr('id', 'btnSmallEditType')
                .addClass('btn btn-success')
                .attr('type', 'button')
                .attr('onClick', 'smallUpdateType()')
                .append('Update')
            )
            .append($('<button>')
                .attr('id', 'btnSmallCloseType')
                .addClass('btn btn-danger')
                .attr('type', 'button')
                .attr('onClick', 'closeSmallEdit()')
                .append('Close')
            )
        )
    )
}

function smallUpdateType() {
    let name = $("#inputLableNameType").val()
    let id = $('#hiddenIdLastTypeName').val()

    $.ajax({
        url: `/api/types/`,
        type: 'PUT',
        data: {
            'id': id,
            'name': name
        },
        success: function (data, status) {
            $('#inputLableNameType').removeClass('inputCourseError')
            let lastName = $('#hiddenLastTypeName').val()

            let cpt = 1
            while ($('#typeroomModal-table').children().children().length > cpt) {
                // console.log($('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text())
                let txt = $('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text()
                console.log(txt)
                console.log(lastName)
                if(txt == lastName) {
                    $('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text(name)
                    break;
                }
                cpt++
            }
        }
    }).fail(function (response) {
        $('#typeroomName').addClass('inputCourseError')
    });
}

function closeSmallEdit() {
    $(`#rowFormType`).remove();
}

function deleteFromSmallBtnType(id) {

    let name = $('#' + id + '-row-typetable').children().eq(0).text()

    $.ajax({
        url: `/api/types/`,
        type: 'DELETE',
        data: {
            'name': name
        },
        success: function (data, status) {

            let cpt = 1
            while ($('#typeroomModal-table').children().children().length > cpt) {
                // console.log($('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text())
                let txt = $('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text()
                if(txt == name) {
                    $('#typeroomModal-table').children().children().eq(cpt).remove()
                    break;
                }
                cpt++
            }
        }
    }).fail(function (response) {
        //$('#typeroomName').addClass('inputCourseError')
    });
}

$('#deleteTyperoomButton').on('click', (e) => {
    let name = $('#typeroomName').val();

    $.ajax({
        url: `/api/types/`,
        type: 'DELETE',
        data: {
            'name': name
        },
        success: function (data, status) {
            $('#typeroomName').removeClass('inputCourseError')

            let cpt = 1
            while ($('#typeroomModal-table').children().children().length > cpt) {
                console.log($('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text())
                let txt = $('#typeroomModal-table').children().children().eq(cpt).children().eq(0).text()
                if(txt == name) {
                    $('#typeroomModal-table').children().children().eq(cpt).remove()
                    break;
                }
                cpt++
            }
        }
    }).fail(function (response) {
        $('#typeroomName').addClass('inputCourseError')
    });
});


$('#addTyperoomButton').on('click', (e) => {
    let name = $('#typeroomName').val()
    console.log(name)

    $.ajax({
        url: `/api/types/`,
        type: 'POST',
        data: {
            'name': name
        },
        success: function (data, status) {
            $('#typeroomName').removeClass('inputCourseError')

            $('#typeroomModal-table').append($('<tr>')
                .attr('id', data + '-row-typetable')
                .append($('<td>')
                    .text(name)
                )
                .append($('<td>')
                    .addClass('text-right')
                    .append($('<div>')
                        .addClass('btn-group')
                        .addClass('group-btn-type')
                        .attr('role', 'group')
                        .attr('aria-label', 'btn-group-modal')
                        .append($('<button>')
                            .attr('type', 'button')
                            .attr('id', 'btnDeleteType')
                            .attr('value', data)
                            .attr('onClick', `hahaha()`)
                            .addClass('btn btn-secondary btn-del-type')
                            .append(`<i class='fa fa-trash text-danger'></i>`)
                        )
                        .append($('<button>')
                            .attr('type', 'button')
                            .attr('id', data + 'btnEditType')
                            .attr('onClick', `showSmallForEditType(${data})`)
                            .addClass('btn btn-secondary btn-edit-type')
                            .append(`<i class='fas fa-edit text-warning'></i>`)
                        )
                    )
                )
            )
        }
    }).fail(function (response) {
        $('#typeroomName').addClass('inputCourseError')
    });
});