<?php 
    include("./Common/header.php");
    
    include_once "Functions.php";
    include_once "EntityClassLib.php";
    session_start();
    extract($_POST);
    
    if($_SESSION['student']){
        $student = unserialize($_SESSION['student']);
        $name = $student->getName();
        $studentId = $student->getStudentId();
        
        $registeredCourses = getRegisterdCourseByStudentIdandSemesterId($student->getStudentId());
        
        if(isset($_POST['deleteSelected'])){
            // When course(s) is(are) selected
            if(count($selectedCourses) > 0) {
                foreach ($selectedCourses as $sc) {
                    
                    $scCc = substr($sc, 0, strpos($sc, ':'));
                    $scSc =substr($sc, strpos($sc, ':')+1);
                    deleteRegistration($studentId, $scSc, $scCc);
   
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();   
            }
        }
       
    }else{
        header("Location: index.php");
        exit();
    }
    
    
    
    
?>
<div class="container">
    <h1>Course Registration</h1>
    <hr class="solid">
    <p>Hello <strong><?php echo $name; ?></strong>, (not you? change user <a href="Logout.php"><b>here</b></a>).The followings are your current registration.</p>
    <form  method = "post" name="rCourses" onsubmit="return doublecheck()">
        <!-- available course table -->
        <table class='table table-striped'>
            <tr>    
              <th>Year</th>
              <th>Term</th>
              <th>Course Code</th>
              <th>Course Title</th>
              <th>Hours</th>
              <th>Select</th>
            </tr>
            <tr>
                <?php
                    if(isset($registeredCourses)) {
                        $totalHours = 0;
                        $tableData = '';
                        foreach ($registeredCourses as $i => $course) {
                            
                            if($i > 0) { 
                                // total
                                if($course->getSemseterCode() != $registeredCourses[$i-1]->getSemseterCode()){
                                  $tableData .= '<tr><td colspan="4" style="text-align: right"><b> Total Weekly Hours </b></td>'
                                             .'<td><b>'. $totalHours .'</b></td><td></td></tr>'; 
                                  
                                  // reset totalHours
                                  $totalHours = 0;
                                }
                            }
                            $totalHours += (int)$course->getWeeklyHours();
                            $tableData .= '<tr><td>'.$course->getYear() .'</td>'
                                .'<td>'.$course->getTerm() .'</td>'
                                .'<td>'.$course->getCourseCode() .'</td>'
                                . '<td>'.$course->getTitle() .'</td>'
                                . '<td>'.$course->getWeeklyHours() .'</td>'
                                . '<td><input type="checkbox" id="'.$course->getCourseCode().'" name="selectedCourses[]" value="'.$course->getCourseCode().':'.$course->getSemseterCode().'"></td></tr>';
                            
                            // The last item
                            if($i+1 == count($registeredCourses)) {
                                $tableData .= '<tr><td colspan="4" style="text-align: right"><b> Total Weekly Hours </b></td>'
                                             .'<td><b>'. $totalHours .'</b></td><td></td></tr>'; 
                                  
                                // reset totalHours
                                $totalHours = 0;
                            }
                        }

                    }
                    echo $tableData;
                ?>

            </tr>
        </table>
        
        <!-- button -->
        <div class="form-group row"> 
            <div class=" col-sm-2 m-4">
                <input type = "submit" value = "DeleteSelected" class="btn btn-secondary" name="deleteSelected" id="deleteSelected" onclick="rCourses.key.value='delete'" />
            </div>
            <div class=" col-sm-2 m-4">
                <input type = "submit" value = "Clear" class="btn btn-secondary" name="clear" id="clear" onclick="rCourses.key.value='clear'" />
            </div>
            <input name="key" type="hidden" value="" />
        </div>
    </form>
</div>
<script>
    function doublecheck() {
        console.log(rCourses);
        if(rCourses.key.value === 'delete') {
            return window.confirm("The selected registration will be deleted.");
        }
    };
    
</script>

<?php include('./Common/footer.php'); ?>
