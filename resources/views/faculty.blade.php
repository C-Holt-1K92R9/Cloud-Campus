<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

$courses = Course::where('u_id', session('user_id'))->get();

$days = ['Time', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$routine = [];
foreach ($courses as $class) {
    $time_slot = $class->course_time; 
    $class_days_array = array_map('trim', explode(',', $class->course_days));
    foreach ($class_days_array as $day) {
        $routine[$time_slot][$day] = $class; 
    }}

$today = Carbon::now('Asia/Dhaka')->format('l');


$live_classes = Course::where('course_days', 'like', '%' . $today . '%')
                    ->where('u_id', session('user_id')) 
                    ->get();


foreach ($live_classes as $temp){
    if ($temp->status =='Cancelled'){
        $passed_time = Carbon::parse($temp->updated_at)->diffInHours(Carbon::now());
        if ($passed_time >= 24) {
            $temp->status = 'Online';
            $temp->save();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAC University Virtual Campus</title>
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
        }
        .sidebar {
            width: 280px;
            background-color: #171717;
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
        .grid{
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .dash{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .course{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 30px;
        }
        .grid > div > h2 {
            font-size: 1.0em;
            margin-bottom: 20px;
        }
        .grid{
            display: none;
        }
        .grid.active{
            display: grid;
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
            animation: glow 1s ease-in-out infinite alternate;
        }
        .virtual-campus {
            animation: neon 1s ease infinite;
            -moz-animation: neon 1s ease infinite;
            -webkit-animation: neon 1s ease infinite;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="email"],
        form input[type="number"],
        form input[type="date"],
        form input[type="file"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #2c2c2c;
            background-color: #171717;
            color: #fff;
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
        .button::after {
            pointer-events: none;
            content: "";
            position: absolute;
            top: 120%;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: var(--glow-spread-color);
            filter: blur(2em);
            opacity: .7;
            transform: perspective(1.5em) rotateX(35deg) scale(1, .6);
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
        .flip-card {
            background-color: transparent;
            width: 230px;
            height: 294px;
            perspective: 1000px;
            font-family: sans-serif;
        }
        .title {
            font-size: 1.5em;
            font-weight: 900;
            text-align: center;
            margin: 0;
        }
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-front, .flip-card-back {
            --glow-color: rgb(115, 176, 255);
            --glow-spread-color: rgba(72, 136, 233, 0.481);
            --card-color: rgba(27, 27, 27, 0.569);
            box-shadow: 0 0 1em 0.25em var(--glow-color),
                        0 0 4em 1em var(--glow-spread-color),
                        inset 0 0 0.75em 0.25em var(--glow-color);
            position: absolute;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border: 0.25em solid var(--glow-color);
            border-radius: 1rem;
            transition: all 0.3s;
        }
        .flip-card-front {
            background: linear-gradient(120deg, var(--card-color) 60%, var(--glow-spread-color) 88%,
            var(--card-color) 1000%);
            color: white;
        }
        .flip-card-back {
            background: linear-gradient(120deg, rgb(62, 139, 255) 30%, rgb(41, 92, 169) 88%,
            rgb(41, 92, 169) 40%, rgb(98, 166, 234) 78%);
            color: white;
            transform: rotateY(180deg);
        }
        .flip-card:hover .flip-card-front,
        .flip-card:hover .flip-card-back {
            box-shadow: 0 0 1em 0.25em var(--glow-color),
                        0 0 4em 2em var(--glow-spread-color),
                        inset 0 0 0.75em 0.25em var(--glow-color);
        }
        .flip-card::after {
            pointer-events: none;
            content: "";
            position: absolute;
            top: 120%;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: var(--glow-spread-color);
            filter: blur(2em);
            opacity: 0.7;
            transform: perspective(1.5em) rotateX(35deg) scale(1, 0.6);
        }
        .routine-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
            gap: 30px;
            overflow-x: auto;
            align-content: center;
            padding: 10px;
            background-color: #1c1c1c;
            border-radius: 30px;
            width: 90%;
        }
        .routine-table {
            width: 100%;
            border-radius: 20px;
            text-align: center;
            border-collapse: separate;
            border-spacing: 10px;
            background-color: #484747;
        }
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            color: #333;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .button_out {
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
        .assignment_status{
            
            padding: 20px;
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            backdrop-filter: blur(10px);
            background-color: rgba(0,0,0,0.5);
            padding-top: 60px;
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
                <li><b><a class="menus active" onclick="opentab('Assignment')" href="#">Assign Class Work</a></b></li>
                <li><b><a class="menus" onclick="opentab('Courses')" href="#">Courses</a></b></li>
                <li><b><a class="menus" onclick="opentab('Routine')" href="#">Class Routine</a></b></li>
            </ul>
            <form action="{{route('logout')}}" method="POST">
                @csrf
                
            <button type="submit" class="button_out">Log Out</button>
            </form>
        </div>
        <div class="content">
            <div class="header">
                <h2 class="greeting">⛅Good Afternoon, {{session('user_name')}}</h2>
                
            </div>
            <!--Dashboard-->
            <div class="grid active" id="Dashboard">
                <div class="dash">
                    <div>
                        <h2>Live Classes</h2>
                   
                        @if ($live_classes->isNotEmpty())
                           
                            @foreach ($live_classes as $class)
                                <div class="card">
                                   
                                    <h3>{{ $class->course_code . ': ' . $class->course_name }}</h3>
                                    <p>Section: {{ $class->course_section }}</p>
                                    <p>{{ $class->course_time . ' | ' . $class->course_days }}</p>
                                    <div class="card-footer">                                        
                                        {{-- Display the calculated time difference --}}
                                        <p class="status">Status: {{$class->status}}</p>
                                        {{-- Display the join link --}}
                                        <form action="{{route('cancel_class')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="class_id" value="{{ $class->course_id }}">
                                            <button type="submit" class="button">Cancel</button>
                                        </form>
                                        
                                        <a href="{{ $class->course_link }}" class="button">Join Now</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Message if the collection is empty --}}
                            <p>No upcoming live classes.</p>
                        @endif
                    </div>
                </div>
            </div>
                <!--a little code was here p1-->
            <div class="grid" id="Assignment">
                <h2>Assign a Work to a CLass</h2>
                <form action="{{route('add_edit_class_work')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Course/Section:
                        <select name="class_id" id="classID" >
                        @foreach ($courses as $course)
                            <option value="{{$course->course_id}}">{{$course->course_code}} ({{$course->course_section}})</option>
                        @endforeach    
                        </select>
                </label>
                <input type="file" name="class_work" required>
                
                <input type="date" name="work_due_date" placeholder="Due Date" required><br><br>
                <button type="submit" class="button">Assign Work</button>
                </form>
                <h2>All Assigned Class Works</h2>
                <div class="table-container" style="scrollbar-width: thin; overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Class Work</th>
                                <th>Due Date</th>
                                <th>Submission Status</th>
                                <th>Download Submissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                
                                    <tr>
                                        <td>{{ $course->course_code }} ({{$course->course_section}})</td>
                                        <td>{{ $course->course_name }}</td>
                                        <td>{{ $course->class_work }}</td>
                                        <td>{{ $course->work_due_date }}</td>
                                        <td><button onclick="open_status('{{ $course->course_code . $course->course_section }}')" type="submit" class="button" style=" padding: .5em 1.5em;">View</button></td>
                                        <form action="{{route('download_all_assignment')}}" method="GET">
                                            @csrf
                                            <input type="hidden" name="file" value="Assignments/{{ $course->course_code }}_{{ $course->course_section}}/submission">
                                            
                                        <td><button type="submit" class="button" style=" padding: .5em 1.5em;">Download</button></td>
                                        </form>
                                    </tr>
                                    
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                </div>
            <!--End Dashboard-->
                <div class="grid" id="Courses">
                    <h2>Courses Taken This Semester</h2>
                    <div class="course">
                        @foreach ($courses as $course)
                            <div>
                                <div class="flip-card">
                                    <div class="flip-card-inner">
                                        <div class="flip-card-front">
                                            <p class="title">{{ $course->course_code }}</p>
                                            <br>
                                            <p class="title">{{ $course->course_name }}</p>
                                        </div>
                                        <div class="flip-card-back">
                                            <p class="title">
                                                <p>Course Details: {{ $course->course_description }}</p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            <!--End of Courses-->
           
            <!--Start of routine -->
            <div class="grid" id="Routine">
                <h2>Class Routine</h2>
                <div class="Top row">
                    <div class="routine-container">
                        {{-- Render Days Header --}}
                        @foreach ($days as $day)
                            <div class='routine-table'><p>{{ $day }}</p></div>
                        @endforeach

                        {{-- Render Routine Rows --}}
                        @foreach ($routine as $time_slot => $classes)
                            {{-- Time Slot Cell --}}
                            <div class='routine-table'><p>{{ $time_slot }}</p></div>

                            {{-- Class Cells for Each Day --}}
                            @foreach ($days as $day)
                                {{-- Skip the 'Time' header cell --}}
                                @if ($day !== 'Time')
                                    <div class='routine-table'>
                                        {{-- Check if there's a class for this time slot and day --}}
                                        @isset($classes[$day])
                                            {{-- Access class properties from the model object --}}
                                            <p>{{ $classes[$day]->course_code }}<br>({{ $classes[$day]->course_section }})</p>
                                        @else
                                            {{-- Display placeholder if no class --}}
                                            <p>-</p>
                                        @endisset
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            <!--End of routine-->
        </div> <!--This is the whole body part-->
    </div>
<!--End of container-->
<!--Start of assignment status-->
@foreach ($courses as $course)
<div class="assignment_status" id="{{$course->course_code . $course->course_section}}">
    <h2>Assignment Status</h2> 
    <button class="button_out" style="left:90%x;" onclick="close_stat('{{$course->course_code . $course->course_section}}')">close</button>
    <div class="table-container" style="scrollbar-width: thin; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student name</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    $students=Enrollment::where('course_id', $course->course_id)->get();
                ?>
                @foreach ($students as $student)
                    
                        <tr>
                            <td>{{ $student->student_u_id }}</td>
                            <?php 
                            $name=User::where('u_id', $student->student_u_id)->first();

                            ?>
                            <td>{{ $name->name }}</td>
                            <td>
                                <?php 
                                    $status=Course::where('class_work_link','like', '%' . $name->u_id . '%')->where('course_id',$course->course_id)->first();
                                ?>
                                @if (!empty($status))
                                    <span style="color:green;">Submitted</span>
                                @else
                                    <span style="color:red;">Not Submitted</span>
                                @endif
                            </td>
                            
                            
                        </tr>
                    
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
    <script>
        function close_stat(tabname) {
            var modal = document.getElementById(tabname);
            modal.style.display = "none";
        }
        function open_status(tabname) {
            var modal = document.getElementById(tabname);
            modal.style.display = "block";
        }
        var menust = document.getElementsByClassName("menus");
        var pannels = document.getElementsByClassName("grid");
        function opentab(tabname) {
            for (m of menust) {
                m.classList.remove("active");
            }
            for (p of pannels) {
                p.classList.remove("active");
            }
            event.currentTarget.classList.add("active");
            document.getElementById(tabname).classList.add("active");
        }

   
      
        var span = document.getElementsByClassName("close")[0];

        

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>