<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <p>{{ route('types.destroy', ['type' => "5"]) }}</p>

    <fieldset>
        <legend>Ajouter Type</legend>
        <form method="post">
            @csrf

            <input type="text" name="nameC" id="nameC">
            <input type="submit" id="create" value="Créer">
        </form>
    </fieldset>


    <fieldset>
        <legend>Modifer Type</legend>

        <form method="put">
            @csrf

            <select name="typeChoose" id="typeChoose">
            </select>

            <input type="text" name="nameM" id="nameM">


            <button type="submit" id="modif">Modifier</button>


        </form>
    </fieldset>


    <div class="result">
    </div>


    <table>
        <thead>
            <tr>
                <td>Name</td>
                <td>Supprimer</td>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "{{ route('types.index') }}",
                success: function(response) {
                    console.log(response);
                    var tp = JSON.parse(response);

                    if (tp.length == 0) {
                        $("#typeChoose").append(
                            $("<option>").text("Aucun type de local")
                        );
                    } else {

                        for (var i = 0; i < tp.length; i++) {
                            $("#typeChoose").append(
                                $("<option>").attr('value', tp[i].id).text(tp[i].name)
                            );

                            $("tbody").append(
                                $("<tr>").append(
                                    $("<td>")
                                    .text(tp[i].name),
                                    $("<td>").append(
                                        $("<button>")
                                        .attr("class", "delBtn")
                                        .attr("id", tp[i].id)
                                        .text("X")
                                    )
                                )
                            );
                        }
                    }

                },
                error: function(error) {
                    console.log(error);
                }
            });

            $("#create").click(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('types.store') }}",
                    data: {
                        name: $('#nameC').val()
                    },

                    success: function(response) {
                        $(".result").append(
                            $("<p class='success'>").text("Type de local modifié avec succès")
                        );
                    },
                    error: function(error) {
                        $(".result").append(
                            $("<p class='fail'>").text("Erreur lors de la modification du type de local")
                        );
                    }

                });
            });


            $('#modif').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "PUT",
                    url: "{{ route('types.update', ['type' => 'chat']) }}",
                    data: {
                        id: $('#typeChoose').val(),
                        name: $('#nameM').val()

                    },
                    success: function(response) {
                        $(".result").append(
                            $("<p class='success'>").text("Type de local modifié avec succès")
                        );
                    },
                    error: function(error) {
                        $(".result").append(
                            $("<p class='fail'>").text("Erreur lors de la modification du type de local")
                        );
                    }
                });
            });
        });
    </script>



</body>

</html>