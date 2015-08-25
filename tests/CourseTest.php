<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Course.php";
    require_once "src/Student.php";


    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CourseTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Course::deleteAll();
            Student::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $result = $test_course->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $test_course->setName("Science");
            $result = $test_course->getName();

            //Assert
            $this->assertEquals("Science", $result);
        }

        function testGetCourseNum()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $result = $test_course->getCourseNum();

            //Assert
            $this->assertEquals($course_num, $result);
        }

        function testSetCourseNum()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $test_course->setCourseNum("102");
            $result = $test_course->getCourseNum();

            //Assert
            $this->assertEquals("102", $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSetId()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);

            //Act
            $test_course->setId(2);
            $result = $test_course->getId();

            //Assert
            $this->assertEquals(2, $result);
        }

        function testSave()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals($test_course, $result[0]);

        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();
            $name2 = "English";
            $course_num2 = "300";
            $id2 = 2;
            $test_course2 = new Course($name2, $course_num2, $id2);
            $test_course2->save();

            //Act
            Course::deleteAll();
            $result = Course::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();

            $new_name = "English";
            $new_course_num = "200";

            //Act
            $test_course->update($new_name, $new_course_num);

            //Assert
            $this->assertEquals("English", $test_course->getName());
            $this->assertEquals("200", $test_course->getCourseNum());
        }

        function testFind()
        {
            //Arrange
            $name = "Math";
            $course_num = "101";
            $id = 1;
            $test_course = new Course($name, $course_num, $id);
            $test_course->save();
            $name2 = "English";
            $course_num2 = "300";
            $id2 = 2;
            $test_course2 = new Course($name2, $course_num2, $id2);
            $test_course2->save();

            //Act
            $result = Course::find($test_course2->getId());

            //Assert
            $this->assertEquals($test_course2, $result);
        }

        // function testDelete()
        // {
        //
        // }


    }

?>
