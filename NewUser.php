<?php 
    include("./Common/header.php");
    
    include_once "Functions.php";
    include_once "EntityClassLib.php"; 	
    session_start();
    
    // Get all post data
    extract($_POST);
    // Error messages
    $StudentIdEMessage = "";
    $NameEMessage = "";
    $pNumberEMessage = "";
    $PasswdEMessage = "";
    $PasswdAgainEMessage = "";
    $errorflag = false;
    
    //*******************************
    //***** validation functions*****
    //*******************************
    
    // Phone Regular Expression Check
    function ValidatePhone($phone) {
        $phoneNumberRegex = "/^[1-9]\d{2}-[1-9]\d{2}-\d{4}\z/";
        if(!preg_match($phoneNumberRegex, $phone)){
            global $errorflag;
            $errorflag = true;
            return "Invalid Phone Number Format";
        }
    } 
    
    //Password Regular Expression Check
    function ValidatePassword($pwd) {
        $pwdrRegex = "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{6}/";
        if(!preg_match($pwdrRegex, $pwd)){
            global $errorflag;
            $errorflag = true;
            return "Invalid Password. Password must be at least 6 characters long, contains at least one upper case, one lowercase and one digit.";
        }
    }
    
    
    if(isset($Submit)){
        $errorflag = false;
        //*******************************
        // Validation Check
        
        if($StudentId == "") {
            $errorflag = true;
            $StudentIdEMessage = "Student ID cannot be blank.";  
        }
        
        if($Name == "") {
            $errorflag = true;
            $NameEMessage = "Name cannot be blank.";  
        }
        
        if($pNumber == "") {
            $errorflag = true;
            $pNumberEMessage = "Phone Number cannot be blank.";  
        }else{
            $pNumberEMessage = ValidatePhone($pNumber);  
        }
        
        if($Passwd == "") {
            $errorflag = true;
            $PasswdEMessage = "Password cannot be blank.";  
        }else{
            $PasswdEMessage = ValidatePassword($Passwd);  
        }
        if($PasswdAgain == "") {
            $errorflag = true;
            $PasswdAgainEMessage = "Password Again cannot be blank.";  
        }else{
            $PasswdAgainEMessage = ValidatePassword($PasswdAgain);
            if($Passwd != $PasswdAgain){
              $errorflag = true;
              $PasswdAgainEMessage = "Password Again doesn't match with Password.";    
            }
        }
        //*******************************
        
        if(!$errorflag){
            // set to the database
            try {
                // Lab6 : edited for using hashed passwords by miku 2022.11.15
                $hashed_Passwd = hash("sha256", $Passwd);
                
                // Lab6 : edited for using hashed passwords by miku 2022.11.15
                $student = getStudentByIdAndPassword($StudentId, $hashed_Passwd);
               
                if(!isset($student)) {
                    try {
                        // Lab6 : edited for using hashed passwords by miku 2022.11.15
                        addNewUser($StudentId, $Name, $pNumber, $hashed_Passwd);
                        // header("Location: CourseSelection.php");
                        header("Location: Login.php");
                        exit();
                        
                    } catch (Exception $ex) {
                        die("The system is currently not available, try again later");
                    } 
                } else {
                    // $student is already registered to the database
                    $StudentIdEMessage = "A student with this ID has already signed up.";
                    
                }
            } catch (Exception $ex) {
                die("The system is currently not available, try again later");
            }
            
        }
        
        
    }
    
?>
<div class="container">
    <h1>Sign Up</h1>
    <hr class="solid">
    
    <!-- <form method="post" action="CourseSelection.php"> -->
    <form method="post">
        <div class="form-group row">
            <p>All fields are required.</p>
        </div>
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
            <label for="Name" class="control-label col-sm-4">Name:</label>
            <div class="col-sm-4">
                <input type="text" id="Name" name="Name" class="form-control" autocomplete="off" value="<?php echo $Name;?>"/>
            </div>
            <div class="col-sm-4">
                <p class='text-danger'>
                    <?php
                    echo $NameEMessage;
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group row">
            <label for="pNumber" class="control-label col-sm-4">Phone Number:</br>(nnn-nnn-nnnn)</label>
            <div class="col-sm-4">
                <input type="text" id="pNumber" name="pNumber" class="form-control" autocomplete="off" value="<?php echo $pNumber;?>"/>
            </div>
            <div class="col-sm-4">
                <p class='text-danger'>
                    <?php
                    echo $pNumberEMessage;
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
        <div class="form-group row">
            <label for="PasswdAgain" class="control-label col-sm-4">Password Again:</label>
            <div class="col-sm-4">
                <input type="password" id="PasswdAgain" name="PasswdAgain" class="form-control" autocomplete="off" value="<?php echo $PasswdAgain;?>"/>
            </div>
            <div class="col-sm-4">
                <p class='text-danger'>
                    <?php
                    echo $PasswdAgainEMessage;
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
