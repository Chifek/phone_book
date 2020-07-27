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
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.jqueryui.min.js"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
        $(document).ready(function () {
            //Register user
            $("#add").click(function (e) {
                if ($("#form-data")[0].checkValidity()) {
                    e.preventDefault();
                    $.ajax({
                        url: "action.php",
                        type: "POST",
                        data: $("#form-data").serialize() + "&action=reg",
                        success: function (response) {
                            if (response == 'Success') {
                                window.location.href = "/auth.php";
                            } else {
                                $("#error").html(response);
                            }
                        }
                    })
                }
            })
        })
    </script>

    <title>Registration to phone book!</title>
</head>
<body>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div class="wrapper fadeInDown">
    <div id="formContent">
        <div class="fadeIn first">
            <img src="https://image.flaticon.com/icons/png/128/753/753210.png" id="icon" alt="User Icon"/>
        </div>
        <form action="" method="post" id="form-data">
            <input type="text" id="login" class="fadeIn second" name="login" placeholder="login" required>
            <input type="text" id="email" class="fadeIn second" name="email" placeholder="email" required>
            <input type="text" id="password" class="fadeIn third" name="password" placeholder="password" required>
            <input type="text" id="re_password" class="fadeIn third" name="re_password" placeholder="re enter password"
                   required>
            <p id="error" style="color:red"></p>
            <div style="padding-left: 70px;">
                <div class="g-recaptcha" data-sitekey="6LfuBrcZAAAAAA4HkwF-W9hpsrF-dtmJpvrNyER3"></div>
            </div>
            <input type="submit" class="fadeIn fourth" name="add" id="add" value="Register">
        </form>
        <div id="formFooter">
            <a class="underlineHover" href="/auth.php">Login</a>
        </div>

    </div>
</div>
</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 26.07.20
 * Time: 13:59
 */