<?php
    include("./Common/header.php");


    include_once "Functions.php";
    include_once "EntityClassLib.php";
    session_start();

    extract($_POST);
    $StudentIdEMessage = "";
    $PasswdEMessage = "";
    $EMessage = "";
    $errorflag = false;

    if(isset($Submit)){

        // Validation Check
        //*******************************
        if($StudentId == "") {
            $errorflag = true;
            $StudentIdEMessage = "Student ID cannot be blank.";
        }

        if($Passwd == "") {
            $errorflag = true;
            $PasswdEMessage = "Password cannot be blank.";
        }

        if(!$errorflag){
            // check the database
            try {
                // Lab6 : edited for using hashed passwords by miku 2022.11.15
                $hashed_Passwd = hash("sha256", $Passwd);
                $student = getStudentByIdAndPassword($StudentId, $hashed_Passwd);
                var_dump($StudentId);
                var_dump($Passwd);
                var_dump($student);

            } catch (Exception $ex) {
                die("The system is currently not available, try again later");
            }

            if($student == null) {
                $EMessage = 'Incorrect User ID and Password Combination!';
            }else {
                $_SESSION['student'] = serialize($student);
                header("Location: CourseSelection.php");
                exit();
            }


        }


    }


?>
<div class="container">
    <h1>Log In</h1>
    <hr class="solid">
    <p>you need to <a href="NewUser.php">sign up</a> if you are a new user.</p>
    <p class='text-danger'>
        <?php
        echo $EMessage;
        ?>
    </p>
    <!--  Input Form -->
    <form method="post" action="Login.php">
        <div class="form-group row">
           <label for="StudentId" class="control-label col-sm-4">Student ID:</label>
            <div class="col-sm-4">
                <input type="text" id="StudentId" name="StudentId" class="form-control" autocomplete="off" value="<?php echo $StudentId;?>"/>
            </div>
            <div class="col-sm-4">
                <p class='text-danger'>
                    <?php
                    echo $StudentIdEMessage;
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group row">
           <label for="Passwd" class="control-label col-sm-4">Password:</label>
            <div class="col-sm-4">
                <input type="password" id="Passwd" name="Passwd" class="form-control" autocomplete="off" value="<?php echo $Passwd;?>"/>
            </div>
            <div class="col-sm-4">
                <p class='text-danger'>
                    <?php
                    echo $PasswdEMessage;
                    ?>
                </p>
            </div>
        </div>

        <!-- A submit & clear buttons -->
        <div class="form-group row">
            <div class="col-sm-2 m-4">
                <input type = "submit" value = "Submit" class="btn btn-primary" name="Submit" />
            </div>
            <div class="col-sm-2 m-4">
                <input type = "reset" value = "Clear" class="btn btn-primary" name="Clear" />
            </div>
        </div>

    </form>
</div>
<?php include('./Common/footer.php'); ?>
