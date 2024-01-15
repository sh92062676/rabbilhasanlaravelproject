<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    function CoursesIndex(){
        $all_courses = Course::orderBy('id','desc')->get();
        return view('allcourses', ['courses'=>$all_courses]);
    }
}
