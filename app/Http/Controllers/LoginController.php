<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Course; // Assuming Course model is used for routine
use Carbon\Carbon; 
class LoginController extends Controller
{
    public function login_auth(Request $request){
        $user = User::where('email', $request->input('email'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            Session::put('user_id', $user->u_id);
            Session::put('user_name', $user->name);
            Session::put('user_type', $user->type);
            
            $type=$user->type;
            if ($type=="admin"){
            return redirect('/admin'); 
            }
            else if ($type=="student"){
                return redirect('/student'); 
            } 
            else if ($type=="faculty"){
                $classes = Course::where('u_id', $user->u_id)->get(); // Assuming 'faculty_id' is the correct column in the Course table

                $days = ['Time', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $routine = [];
                foreach ($classes as $class) {
                    $startTime = Carbon::parse($class->start_time)->format('H:i');
                    $endTime = Carbon::parse($class->end_time)->format('H:i');
                    $time_slot = $startTime . ' - ' . $endTime;
                    $routine[$time_slot][$class->class_days] = $class;
                }
                $courses = Course::where('u_id', session('user_id'))->get();
                $today = date('l');

                $live_classes = Course::where('course_days', 'like', '%' . $today . '%')
                                    ->where('u_id', session('user_id')) // Add this condition
                                    ->get();
                
                return view('/faculty', compact('days', 'routine', 'courses', 'live_classes'));
                
            } 
            
        } 
        
        else {
            return redirect('/')->withErrors(['Invalid credentials']); 
        }
}
public function logout(Request $request) {
    Session::flush(); // Clear all session data
    return redirect('/'); // Redirect to the login page
}
}