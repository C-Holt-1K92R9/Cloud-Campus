<?php

namespace App\Http\Controllers;

use App\Models\Student; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        return view('admin',['tab'=>'student']);
    }

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
            $student = Student::where('u_id', $request->input('edit_id'))->first();
            $user= User::where('u_id', $request->input('edit_id'))->first();

            $user -> name = $request->input('student_name');
            $user -> email = $request->input('student_email');

            $student->student_name = $request->input('student_name');
            $student->student_email = $request->input('student_email');
            $student->student_phone = $request->input('student_number');
            $student->student_department = $request->input('student_department');
            $student->save();

            if ($request->input('tpass')) {
                
                $user->password = Hash::make($request->input('tpass'));
                $user->save();
            }
        } else {
            
            $lastStudentId = DB::table('student')
                ->select(DB::raw("MAX(CAST(SUBSTRING(u_id, 2) AS UNSIGNED)) as max_id"))
                ->first()->max_id;

            $newId = 'S' . ($lastStudentId ? $lastStudentId + 1 : 1);
            $student = new Student();
            $user = new User();
            $user->name = $request->input('student_name');
            $user->email = $request->input('student_email');
            $user->password = Hash::make($request->input('tpass'));
            $user->type = 'student';
            $user->u_id = $newId;
            $user->save();
            $student->student_name = $request->input('student_name');
            $student->u_id = $newId;
            $student->student_email = $request->input('student_email');
            $student->student_phone = $request->input('student_number');
            $student->student_department = $request->input('student_department');
            $student->save();
        }

        return redirect()->route('student.index')->with('success', 'Student saved successfully!');
    }


    public function destroy(Request $request)
    {
        $id = $request->input('s_del_id');
        $student = Student::where('u_id', $id)->first();
        $user_student = user::where('u_id', $id)->first();
        $student->delete();
        $user_student->delete();

        return redirect()->route('student.index')->with('success', 'Student deleted successfully!');
    }
}