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

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM courses_students WHERE course_id = {$this->getId()};");
        }

        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO courses_students (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }

        function getStudents()
        {
            //Trying JOIN - not working
            $query = $GLOBALS['DB']->query("SELECT students.* FROM courses JOIN courses_students ON (courses.id = courses_students.course_id) JOIN students ON (courses_students.student_id = students.id) WHERE courses.id = {$this->getId()};");
            $students = $query->fetchAll(PDO::FETCH_ASSOC);

            $students_array = array();

            foreach($students as $student) {
                $name = $students[0]['name'];
                $date = $students[0]['date'];
                $id = $students[0]['id'];
                $new_student = new Student($name, $date, $id);
                array_push($students_array, $new_student);
            }
            return $students_array;

            //Function with old method that works
            // $query = $GLOBALS['DB']->query("SELECT student_id FROM courses_students WHERE course_id = {$this->getId()};");
            // $student_ids = $query->fetchAll(PDO::FETCH_ASSOC);
            //
            // $students = array();
            // foreach($student_ids as $id) {
            //     $student_id = $id['student_id'];
            //     $result = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$student_id};");
            //     $returned_student = $result->fetchAll(PDO::FETCH_ASSOC);
            //
            //     $name = $returned_student[0]['name'];
            //     $id = $returned_student[0]['id'];
            //     $date = $returned_student[0]['date'];
            //
            //     $new_student = new Student($name, $date, $id);
            //     array_push($students, $new_student);
            // }
            // return $students;
        }


    }


 ?>
