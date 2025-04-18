<?php
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or edit student
    if (isset($_POST['action']) && $_POST['action'] === 'add_edit_student') {
        $name = $_POST['student_name'];
        $pass = $_POST['tpass'];
        $department = $_POST['student_department'];
        $student_id = $_POST['student_id'] ?? uniqid();
        $username = strtolower(str_replace(' ', '_', $name));

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            // Update existing student
            $stmt = $pdo->prepare("UPDATE student SET name = :name, department = :department WHERE id = :id");
            $stmt->execute([':id' => $_POST['edit_id'], ':name' => $name, ':department' => $department]);

            // Update user if password is provided
            if (!empty($pass)) {
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = (SELECT user_id FROM student WHERE id = :id)");
                $stmt->execute([':id' => $_POST['edit_id'], ':password' => $pass]);
            }
        } else {
            // Insert new student
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'student')");
            $stmt->execute([':username' => $username, ':password' =>$pass]);

            $user_id = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO student (user_id, name, student_id, department) VALUES (:user_id, :name, :student_id, :department)");
            $stmt->execute([':user_id' => $user_id, ':name' => $name, ':student_id' => $student_id, ':department' => $department]);
        }
    }
    
    // Add or edit faculty
    if (isset($_POST['action']) && $_POST['action'] === 'add_edit_faculty') {
        $name = $_POST['faculty_name'];
        $department = $_POST['faculty_department'];
        $passw = $_POST['facpass'];
        $username = strtolower(str_replace(' ', '_', $name));

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            // Update existing faculty
            $stmt = $pdo->prepare("UPDATE faculty SET name = :name, department = :department WHERE id = :id");
            $stmt->execute([':id' => $_POST['edit_id'], ':name' => $name, ':department' => $department]);

            // Update user if password is provided
            if (!empty($passw)) {
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = (SELECT user_id FROM faculty WHERE id = :id)");
                $stmt->execute([':id' => $_POST['edit_id'], ':password' => $passw]);
            }
        } else {
            // Insert new faculty
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'faculty')");
            $stmt->execute([':username' => $username, ':password' => $passw]);

            $user_id = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO faculty (user_id, name, department) VALUES (:user_id, :name, :department)");
            $stmt->execute([':user_id' => $user_id, ':name' => $name, ':department' => $department]);
        }
    }

    // Add or edit course
    if (isset($_POST['action']) && $_POST['action'] === 'add_edit_course') {
        $course_code = $_POST['course_code'];
        $course_name = $_POST['course_name'];
        $section = $_POST['course_section'];
        $faculty_id = $_POST['faculty_id'];
        $class_days = $_POST['class_days'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $join_link = $_POST['join_link'];
        $frequency = $_POST['frequency'];

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            // Update existing course
            $stmt = $pdo->prepare("UPDATE classes SET course_code = :course_code, course_name = :course_name, section = :section, faculty_id = :faculty_id, class_days = :class_days, start_time = :start_time, end_time = :end_time, join_link = :join_link, frequency = :frequency WHERE id = :id");
            $stmt->execute([
                ':id' => $_POST['edit_id'],
                ':course_code' => $course_code,
                ':course_name' => $course_name,
                ':section' => $section,
                ':faculty_id' => $faculty_id,
                ':class_days' => $class_days,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':join_link' => $join_link,
                ':frequency' => $frequency
            ]);
        } else {
            // Insert new course
            $stmt = $pdo->prepare("INSERT INTO classes (course_code, course_name, section, faculty_id, class_days, start_time, end_time, join_link, frequency) VALUES (:course_code, :course_name, :section, :faculty_id, :class_days, :start_time, :end_time, :join_link, :frequency)");
            $stmt->execute([
                ':course_code' => $course_code,
                ':course_name' => $course_name,
                ':section' => $section,
                ':faculty_id' => $faculty_id,
                ':class_days' => $class_days,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':join_link' => $join_link,
                ':frequency' => $frequency
            ]);
        }
    }

    // Assign or remove student from course
    if (isset($_POST['action']) && $_POST['action'] === 'assign_remove_student') {
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $assign_remove = $_POST['assign_remove'];

        if ($assign_remove === 'assign') {
            $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, class_id) VALUES (:student_id, :class_id)");
            $stmt->execute([':student_id' => $student_id, ':class_id' => $course_id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM enrollments WHERE student_id = :student_id AND class_id = :class_id");
            $stmt->execute([':student_id' => $student_id, ':class_id' => $course_id]);
        }
    }

    // Delete record
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $table = $_POST['table'];
        $id = $_POST['id'];

        // If deleting a student or faculty, also delete the associated user
        if ($table === 'student' || $table === 'faculty') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = (SELECT user_id FROM $table WHERE id = :id)");
            $stmt->execute([':id' => $id]);
        }

        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}