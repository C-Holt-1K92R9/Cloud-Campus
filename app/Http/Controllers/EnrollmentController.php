<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
class EnrollmentController extends Controller
{
 
    public function index()
    {
        return view('admin');
    }

    public function store(Request $request)
    {
        $enrollment = new Enrollment();
        //"<?= $course['course_id'].",".$course['course_id'].",".$course['course_section'].",".$course['course_instructor']"
        // Create new faculty member
        

        $lastId = DB::table('enrollment')
            ->select(DB::raw("MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)) as max_id"))
            ->first()->max_id;

        $newId = 'E' . ($lastId ? $lastId + 1 : 1);

        $array = explode(',', $request->input('course_data'));
        $enrollment->id=$newId;
        $enrollment->student_u_id = $request->input('student_id');
        $enrollment->course_id = trim($array[0]);
        $enrollment->course_code = trim($array[1]);
        $enrollment->course_section = trim($array[2]);
        $enrollment->faculty_initial= trim($array[3]);
        $enrollment->save();
        return redirect()->route('enrollment.index')->with('success', 'Enrollment created successfully!');
    }

    public function destroy(Request $request)
    {
        $enrollment = Enrollment::where('id',$request->input('en_del_id'));
        
        $enrollment->delete();
        return redirect()->route('enrollment.index')->with('success', 'Enrollment deleted successfully!');
    }
}
