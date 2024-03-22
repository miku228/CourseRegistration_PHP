<?php 
    include("./Common/header.php");
    
    include_once "Functions.php";
    include_once "EntityClassLib.php";
    session_start();
    
    extract($_POST);
    $errorMessage = "";
    
    // ******************
    // *** functions ****
    // ******************
    function getModifiedRegisteredCourse($aCourses, $rCourses) {
        $courses = [];
        if(isset($rCourses)) {
           foreach ($rCourses as $key => $rc){
                if($key === 0){
                    global $courses;
                    $courses = array_filter($aCourses, function($value) use ($rc) {
                            return $value->getCourseCode() !== $rc->getCourseCode();
                            }); 
                } else {
                   global $courses;
                   $courses = array_filter($courses, function($value) use ($rc) {
                            return $value->getCourseCode() !== $rc->getCourseCode();
                            }); 
                }   
           }
           return $courses; 
        }else{
            return $aCourses;
        }
        
    }
    
    // ******************
    // ******************
    // ******************
    
    if($_SESSION['student']){
        $totalSelectedWeeklyHours = 0;
        $totalRegisteredWeeklyHours = 0;
        $student = unserialize($_SESSION['student']);
        $name = $student->getName();
        $keepselection = false;
        
        // ** Get data from database
        $availableSemester = getAvailableSemester();
        
        // ** When semester is selected
        if (isset($_POST['getAvailableCouses'])) {
            $_SESSION['selectedCourses'] = null;
            if (!empty($selectedSemester)) {
                $_SESSION['selectedSemester'] = $selectedSemester;  
            } 
        }
        
        // ** Get SelectedSemester
        if($_SESSION['selectedSemester']) {
            $selectedSemester = $_SESSION['selectedSemester'];
        } else {
            $selectedSemester = "22F";
        }
        // ** Get data from database
        $availableCourses = getAvailableCourses($selectedSemester);
        $registeredCourses = getRegisterdCourseByStudentIdandSemesterId($student->getStudentId(), $selectedSemester);
        
        // ** Set display courses
        $modifiedAvailableCourses = getModifiedRegisteredCourse($availableCourses, $registeredCourses);
        
        // ** Calculate Registered WeeklyHours
        if(isset($registeredCourses)) {
            foreach($registeredCourses as $rc) {
                $totalRegisteredWeeklyHours += (int)$rc->getWeeklyHours();
            }
        }
        
        // ** When Submit button is clicked - Courses are registered
        if(isset($_POST['registerCouses'])) {
            
            // *** Get data from database
            $availableCourses = getAvailableCourses($selectedSemester);
            $registeredCourses = getRegisterdCourseByStudentIdandSemesterId($student->getStudentId(), $selectedSemester);
            
            $modifiedAvailableCourses = getModifiedRegisteredCourse($availableCourses, $registeredCourses);
            
            if(!isset($selectedCourses)){
                $errorMessage = 'You need to select at least one course!';
            } else {
                // *** Get total hours from selected Courses 
                // *** Calculate selected course hours
                foreach ($selectedCourses as $selectedCourseCode) {
                    foreach ($availableCourses as $ac) {
                       if($ac->getCourseCode() == $selectedCourseCode)  {
                           $totalSelectedWeeklyHours += (int)$ac->getWeeklyHours();
                        }
                     }
                }
                
                // *** When the total number of weekly hours of the registered 
                // *** & selected courses for the semester does not exceed 16 hours.
                if(($totalRegisteredWeeklyHours + $totalSelectedWeeklyHours) > 16) {
                  $_SESSION['selectedCourses'] = $selectedCourses;
                  $keepselection = true;
                  $errorMessage = 'Your selection exceed the max weekly hours!';
                } else {
                    // ** Register Selected Courses
                    foreach ($selectedCourses as $scc) {
                        addNewRegistration($student->getStudentId() , $scc, $selectedSemester);
                        // $availableCourses = getAvailableCourses($selectedSemester);
                        $registeredCourses = getRegisterdCourseByStudentIdandSemesterId($student->getStudentId(), $selectedSemester);
                        // *** Set display courses
                        // $modifiedAvailableCourses = getModifiedRegisteredCourse($availableCourses, $registeredCourses);
                        $totalRegisteredWeeklyHours = 0;
                        if(isset($registeredCourses)) {
                            foreach($registeredCourses as $rc) {
                                $totalRegisteredWeeklyHours += (int)$rc->getWeeklyHours();
                            }
                        }

                     }
                     
                    $availableCourses = getAvailableCourses($selectedSemester);
                    $registeredCourses = getRegisterdCourseByStudentIdandSemesterId($student->getStudentId(), $selectedSemester);
                    // *** Set display courses
                    $modifiedAvailableCourses = getModifiedRegisteredCourse($availableCourses, $registeredCourses);

                }
            } 
        }
       
        
        
        
    }else{
        header("Location: index.php");
        exit();
    }
    
