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
                
                return view('/faculty');
                
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