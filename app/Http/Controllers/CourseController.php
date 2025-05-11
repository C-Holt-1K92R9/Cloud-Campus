<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\File; 
class CourseController extends Controller
{
    
    

    public function store(Request $request)
    {
        
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
            
            $course = Course::find($request->input('edit_id'));
                $course->course_code = $request->input('course_code');
                $course->course_name = $request->input('course_name');
                $course->course_description = $request->input('course_description');
                $course->course_section = $request->input('course_section');
                $course->course_time = $request->input('course_time');
                $course->course_days = $request->input('course_days');
                $arr= array_map('trim', explode(',', $request->input('course_instructor')));
                $course->course_instructor =$arr[0] ;
                $course->u_id= $arr[1];
                $course->save();
                return view('admin');
    
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
        $arr= array_map('trim', explode(',', $request->input('course_instructor')));
        $course->course_instructor =$arr[0] ;
        $course->u_id= $arr[1];
        $course->save();

        return redirect()->route('redirect');
    }
}

    public function destroy(Request $request)
    {
        $id = $request->input('course_del_id');
        $course = Course::find($id)->first();
        if ($course) {
            $course->delete();
        }
        
        return redirect()->route('redirect');
    }
    public function cancel_class(Request $request)
    {
        $id = $request->input('class_id');
        $course = Course::where('course_id',(int)$id)->first();
        if ($course) {
            $course->status= 'Cancelled';
        }
        $course->save();
  
        return redirect()->route('redirect');
}
public function add_work(Request $request)
{
    $id = $request->input('class_id');
    $course = Course::where('course_id',(int)$id)->first();
    
    
    if ($course) {
        $work_file = $request->file('class_work');
        $folderName = $course->course_code . '_' . $course->course_section;
        $disk = Storage::disk('public'); 
        $folderPath = 'Assignments/' . $folderName."/work";
        $files = $disk->files($folderPath);
        if (!empty($files)) {   
            $disk->delete($files);
        }
        $path = $work_file->storeAs('Assignments/' . $folderName."/work", $work_file->getClientOriginalName(), 'public');
        $course->class_work= $work_file->getClientOriginalName();
        $course->work_due_date= $request->input('work_due_date');
        $course->class_work_link= "";
    }
    $course->save();
    if ($request->input('update_New')=='assign'){
    $files = Storage::disk("public")->files('Assignments/' . $folderName."/submission");
            if (!empty($files)) {   
                Storage::disk("public")->delete($files);
            }
        }
     return redirect()->route('redirect');
}
public function upload_work(Request $request)
{
    $id = $request->input('course_id');
    $student_id = $request->input('student_id');
    $student_name = $request->input('student_name');
    $course = Course::where('course_id',(int)$id)->first();
    
    
    if ($course) {
        $work_file = $request->file('submition_file');
        $folderName = $course->course_code . '_' . $course->course_section;
        $disk = Storage::disk('public'); 
        $folderPath = 'Assignments/' . $folderName."/submission";
        $path = $work_file->storeAs($folderPath, $student_id."_".$student_name.".pdf", 'public');
        $submitted=$course->class_work_link;
        $submitted= $submitted.",".$student_id;
        $course->class_work_link= $submitted;
    }
    $course->save();
    return redirect()->route('redirect');
}
public function downloadAllSubmissions(Request $request)
    {
        $submissionFolderPath = $request->input('file');
        if (empty($submissionFolderPath)) {
             return back()->with('error', 'Folder path not provided.');
        }
        $diskName = 'public';
        $filesInFolder = Storage::disk($diskName)->allFiles($submissionFolderPath);
        if (empty($filesInFolder)) {
            return back()->with('error', 'No submissions found in this folder.');
        }
        $tempZipFileName = 'submissions_' . Str::random(10) . '.zip';
        $tempZipDirectory = public_path('temp_zips');
        $tempZipFilePath = $tempZipDirectory . '/' . $tempZipFileName;
        if (!File::exists($tempZipDirectory)) {
            File::makeDirectory($tempZipDirectory, 0775, true);
        }
        $zip = new ZipArchive;
        if ($zip->open($tempZipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($filesInFolder as $filePath) {
                $fileName = basename($filePath);
                $fileContents = Storage::disk($diskName)->get($filePath);
                $zip->addFromString($fileName, $fileContents);
            }
            $zip->close();

            return response()->download($tempZipFilePath)->deleteFileAfterSend(true); 
        } else {
            return back()->with('error', 'Could not create zip archive.');
        }
    }
}
