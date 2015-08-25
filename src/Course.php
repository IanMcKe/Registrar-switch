<?php

    class Course {

        private $name;
        private $course_num;
        private $id;

        function __construct($name, $course_num, $id=null) {

            $this->name = $name;
            $this->course_num = $course_num;
            $this->id = $id;
        }

        function setName($new_name) {
            $this->name = $new_name;
        }

        function getName() {
            return $this->name;
        }

        function setCourseNum($new_course_num) {
            $this->course_num = $new_course_num;
        }

        function getCourseNum() {
            return $this->course_num;
        }

        function setId($new_id) {
            $this->id = $new_id;
        }

        function getId() {
            return $this->id;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO courses (name, course_num) VALUES ('{$this->getName()}', '{$this->getCourseNum()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $courses = array();
            foreach($returned_courses as $course) {
                $name = $course['name'];
                $course_num = $course['course_num'];
                $id = $course['id'];
                $new_course = new Course($name, $course_num, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        function update($new_name, $new_course_num)
        {
            $GLOBALS['DB']->exec("UPDATE courses SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("UPDATE courses SET course_num = '{$new_course_num}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
            $this->setCourseNum($new_course_num);
        }

        static function find($search_id)
        {
            $found_course = null;
            $courses = Course::getAll();
            foreach($courses as $course) {
                $course_id = $course->getId();
                if ($course_id == $search_id) {
                    $found_course = $course;
                }
            }
            return $found_course;
        }

        // function delete() {
        //     $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
        // }

        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO courses_students (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }


    }


 ?>
