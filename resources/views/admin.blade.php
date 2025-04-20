<?php
// Fetch existing data
$students = \App\Models\Student::all();
$faculty = \App\Models\Faculty::all();
$courses = \App\Models\Course::all();
$enrollments = \App\Models\Enrollment::all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAC University Virtual Campus - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #171717;
            color: #fff; 
        }
        .container {
            display: flex;
            height: 100vh; /* Full viewport height */
            overflow: hidden;
            
           
        }
        .sidebar {
            background-color: #1a1a1a; /* or whatever color you prefer */
            color: white;
            padding: 20px;
            flex-shrink: 0; /* Prevent shrinking */
            overflow-y: none; /* Allow scrolling inside sidebar if content overflows */
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            width: 110px;
            margin-bottom: 10px;
            margin-top: 15px;
        }
        .menu {
            list-style-type: none;
            padding: 0;
            width: 100%;
        }
        .menu li {
            margin-bottom: 15px;
        }
        .menu li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px 13px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        .menu li a:hover {
            background-color: #2c2c2c;
            border-radius: 8px;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 300px; /* Same as sidebar width */
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
                    }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .greeting {
            background-color: #1c1c1c;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.3em;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info span {
            font-size: 1.5em;
            margin-right: 15px;
        }
        .grid {
            display: none;
        }
        .grid.active {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .card {
            background-color: #1c1c1c;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .card h3 {
            margin-top: 0;
            font-weight: 600;
            font-size: 1.2em;
        }
        .card p {
            margin: 10px 0;
            font-size: 0.9em;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        .status {
            color: #2ecc71;
            font-size: 0.9em;
        }
        .virtual-campus {
            color: #295CA9;
            font-size: 1.4em;
            font-style: italic;
            font-weight: bold;
            margin-bottom: 30px;
            animation: neon 1s ease infinite;
        }
        @keyframes neon {
            100% {
                text-shadow: 0 0 1vw #2974e3, 0 0 3vw #14448b, 0 0 10vw #14448b, 0 0 10vw #14448b, 0 0 .4vw #469ad9, .5vw .5vw .1vw #144180;
                color: #73baf7;
            }
            70% {
                text-shadow: 0 0 .5vw #469ad9, 0 0 1.5vw #469ad9, 0 0 5vw #469ad9, 0 0 5vw #14448b, 0 0 .2vw #14448b, .5vw .5vw .1vw #475aaf;
                color: #7cb4ec;
            }
        }
        .button {
            --glow-color: #4699d9;
            --glow-spread-color: #14448b86;
            --enhanced-glow-color: rgb(231, 206, 255);
            --btn-color: #295eadaf;
            border: .25em solid var(--glow-color);
            padding: 1em 3em;
            color: white;
            font-size: 15px;
            font-weight: bold;
            background-color: var(--btn-color);
            border-radius: 1em;
            outline: none;
            box-shadow: 0 0 1em .25em var(--glow-color),
                        0 0 4em 1em var(--glow-spread-color),
                        inset 0 0 .75em .25em var(--glow-color);
            text-shadow: 0 0 .5em var(--glow-color);
            position: relative;
            transition: all 0.3s;
        }
        .button:hover {
            color: white;
            background-color: var(--glow-color);
            box-shadow: 0 0 1em .25em var(--glow-color),
                        0 0 4em 2em var(--glow-spread-color),
                        inset 0 0 .75em .25em var(--glow-color);
        }
        .button:active {
            box-shadow: 0 0 0.6em .25em var(--glow-color),
                        0 0 2.5em 2em var(--glow-spread-color),
                        inset 0 0 .5em .25em var(--glow-color);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }
        th, td {
            background-color: #1c1c1c;
            padding: 15px;
            text-align: left;
            border-top: 1px solid #2c2c2c;
            border-bottom: 1px solid #2c2c2c;
        }
        th:first-child, td:first-child {
            border-left: 1px solid #2c2c2c;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        th:last-child, td:last-child {
            border-right: 1px solid #2c2c2c;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        form {
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="email"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #2c2c2c;
            background-color: #171717;
            color: #fff;
        }
        form button {
            margin-top: 10px;
        }.button_out {
            text-decoration: none;
            --glow-color: rgb(196, 42, 34,.25);
            --glow-spread-color: rgb(196, 42, 34,.25);
            --enhanced-glow-color: #ff762c;
            --btn-color: #295eadaf;
            border: .25em solid var(--glow-color);
            padding: 10px;
            color: white;
            font-size: 15px;
            font-weight: bold;
            background-color: var(--btn-color);
            border-radius: 1em;
            outline: none;
            box-shadow: 0 0 1em .25em var(--glow-color),
                    0 0 4em 1em var(--glow-spread-color),
                    inset 0 0 .75em .25em var(--glow-color);
            text-shadow: 0 0 .5em var(--glow-color);
            position: relative;
            transition: all 0.3s;
        }
        .button_out:hover {
            color: white;
            background-color: rgb(196, 42, 34);
            box-shadow: 0 0 1em .25em var(--glow-color),
                    0 0 4em 2em var(--glow-spread-color),
                    inset 0 0 .75em .25em var(--glow-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="/logo.png" alt="BRAC University Logo" class="logo">
            <h2 class="virtual-campus"><b>VIRTUAL CAMPUS</b></h2>
            <ul class="menu">
                <li><b><a class="menus active" onclick="opentab('Dashboard')" href="#">Dashboard</a></b></li>
                <li><b><a class="menus" onclick="opentab('ManageStudents')" href="#">Manage Students</a></b></li>
                <li><b><a class="menus" onclick="opentab('ManageFaculty')" href="#">Manage Faculty</a></b></li>
                <li><b><a class="menus" onclick="opentab('ManageCourses')" href="#">Manage Courses</a></b></li>
                <li><b><a class="menus" onclick="opentab('ManageEnrollments')" href="#">Manage Enrollments</a></b></li>
            </ul>
          
                
            <a href="/virtual_campus/login.php" class="button_out">Log Out</a>
            
        </div>
        <div class="content">
            <div class="header">
                <h2 class="greeting">⛅Welcome, Admin</h2>
                
            </div>

            <div class="grid active" id="Dashboard">
                <h2>Admin Dashboard</h2>
                <div class="card">
                    <h3>Quick Stats</h3>
                    <p>Total Students: <?= $students->count() ?></p>
                    <p>Total Faculty: <?= $faculty->count() ?></p>
                    <p>Total Courses: <?= $courses->count() ?></p>
                    <p>Total Enrollments: <?= $enrollments->count() ?></p>
                </div>
                
            </div>

            <div class="grid" id="ManageStudents">
                <h2>Manage Students</h2>
                <form method="POST" action="{{ route('student_edit') }}" id="studentForm">
                    @csrf
                    <input type="hidden" name="action" value="add_student">
                    <input type="hidden" name="edit_id" id="studentEditId">
                    <label>Name: <input type="text" name="student_name" id="studentName" required></label>
                    <label>Email: <input type="email" name="student_email" id="studentemail" placeholder="example@example.com" required></label>
                    <label>Phone: <input type="number" name="student_number" placeholder="018546-82005" id="studentnumber" required></label>
                    <label>Password: <input type="password" name="tpass" id="studentPass"></label>
                    <label>Department:
                        <select name="student_department" id="studentDepartment" required>
                            <option value="CSE">CSE</option>
                            <option value="CS">CS</option>
                            <option value="EEE">EEE</option>
                            <option value="BBA">BBA</option>
                            <option value="ANT">ANT</option>
                            <option value="BIO_TEC">BIO_TEC</option>
                            <option value="ECE">ECE</option>
                        </select>
                    </label>
                    <button type="submit" id="sbutton" class="button">Add</button>
                    <button type="button" class="button" onclick="resetForm('studentForm')">Reset</button>
                </form>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Department</th><th>Actions</th></tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['u_id'] ?></td>
                            <td><?= $student['student_name'] ?></td>
                            <td><?= $student['student_department'] ?></td>
                            <td>
                            <a href=#top><button onclick="editStudent(<?= htmlspecialchars(json_encode($student)) ?>)" class="button">Edit</button></a>
                                <form method="POST" action="{{ route('student_del') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="s_del_id" value="<?= $student['u_id'] ?>">
                                    <button type="submit" class="button" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="grid" id="ManageFaculty">
                <h2>Manage Faculty</h2>
                <form method="POST" id="facultyForm">
                    <input type="hidden" name="action" value="add_edit_faculty">
                    <input type="hidden" name="edit_id" id="facultyEditId">
                    <label>Name: <input type="text" name="faculty_name" id="facultyName" required></label>
                    <label>Password: <input type="password" name="facpass" id="facultyPass"></label>
                    <label>Department:
                        <select name="faculty_department" id="facultyDepartment" required>
                            <option value="CSE">CSE</option>
                            <option value="CS">CS</option>
                            <option value="EEE">EEE</option>
                            <option value="BBA">BBA</option>
                            <option value="ANT">ANT</option>
                            <option value="BIO_TEC">BIO_TEC</option>
                            <option value="ECE">ECE</option>
                        </select>
                    </label>
                    <button type="submit" class="button">Add/Edit Faculty</button>
                </form>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Department</th><th>Actions</th></tr>
                    <?php foreach ($faculty as $fac): ?>
                        <tr>
                            <td><?= $fac['id'] ?></td>
                            <td><?= $fac['name'] ?></td>
                            <td><?= $fac['department'] ?></td>
                            <td>
                                <button onclick="editFaculty(<?= htmlspecialchars(json_encode($fac)) ?>)" class="button">Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="table" value="faculty">
                                    <input type="hidden" name="id" value="<?= $fac['id'] ?>">
                                    <button type="submit" class="button" onclick="return confirm('Are you sure you want to delete this faculty member?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="grid" id="ManageCourses">
                <h2>Manage Courses</h2>
                <form method="POST" id="courseForm">
                    <input type="hidden" name="action" value="add_edit_course">
                    <input type="hidden" name="edit_id" id="courseEditId">
                    <label>Course Code: <input type="text" name="course_code" id="courseCode" required></label>
                    <label>Course Name: <input type="text" name="course_name" id="courseName" required></label>
                    <label>Section: <input type="text" name="course_section" id="courseSection" required></label>
                    <label>Faculty:
                        <select name="faculty_id" id="courseFaculty" required>
                            <?php foreach ($faculty as $fac): ?>
                                <option value="<?= $fac['id'] ?>"><?= $fac['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Class Days:
                        <select name="class_days" id="classDays" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </label>
                    <label>Start Time: <input type="time" name="start_time" id="startTime" required></label>
                    <label>End Time: <input type="time" name="end_time" id="endTime" required></label>
                    <label>Join Link: <input type="url" name="join_link" id="joinLink" required></label>
                    <label>Frequency: <input type="number" name="frequency" id="frequency" min="1" max="7" required></label>
                    <button type="submit" class="button">Add/Edit Course</button>
                </form>
                <table>
                    <tr><th>ID</th><th>Course Code</th><th>Course Name</th><th>Section</th><th>Faculty</th><th>Class Days</th><th>Time</th><th>Actions</th></tr>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= $course['id'] ?></td>
                            <td><?= $course['course_code'] ?></td>
                            <td><?= $course['course_name'] ?></td>
                            <td><?= $course['section'] ?></td>
                            <td><?= $course['faculty_name'] ?></td>
                            <td><?= $course['class_days'] ?></td>
                            <td><?= $course['start_time'] ?> - <?= $course['end_time'] ?></td>
                            <td>
                                <button onclick="editCourse(<?= htmlspecialchars(json_encode($course)) ?>)" class="button">Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="table" value="classes">
                                    <input type="hidden" name="id" value="<?= $course['id'] ?>">
                                    <button type="submit" class="button" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="grid" id="ManageEnrollments">
                <h2>Manage Enrollments</h2>
                <form method="POST" id="enrollmentForm">
                    <input type="hidden" name="action" value="assign_remove_student">
                    <label>Student:
                        <select name="student_id" required>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>"><?= $student['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Course:
                        <select name="course_id" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>"><?= $course['course_code'] ?> (<?= $course['section'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        <input type="radio" name="assign_remove" value="assign" checked> Assign
                        <input type="radio" name="assign_remove" value="remove"> Remove
                    </label>
                    <button type="submit" class="button">Manage Enrollment</button>
                </form>
                <table>
                    <tr><th>ID</th><th>Student</th><th>Course</th><th>Actions</th></tr>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td><?= $enrollment['id'] ?></td>
                            <td><?= $enrollment['student_name'] ?></td>
                            <td><?= $enrollment['course_code'] ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="table" value="enrollments">
                                    <input type="hidden" name="id" value="<?= $enrollment['id'] ?>">
                                    <button type="submit" class="button" onclick="return confirm('Are you sure you want to remove this enrollment?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function opentab(tabname) {
            $('.grid').removeClass('active');
            $('#' + tabname).addClass('active');
            $('.menus').removeClass('active');
            $('a[onclick="opentab(\'' + tabname + '\')"]').addClass('active');
        }

        function editStudent(student) {
            document.getElementById('studentEditId').value = student.u_id;
            document.getElementById('studentemail').value = student.student_email;
            document.getElementById('studentnumber').value = student.student_phone;
            document.getElementById('studentName').value = student.student_name;
            document.getElementById('studentDepartment').value = student.student_department;
            document.getElementById('sbutton').innerHTML = 'Update Student';
        }
        

        function editFaculty(faculty) {
            $('#facultyEditId').val(faculty.id);
            $('#facultyName').val(faculty.name);
            $('#facultyDepartment').val(faculty.department);
            $('#facultyPass').val('');
            $('#facultyForm').find('button[type="submit"]').text('Update Faculty');
        }

        function editCourse(course) {
            $('#courseEditId').val(course.id);
            $('#courseCode').val(course.course_code);
            $('#courseName').val(course.course_name);
            $('#courseSection').val(course.section);
            $('#courseFaculty').val(course.faculty_id);
            $('#classDays').val(course.class_days);
            $('#startTime').val(course.start_time);
            $('#endTime').val(course.end_time);
            $('#joinLink').val(course.join_link);
            $('#frequency').val(course.frequency);
            $('#courseForm').find('button[type="submit"]').text('Update Course');
        }

        function resetForm(formId) {
            $('#' + formId)[0].reset();
            $('#' + formId).find('input[type="hidden"][name="edit_id"]').val('');
            $('#' + formId).find('button[type="submit"]').text(function() {
                return $(this).text().replace('Update', 'Add/Edit');
            });
        }

    </script>
</body>
</html>