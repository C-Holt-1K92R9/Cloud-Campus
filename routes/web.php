<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view("admin");
});
// For student management
Route::post('/students/edit', [StudentController::class, 'store'])->name('student_edit');
Route::post('/students/del', [StudentController::class, 'destroy'])->name('student_del');
Route::get('/student', [StudentController::class, 'index'])->name('student.index');
// For faculty management
Route::post('/faculty/edit', [FacultyController::class, 'store'])->name('faculty_edit');
Route::post('/faculty/del', [FacultyController::class, 'destroy'])->name('faculty_del');
Route::get('/faculty', [FacultyController::class, 'index'])->name('faculty.index');
// For course management
Route::post('/course/edit', [CourseController::class, 'store'])->name('course_edit');
Route::post('/course/del', [CourseController::class, 'destroy'])->name('course_del');
Route::get('/course', [CourseController::class, 'index'])->name('course.index');