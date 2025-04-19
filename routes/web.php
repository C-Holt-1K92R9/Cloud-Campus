<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view("admin");
});
Route::post('/students/edit', [StudentController::class, 'store'])->name('student_edit');
Route::post('/students/del', [StudentController::class, 'destroy'])->name('student_del');
Route::get('/student', [StudentController::class, 'index'])->name('student.index');
