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
use Illuminate\Support\Facades\Storage;
Route::get('/admin', function () {
    if (Session::has('user_type')) {
        return view("admin");
    }
    else {
       return  redirect('/');;
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
        if (Session::get('user_type') == 'admin'){
            return redirect('/admin');
        } 
        else if (Session::get('user_type') == 'student') {
            return redirect('/student');
        } 
        else if (Session::get('user_type') == 'faculty') {
            return redirect('/faculty');
        }
        
    }
    return view('login');
});

Route::post('/login', [LoginController::class, 'login_auth'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// for faculty pannel
Route::post('/class/cancel', [CourseController::class, 'cancel_class'])->name('cancel_class');
Route::post('/', function () {
    return  redirect('/');;
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
Route::get('/student',function(){
    if (Session::has('user_type')) {
        return view("student");
    }
    else {
        return redirect('/');
    }
});
Route::get('/download', function (Request $request) {
    $filePath = $request->input('file');
 
    if (Storage::disk('public')->exists($filePath)) {
        
         return Storage::disk('public')->download(
             $filePath, 
             basename($filePath) 
             
         );
    } else {
        abort(404, 'File not found.');
    }

})->name('download_assignment');
route::post('upload',[CourseController::class,'upload_work'])->name('upload_work');
Route::get('/download-all-assignment', [CourseController::class, 'downloadAllSubmissions'])->name('download_all_assignment');