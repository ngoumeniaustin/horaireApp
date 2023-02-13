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


    <form method="post">
        @csrf

        <select name="typeChoose" id="typeChoose">
        </select>

        <input type="text" name="name" id="name">


        <button type="submit" id="modif">Modifier</button>
        <button type="submit" id="del">Supprimer</button>




    </form>



    <div class="result">

    </div>

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
                        }
                    }

                },
                error: function(error) {
                    console.log(error);
                }
            });



            $('#modif').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "PUT",
                    url: "{{ route('types.update') }}",
                    data: {
                        id: $('#typeChoose').val(),
                        name: $('#name').val()

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



            $("#del").click(
                function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('types.destroy') }}",
                        data: {
                            id: $('#typeChoose').val(),
                        },
                        success: function(response) {


                            $(".result").append(
                                $("<p class='success'>").text("Type de local supprimé avec succès")
                            );

                        },
                        error: function(error) {
                            console.log(error);
                            $(".result").append(
                                $("<p class='fail'>").text("Erreur lors de la suppression du type de local")
                            );
                        }
                    });
                }
            );

        });
    </script>



</body>

</html>