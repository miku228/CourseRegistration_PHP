<?php
    class Student{
        private $studentId;
        private $name;
        private $phone;

        private $messages;

        public function __construct($studentId, $name, $phone)
        {
            $this->studentId = $studentId;
            $this->name = $name;
            $this->phone = $phone;

            $this->messages = array();
        }

        public function getStudentId() {
            return $this->studentId;
        }

        public function getName() {
            return $this->name;
        }

        public function getPhone() {
            return $this->phone;
        }
        
    }
    
    class Semester{
        private $semesterCode;
        private $term;
        private $year;

        private $messages;

        public function __construct($semesterCode, $term, $year)
        {
            $this->semesterCode = $semesterCode;
            $this->term = $term;
            $this->year = $year;

            $this->messages = array();
        }

        public function getSemseterCode() {
            return $this->semesterCode;
        }

        public function getTerm() {
            return $this->term;
        }

        public function getYear() {
            return $this->year;
        }
        
        
        public function getDisplayText() {
            return $this->year . " " . $this->term;
        }
        
    }
    
    class Course{
        private $semesterCode;
        private $courseCode;
        private $title;
        private $weeklyHours;
        private $year;
        private $term;

        private $messages;

        public function __construct($semesterCode, $courseCode, $title, $weeklyHours, $year = 1999, $term = "AAA")
        {
            $this->semesterCode = $semesterCode;
            $this->courseCode = $courseCode;
            $this->title = $title;
            $this->weeklyHours = $weeklyHours;
            $this->year = $year;
            $this->term = $term;

            $this->messages = array();
        }

        public function getSemseterCode() {
            return $this->semesterCode;
        }

        public function getCourseCode() {
            return $this->courseCode;
        }

        public function getTitle() {
            return $this->title;
        }
        
        public function getWeeklyHours() {
            return $this->weeklyHours;
        }
        
        public function getYear() {
            return $this->year;
        }
        
        public function getTerm() {
            return $this->term;
        }
        
    }
    
    
    
