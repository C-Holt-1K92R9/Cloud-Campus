<?php

namespace App\Http\Controllers;

use App\Models\Student; // If you have a Student model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // For password hashing

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all(); // Fetch all students (adjust as needed)
        return view('admin', compact('students')); // Return the view with data
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email',
            'student_number' => 'required|string|max:20',
            'student_department' => 'required|string|max:255',
            'tpass' => 'nullable|string|min:6',
        ]);
        

        if ($request->input('edit_id')) {
            // Update existing student
            $student = Student::find($request->input('edit_id'));
            $student->student_name = $request->input('student_name');
            $student->student_department = $request->input('student_department');
            $student->save();

            if ($request->input('tpass')) {
                // Update password (hash it!)
                // Assuming you have a User model and a relationship
                $student->user->password = Hash::make($request->input('tpass'));
                $student->user->save();
            }
        } else {
            // Create new student
            $student = new Student();
            $student->student_name = $request->input('student_name');
            $student->student_email = $request->input('student_email');
            $student->student_phone = $request->input('student_number');
            $student->student_department = $request->input('student_department');
            $student->save();
        }

        return redirect()->route('student.index')->with('success', 'Student saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        $student->delete();

        return redirect()->route('student.index')->with('success', 'Student deleted successfully!');
    }
}