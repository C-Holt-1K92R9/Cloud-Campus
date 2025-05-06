<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Faculty; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class FacultyController extends Controller
{
    

    public function store(Request $request)
    {
        if ($request->input('edit_id')) {
            // Update existing faculty
            $faculty = Faculty::where('u_id', $request->input('edit_id'))->first();
            $user= User::where('u_id', $request->input('edit_id'))->first();

            $user -> name = $request->input('faculty_name');
            $user -> email = $request->input('faculty_email');
            if ($request->input('tpass')) {
                $user->password = Hash::make($request->input('tpass'));
            }
            $user->save();
            $faculty->faculty_name = $request->input('faculty_name');
            $faculty->faculty_initial = $request->input('faculty_initial'); 
            $faculty->faculty_email = $request->input('faculty_email');
            $faculty->faculty_phone = $request->input('faculty_number');
            $faculty->faculty_department = $request->input('faculty_department');
            $faculty->save();
        } else {
            
            // Create new faculty member
            $lastFacultyId = DB::table('faculty')
                ->select(DB::raw("MAX(CAST(SUBSTRING(u_id, 2) AS UNSIGNED)) as max_id"))
                ->first()->max_id;

            $newId = 'F' . ($lastFacultyId ? $lastFacultyId + 1 : 1);

            
            $faculty = new Faculty();
            $user = new User();

            $user->u_id = $newId;
            $user->name = $request->input('faculty_name');
            $user->email = $request->input('faculty_email');
            $user->password = Hash::make($request->input('tpass'));
            $user->type = 'faculty'; // Set the role to 'faculty'
            $user->save();

            $faculty->u_id = $newId;
            $faculty->faculty_name = $request->input('faculty_name'); 
            $faculty->faculty_initial = $request->input('faculty_initial'); 
            $faculty->faculty_email = $request->input('faculty_email');
            $faculty->faculty_phone = $request->input('faculty_number');
            $faculty->faculty_department = $request->input('faculty_department');
            $faculty->save();
        }
        return redirect()->route('redirect');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('f_del_id');
        $faculty = Faculty::where('u_id', $id)->first();
        $user_faculty = User::where('u_id', $id)->first();
        if ($faculty) {
            $faculty->delete();
        }
        if ($user_faculty) {
            $user_faculty->delete();
        }
        
        return redirect()->route('redirect');
        
    }
}
