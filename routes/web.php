<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session;
use APP\Http\Models\Enrollment;
use Illuminate\Http\Request;
Route::get('/admin', function () {
    if (Session::has('user_type')) {
        return view("admin");
    }
    else {
        redirect()->route('/');
    }
    
});
// For student management
Route::post('/students/edit', [StudentController::class, 'store'])->name('student_edit');
Route::post('/students/del', [StudentController::class, 'destroy'])->name('student_del');
// For faculty management
Route::post('/faculty/edit', [FacultyController::class, 'store'])->name('faculty_edit');
Route::post('/faculty/del', [FacultyController::class, 'destroy'])->name('faculty_del');

// For course management
Route::post('/course/edit', [CourseController::class, 'store'])->name('course_edit');
Route::post('/course/del', [CourseController::class, 'destroy'])->name('course_del');
// For enrollment management
Route::post('/enrollment/edit', [EnrollmentController::class, 'store'])->name('enrollment_edit');
Route::post('/enrollment/del', [EnrollmentController::class, 'destroy'])->name('enrollment_del');

//login
Route::get('/', function () {
    if (Session::has('user_type')) {
        if (Session::get('user_type') == 'admin') {
            return redirect('/admin');
        } 
        else if (Session::get('user_type') == 'student') {
            return redirect('/student');
        } 
        else if (Session::get('user_type') == 'faculty') {
            return redirect('/faculty');
        }
        return redirect('/admin');
    }
    return view('login');
});

Route::post('/login', [LoginController::class, 'login_auth'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// for faculty pannel
Route::post('/class/cancel', [CourseController::class, 'cancel_class'])->name('cancel_class');
Route::post('/', function () {
    return redirect()->route('/');
})->name('redirect');

Route::get('/faculty', function () {
    if (Session::has('user_type')) {
        return view("faculty");
    }
    else {
        return redirect('/');
    }
    
});

Route::post('/add/work',[CourseController::class,'add_work'])->name('add_edit_class_work');
