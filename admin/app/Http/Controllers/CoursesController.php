<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    function CoursesIndex(){
        return view('courses');
    }
    function getCourses(){
        $all_courses = Course::orderBy('id','desc')->get();
        return response()->json($all_courses);
    }

     function DeleteCourse(Request $request){
        $courseId = $request->id;
        $result = Course::where('id','=',$courseId)->delete();
        if($result==true){
         return 1;
        }
        else{
         return 0;
        }
     }

     function EditCourse(Request $request){
        $courseId = $request->id;
        $result = Course::where('id','=',$courseId)->first();
         return $result;
     }

     public function updateCourse(Request $request, $id)
     {
         $course = Course::where('id','=',$id);
         
         // Update fields based on the received data
         $course->update([
             'course_name' => $request->name,
             'course_des' => $request->description,
             'course_fee' => $request->fee,
             'course_totalenroll' => $request->totalenroll,
             'course_totalclass' => $request->totalclass,
             'course_link' => $request->link
             // Add more fields as needed
         ]);
         
         // Optionally, return a success message or updated data
         return response()->json(['message' => 'Course updated successfully']);
     }

     public function addCource(Request $request)
     {
         $dataToInsert = [
             'course_name' => $request->name,
             'course_des' => $request->description,
             'course_img' => 'images/custom.svg',
             'course_fee'=> $request->fee,
             'course_totalenroll'=> $request->totalenroll,
             'course_totalclass'=> $request->totalclasses,
             'course_link'=>$request->courselink
             // Add more columns and values as needed
         ];
         $course = Course::insert($dataToInsert);
 
         
         // Optionally, return a success message or updated data
         return response()->json(['message' => 'Course added successfully']);
     }
}
