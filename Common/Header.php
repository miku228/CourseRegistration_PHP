<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
    session_start();
    if(isset($_SESSION["student"])){
        $student = $_SESSION["student"];
    } else {
        $student = null;
    }
?>
<html lang="en" style="position: relative; min-height: 100%;">
    <head>
        <meta charset="UTF-8">
        <title>Algonquin Online Registration - CST8257 Lab6</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.6/dist/css/bootstrap.min.css">
    </head>
    <body style="padding-top: 50px; margin-bottom: 60px;">
            <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
                <div class="container-fluid row col-lg-12">
                            <div class="navbar-header">
                                <!-- Collapse button -->
                                <button class="navbar-toggle" data-target="#mobile_menu" data-toggle="collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a href="index.php" class="navbar-brand">Algonquin Online Registration - CST8257 Lab6</a>
                            </div>
                            <!-- Collapse button -->
                            <div class="navbar-collapse collapse" id="mobile_menu">
                                <ul class="nav navbar-nav">
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="CourseSelection.php">Course Selection</a></li>
                                    <li><a href="CurrentRegistration.php">Current Registration</a></li>
                                    <?php
                                        if($student) {
                                            echo '<li><a href="Logout.php">Logout</a></li>';
                                        }else{
                                            echo '<li><a href="Login.php">Login</a></li>';
                                        }
                                    ?>
                                    
                                    
                                </ul>
                            </div>
                </div>
            </nav>
    <!-- 
    </body>
</html>
    -->
