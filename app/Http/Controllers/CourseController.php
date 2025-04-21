<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;


class CourseController extends Controller
{
    public function index()
    {
        return view('admin');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'course_code' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'course_section' => 'required|string|max:255',
            'course_time' => 'required|string|max:255',
            'course_days' => 'required|string|max:255',
            'course_instructor' => 'required|string|max:255',
        ]);

        if($request->input('edit_id')){
            // Update existing course
            $course = Course::find($request->input('edit_id'));
                $course->course_code = $request->input('course_code');
                $course->course_name = $request->input('course_name');
                $course->course_description = $request->input('course_description');
                $course->course_section = $request->input('course_section');
                $course->course_time = $request->input('course_time');
                $course->course_days = $request->input('course_days');
                $course->course_instructor = $request->input('course_instructor');
                $course->save();
                return redirect()->route('course.index')->with('success', 'Course updated successfully!');
    
        }
        else{
        $course = new Course();
        $course->course_code = $request->input('course_code');
        $course->course_name = $request->input('course_name');
        $course->course_description = $request->input('course_description');
        $course->course_section = $request->input('course_section');
        $course->course_time = $request->input('course_time');
        $course->course_days = $request->input('course_days');
        $course->course_link = "https://meet.jit.si/" . $request->input('course_code') . $request->input('course_section') . bin2hex(random_bytes(10));
        $course->course_instructor = $request->input('course_instructor');
        $course->save();

        return redirect()->route('course.index')->with('success', 'Course created successfully!');
    }
}

    public function destroy(Request $request)
    {
        $id = $request->input('course_del_id');
        $course = Course::find($id)->first();
        if ($course) {
            $course->delete();
        }
        
        return redirect()->route('course.index')->with('success', 'Course deleted successfully!');
    }
}