?>
<div class="container">
    <h1>Course Selection</h1>
    <hr class="solid">
    <p>Welcome <strong><?php echo $name; ?></strong>! (not you? change user <a href="Logout.php"><strong>here</strong></a>)</p>
    <p>You have registered <?php echo $totalRegisteredWeeklyHours? $totalRegisteredWeeklyHours : 0;?> hours for selected semester</p>
    <p>You can register <?php echo ($totalRegisteredWeeklyHours) > 16 ? 0 : (16 - $totalRegisteredWeeklyHours);?> more hours of course(s) for the semester</p>
    <p>Please note that the course(s) you have registered will not be displayed in the following list.</p>
    <form  method = "post" name="registerCourses">
        <!-- dropdown -->
        <div>
            <?php
                $dropdown = '<select name="selectedSemester" id="selectedSemester">';
                foreach ($availableSemester as $key => $semseter) {
                    if(isset($selectedSemester)) {
                        if($selectedSemester == $semseter->getSemseterCode()) {
                            $dropdown .= '<option value="'.$semseter->getSemseterCode().'" selected="selected">'.$semseter->getDisplayText().'</option>';
                            continue;
                        }
                    }
                    $dropdown .= '<option value="'.$semseter->getSemseterCode().'">'.$semseter->getDisplayText().'</option>'; 
                }
                $dropdown .= '</select>';
                
                echo $dropdown;
            ?>
       </div>
        <!-- button -->
        <div class="form-group row" hidden> 
            <div class=" col-sm-2 m-4">
                <input type = "submit" value = "submit" class="btn btn-secondary" name="getAvailableCouses" id="getAvailableCouses" />
            </div>
        </div>
        <div class="">
        <p class='text-danger'>
            <?php echo $errorMessage; ?>
        </p>
    </div>
        <!-- available course table -->
        <table class='table table-striped'>
            <tr>    
              <th>Code</th>
              <th>Course Title</th>
              <th>Hours</th>
              <th>Select</th>
            </tr>
            <tr>
                <?php
                    $tableData = '<tr>';
                    // foreach ($availableCourses as $key => $course) {
                    foreach ($modifiedAvailableCourses as $key => $course) {
                        $tableData .= '<td>'.$course->getCourseCode() .'</td>'
                                . '<td>'.$course->getTitle() .'</td>'
                                . '<td>'.$course->getWeeklyHours() .'</td>';
                        // if(isset($_SESSION['selectedCourses'])) {
                        if($keepselection) {
                            if(in_array($course->getCourseCode(), $selectedCourses)) {
                            //if(is_int(array_search($course->getCourseCode(), $_SESSION['selectedCourses']))){
                                $tableData .= '<td><input type="checkbox" checked id="'.$course->getCourseCode().'" name="selectedCourses[]" value="'.$course->getCourseCode().'"></td></tr>';    
                            }else{
                                $tableData .= '<td><input type="checkbox" id="'.$course->getCourseCode().'" name="selectedCourses[]" value="'.$course->getCourseCode().'"></td></tr>';    
                                    
                            }
                            
                        }else{
                            $tableData .= '<td><input type="checkbox" id="'.$course->getCourseCode().'" name="selectedCourses[]" value="'.$course->getCourseCode().'"></td></tr>';
                        }
                        

                    }
                    echo $tableData;

                ?>

            </tr>
        </table>
        
        <!-- button -->
        <div class="form-group row"> 
            <div class=" col-sm-2 m-4">
                <input type = "submit" value = "Submit" class="btn btn-secondary" name="registerCouses" id="registerCouses" />
            </div>
            <div class=" col-sm-2 m-4">
                <input type = "reset" value = "Clear" class="btn btn-secondary" name="clear" id="clear" />
            </div>
        </div>
        
    </form>
    
    
    
    
</div>
<script>
    // alert('Hellow World');
    document.addEventListener("input", function (e) {
        if (e.target.id === "clear") return document.registerCourses.reset();
        // check whether semester select dropdown or not.
        if (e.target.id !== "selectedSemester") return;
        // console.log(document.querySelector("#selectedSemester"));
        const selectedSemseterId = document.querySelector("#selectedSemester").value;

        if (selectedSemseterId !== null ) {
            document.getElementById("getAvailableCouses").click();
        }

    })
</script>
<?php include('./Common/footer.php'); ?>
