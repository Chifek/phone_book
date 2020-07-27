<?php
session_start();
if ($_SESSION["auth"] === null && $_SESSION['user_id'] === null) {
    header("Location: /auth.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Ismailov Ruslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Phone book for everyone">
    <meta name="keywords" content="PHP, AJAX, BOOTSTRAP, HTML, CSS, MYSQL">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.jqueryui.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.jqueryui.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
        $(document).ready(function () {

            showAllRecords();

            function showAllRecords() {
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    data: {action: "view"},
                    success: function (response) {
                        $("#showPersons").html(response);
                        $("#table").DataTable();
                    }
                });
            }

            //Add person
            $("#add").click(function (e) {
                e.preventDefault();
                let formData = new FormData();
                formData.append('action', 'add');
                formData.append('first_name', $('#first_name').val());
                formData.append('last_name', $('#last_name').val());
                formData.append('phone_number', $('#phone_number').val());
                formData.append('file', $('#file')[0].files[0]);
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (response) {
                        $("#myModalAdd").modal('hide');
                        $("#form-data")[0].reset();
                        showAllRecords();
                    }
                })
            })

            //Open edit modal
            $("body").on("click", ".editBtn", function (e) {
                e.preventDefault();
                let edit_id = $(this).attr('id');
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    data: {edit_id: edit_id},
                    success: function (response) {
                        let data = JSON.parse(response)
                        $("#path").remove();
                        $("#e_first_name").val(data.first_name)
                        $("#e_last_name").val(data.last_name)
                        $("#e_phone_number").val(data.phone)
                        $('#img').append("<img src=\"" + data.path + "\" class=\"img-circle\" id=\"path\" style=\"width: 100%\">");
                        $("#e_id").val(data.id)
                    }
                })
            });

            //Update person
            $("#update").click(function (e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('action', 'update');
                formData.append('first_name', $('#e_first_name').val());
                formData.append('id', $('#e_id').val());
                formData.append('last_name', $('#e_last_name').val());
                formData.append('phone_number', $('#e_phone_number').val());
                formData.append('file', $('#e_file')[0].files[0]);

                $.ajax({
                    url: "action.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    // data: $("#edit-form-data").serialize() + "&action=update",
                    success: function (response) {
                        $("#myModalEdit").modal('hide');
                        $("#edit-form-data")[0].reset();
                        showAllRecords();
                    }
                })
            })

            // Delete person
            $("body").on("click", ".delBtn", function (e) {
                e.preventDefault();
                let tr = $(this).closest('tr')
                del_id = $(this).attr('id');
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    data: {del_id: del_id},
                    success: function (response) {
                        tr.css('background-color', '#ff6666');
                        showAllRecords();
                    }
                })
            });

            //Open view modal
            $("body").on("click", ".viewBtn", function (e) {
                e.preventDefault();
                let view_id = $(this).attr('id');
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    data: {view_id: view_id},
                    success: function (response) {
                        $("#table1 tr").remove();
                        $("#path").remove();
                        let data = JSON.parse(response);
                        $('#table1').append("<tr class=\"text-center\"><td>" + data.first_name + "</td><td>" + data.last_name + "</td><td>" + data.phone + "</td><td>" + data.textOfNumbers + "</td></tr>");
                        $('#view_image').append("<img src=\"" + data.path + "\" class=\"img-circle\" id=\"path\" style=\"width: 100%\">");
                    }
                })
            });

            // //Open view modal
            // $("body").on("click", ".viewBtn", function (e) {
            //     e.preventDefault();
            //     let view_id = $(this).attr('id');
            //     $.ajax({
            //         url: "action.php",
            //         type: "POST",
            //         data: {view_id: view_id},
            //         success: function (response) {
            //             $("#table1 tr").remove();
            //             let data = JSON.parse(response)
            //             $('#table1').append("<tr class=\"text-center\"><td>" + data.first_name + "</td><td>" + data.last_name + "</td><td>" + data.phone + "</td><td>" + data.textOfNumbers + "</td></tr>");
            //         }
            //     })
            // });

            //Logout
            $("#logout").click(function (e) {
                $.ajax({
                    url: 'action.php',
                    type: 'get',
                    data: {action: 'logout'},
                    success: function (response) {
                        location.reload();
                    }
                });
            })
        });
    </script>
    <title>Welcome to phone book!</title>
</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="/"><?php session_start();
        if ($_SESSION["auth"] && $_SESSION['user_id'] !== null && $_SESSION['user_name'] !== null) {
            echo $_SESSION["user_name"];
        }
        ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="" id="logout">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h4 class="text-center font-weight-normal">Welcome</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php session_start();
            if ($_SESSION["auth"] && $_SESSION['user_id'] !== null && $_SESSION['user_name'] !== null) {
                echo $_SESSION["user_name"];
            }
            ?>
        </div>
        <div class="col-lg-6">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModalAdd">add
                new
                person
            </button>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive" id="showPersons">
            </div>
        </div>
    </div>
</div>

<!-- The Add Modal -->
<div class="modal fade" id="myModalAdd">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add new person</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="" method="post" id="form-data" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" name="first_name" id="first_name" class="form-control"
                               placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last name"
                               required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone_number" id="phone_number" class="form-control"
                               placeholder="Phone number" required>
                    </div>
                    <div class="form-group">
                        <input id="file" type="file" name="file">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="add" id="add" class="btn btn-block btn-success"
                               value="Add new person"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Edit Modal -->
<div class="modal fade" id="myModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit person</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="" method="post" id="edit-form-data">
                    <input type="hidden" name="e_id" id="e_id">
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" id="e_first_name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" id="e_last_name" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone_number" class="form-control" id="e_phone_number" required>
                    </div>
                    <div class="form-group">
                        <input id="e_file" type="file" name="e_file">
                    </div>
                    <div class="form-group" id="img">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="update" id="update" class="btn btn-block btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The View Modal -->
<div class="modal fade" id="myModalView">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">View person</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr class="text-center">
                        <th>first name</th>
                        <th>last name</th>
                        <th>phone number</th>
                        <th>phone text number</th>
                    </tr>
                    </thead>
                    <tbody id="table1">
                    </tbody>
                </table>
                <div class="form-group" id="view_image">
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
