<?php
include_once 'EntityClassLib.php';


function getPDO() {
    $dbConnection = parse_ini_file("Lab5.ini");
    extract($dbConnection);
    return new PDO($dsn, $scriptUser, $scriptPassword);
}

function getStudentByIdAndPassword($studentId, $password) {
    $pdo = getPDO();
    /*
    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = '$studentId' AND Password = '$password'";
    $resultSet = $pdo->query($sql);
    */
    
    // Lab6: Use prepared statement added by miku 2022.11.15 
    
    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = :studentId AND Password = :password";
    
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['studentId' => $studentId, 'password' => $password]);
    
    
    if($pstmt) {
       $row = $pstmt->fetch(PDO::FETCH_ASSOC);
       /* 
        echo '$row : ';
        var_dump($row);
        echo '<br> ------ <br>';
        */
        if ($row)
        {
            return  new Student($row['StudentId'], $row['Name'], $row['Phone'] ); ;        
        }
        else
        {
            return null;
        }
    }
    else
    {
        throw new Exception("Query failed! SQL statement: $sql");
    }
}

function verifyPassword($password, $hashedPassword) {
    if(password_verify($password, $hashedPassword)) {
               
    };
}

// Lab6: $password will be hashed by miku 2022.11.15
function addNewUser($studentId, $name, $phone, $password)
{
    $pdo = getPDO();
    /*
    $sql = "INSERT INTO Student (StudentId, Name, Phone, Password) VALUES( '$studentId', '$name', '$phone', '$password')";
    $pdoStmt = $pdo->query($sql);
    */
    
    
    // Lab6 : Use prepared statement added by miku 2022.11.15 
    $sql = "INSERT INTO Student (StudentId, Name, Phone, Password) VALUES( :studentId, :name, :phone, :password)";
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['studentId' => $studentId, 
                     'name' => $name, 
                     'phone' => $phone,
                     'password' => $password]);
}

function getAvailableSemester() {
    $pdo = getPDO();
    $sql = 'SELECT * FROM Semester';
    $resultSet = $pdo->query($sql);
    
    foreach ($resultSet as $row) {
        $semester = new Semester($row['SemesterCode'], $row['Term'], $row['Year']);
        $semesters[] = $semester;
    }
    return $semesters;
    
}

function getAvailableCourses($semesterCode){
    $pdo = getPDO();
    $sql = 'SELECT * FROM Course c INNER JOIN CourseOffer co ON c.CourseCode = co.CourseCode WHERE SemesterCode = :semesterCode';
    
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['semesterCode' => $semesterCode]);
    
    foreach ($pstmt as $row) {
        
        $course = new Course($row['SemesterCode'], $row['CourseCode'], $row['Title'], $row['WeeklyHours']);
        $courses[] = $course;
        
    }
    
    return $courses;
    
}

function getRegisterdCourseByStudentIdandSemesterId($studentId, $semesterCode = []) {
// function getRegisterdCourseByStudentId($studentId) {
    $pdo = getPDO();
    
    if(Count($semesterCode) > 0){
        $sql = 'SELECT * FROM Registration r INNER JOIN Course c ON r.CourseCode = c.CourseCode WHERE StudentId = :studentId AND SemesterCode = :semesterCode ORDER BY SemesterCode';
        
        $pstmt = $pdo->prepare($sql);
        $pstmt->execute(['studentId' => $studentId, 'semesterCode' => $semesterCode]);
        if($pstmt) {
        
        foreach ($pstmt as $row) {
            $registerdCourse = new Course($row['SemesterCode'], $row['CourseCode'], $row['Title'], $row['WeeklyHours']);
            $registerdCourses[] = $registerdCourse;
        }
        return $registerdCourses;
        }
    }else{
        $sql = 'SELECT * FROM Registration r 
                INNER JOIN Course c ON r.CourseCode = c.CourseCode 
                INNER JOIN Semester s ON r.SemesterCode = s.SemesterCode
                WHERE StudentId = :studentId ORDER BY r.SemesterCode';
        
        $pstmt = $pdo->prepare($sql);
        $pstmt->execute(['studentId' => $studentId]);
        if($pstmt) {
        
        foreach ($pstmt as $row) {
            $registerdCourse = new Course($row['SemesterCode'], $row['CourseCode'], $row['Title'], $row['WeeklyHours'], $row['Year'], $row['Term']);
            $registerdCourses[] = $registerdCourse;
        }
        return $registerdCourses;
    }

    }
    
    if($pstmt) {
        
        foreach ($pstmt as $row) {
            $registerdCourse = new Course($row['SemesterCode'], $row['CourseCode'], $row['Title'], $row['WeeklyHours']);
            $registerdCourses[] = $registerdCourse;
        }
        return $registerdCourses;
    }
    
    return null;
    
}


function getRegisteredCourseToShow($studentId) {
    $sql = 'SELECT * FROM Registration r 
                INNER JOIN Course c ON r.CourseCode = c.CourseCode 
                INNER JOIN Semester s ON r.SemesterCode = s.SemesterCode
                WHERE StudentId = :studentId ORDER BY r.SemesterCode';
        
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['studentId' => $studentId]);
    
}

function addNewRegistration($studentId, $courseCode, $semesterCode) {
    $pdo = getPDO();
    
    $sql = "INSERT INTO Registration (StudentId, CourseCode, SemesterCode) VALUES( :studentId, :courseCode, :semesterCode)";
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['studentId' => $studentId, 
                     'courseCode' => $courseCode, 
                     'semesterCode' => $semesterCode]);
}

function deleteRegistration($studentId, $semesterCode, $courseCode) {
    $pdo = getPDO();
    
    $sql = "DELETE FROM Registration WHERE StudentId = :studentId AND SemesterCode = :semesterCode AND CourseCode = :courseCode";
    $pstmt = $pdo->prepare($sql);
    $pstmt->execute(['studentId' => $studentId,
                     'semesterCode' => $semesterCode,
                     'courseCode' => $courseCode]);
}