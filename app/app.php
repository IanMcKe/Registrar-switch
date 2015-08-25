<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Course.php";
    require_once __DIR__."/../src/Student.php";

    $app = new Silex\Application();

    $app['debug']=true;

    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //homepage
    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig');
    });

    //pages for all students
    $app->get("/students", function() use($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post("/students", function() use($app) {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $student = new Student($name, $date);
        $student->save();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post("/delete_students", function() use($app){
        $GLOBALS['DB']->exec("DELETE FROM courses_students;");
        Student::deleteAll();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    //pages for individual student/editing
    $app->get("/students/{id}", function($id) use($app){
        $student = Student::find($id);
        $courses = $student->getCourses();
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $courses, 'all_courses' => Course::getAll()));
    });

    $app->get("/students/{id}/edit", function($id) use($app){
        $student = Student::find($id);
        $courses = $student->getCourses();
        return $app['twig']->render('student_edit.html.twig', array('student' => $student, 'courses' => $courses));
    });

    $app->patch("/students/{id}", function($id) use($app){
        $student = Student::find($id);
        $courses = $student->getCourses();
        $student->update($_POST['name'], $_POST['date']);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $courses));
    });

    $app->delete("/students/{id}", function($id) use($app){
        $student = Student::find($id);
        $student->delete();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post("/add_courses", function() use($app){
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $student->addCourse($course);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });

    //pages for all courses
    $app->get("/courses", function() use($app) {
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/courses", function() use($app) {
        $name = $_POST['name'];
        $course_num = $_POST['course_num'];
        $course = new Course($name, $course_num);
        $course->save();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/delete_courses", function() use($app){
        $GLOBALS['DB']->exec("DELETE FROM courses_students;");
        Course::deleteAll();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    //pages for individual courses
    $app->get("/courses/{id}", function($id) use($app){
        $course = Course::find($id);
        $students = $course->getStudents();
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $students, 'all_students' => Student::getAll()));
    });

    $app->get("/courses/{id}/edit", function($id) use($app){
        $course = Course::find($id);
        $students = $course->getStudents();
        return $app['twig']->render('course_edit.html.twig', array('course' => $course, 'students' => $students));
    });

    $app->patch("/courses/{id}", function($id) use($app){
        $course = Course::find($id);
        $students = $course->getStudents();
        $course->update($_POST['name'], $_POST['course_num']);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $students));
    });

    $app->delete("/courses/{id}", function($id) use($app){
        $course = Course::find($id);
        $course->delete();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/add_students", function() use($app){
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $course->addStudent($student);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $course->getStudents(), 'all_students' => Student::getAll()));
    });



    return $app;
 ?>
