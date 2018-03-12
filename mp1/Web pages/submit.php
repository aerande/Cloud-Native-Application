<?php session_start(); ?>

<!DOCTYPE html>
<html>
        <head>
                <title>Submit Page</title>
                <meta charset="utf-8">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
                <style>
                        body {
                                background-image: url("aws_back.jpg");
                                background-repeat: no-repeat;
                        }
                        .first_div{
                                position: relative;
                                height: 190px;
                                text-align: center;
                                padding-top: 70px;
                        }
                        .second_div{
                                padding-bottom: 30px;
                        }
                        a{
                                margin-left: 65px;
                                margin-right: 65px;
                        }
                </style>
        </head>
        <body>
                <div class="first_div">
                        <a href="index.php" class="btn btn-primary">Home</a>
                        <a href="gallery.php" class="btn btn-primary">Gallery</a>
                        <a href="submit.php" class="btn btn-primary">Submit</a>
                </div>
                <div class="second_div">
                        <h1 align="center">Submit your details</h1>
                </div>
                <div>
                        <form enctype="multipart/form-data" action="insert.php" method="POST">
                                <table align="center">
                                        <tr>
                                                <td>Email: </td>
                                                <td><input type="email" name="email" placeholder="Enter your email"/></td>
                                        </tr>
                                        <tr>
                                                <td>Phone: </td>
                                                <td><input type="phone" name="phone" placeholder="Enter your phone"/></td>
                                        </tr>
                                        <tr>
                                                <td><input type="file" name="photo"/></td>
                                        </tr>
                                        <tr>
                                                <td><input type="submit" name="submit" value="Submit"/></td>
                                                <td><input type="reset" name="reset" value="Reset"/></td>
                                        </tr>
                                </table>
                        </form>
                </div>
        </body>
</html>