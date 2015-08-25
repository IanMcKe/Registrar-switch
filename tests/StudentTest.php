<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";


    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $result = $test_student->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $test_student->setName("Judy");
            $result = $test_student->getName();

            //Asset
            $this->assertEquals("Judy", $result);

        }

        function testGetDate()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $result = $test_student->getDate();

            //Assert
            $this->assertEquals($date, $result);
        }

        function testSetDate()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $test_student->setDate("2015-09-15");
            $result = $test_student->getDate();

            //Asset
            $this->assertEquals("2015-09-15", $result);

        }

        function testGetId()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $result = $test_student->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSetId()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);

            //Act
            $test_student->setId(2);
            $result = $test_student->getId();

            //Asset
            $this->assertEquals(2, $result);

        }

        function testSave()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            //Act
            $result = Student::getAll();

            //Assert
            $this->assertEquals($test_student, $result[0]);

        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            $name2 = "Obama";
            $date2 = "2015-10-10";
            $id2 = 2;
            $test_student2 = new Student($name, $date, $id);
            $test_student2->save();

            //Act
            Student::deleteAll();
            $result = Student::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            $new_name = "Obama";
            $new_date= "2015-10-10";

            //Act
            $test_student->update($new_name, $new_date);

            //Assert
            $this->assertEquals("Obama", $test_student->getName());
            $this->assertEquals("2015-10-10", $test_student->getDate());
        }

        function testFind()
        {
            //Arrange
            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            $name2 = "Obama";
            $date2 = "2015-10-10";
            $id2 = 2;
            $test_student2 = new Student($name, $date, $id);
            $test_student2->save();

            //Act
            $result = Student::find($test_student2->getId());

            //Assert
            $this->assertEquals($test_student2, $result);
        }

        function testAddCourse()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $test_course = new Course($name, $course_num);
            $test_course->save();

            $name = "Rick";
            $date = "2015-08-15";
            $test_student = new Student($name, $date);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);

            //Assert
            $this->assertEquals($test_student->getCourses(),[$test_course]);
        }

        function testGetCourses()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();

            $name2 = "English";
            $course_num2 = "200";
            $id2 = 2;
            $test_course2 = new Course($name2, $course_num2, $id2);
            $test_course2->save();

            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);
            $test_student->addCourse($test_course2);

            $result = $test_student->getCourses();

            //Assert
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function testDelete()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();

            $name = "Rick";
            $date = "2015-08-15";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);
            $test_student->delete();

            //Assert
            $this->assertEquals([], $test_course->getStudents());
        }

    }
?>
