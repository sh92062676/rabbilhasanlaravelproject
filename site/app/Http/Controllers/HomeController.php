<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Course;
use App\Models\message;
use App\Models\Project;
use App\Models\service;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function HomeIndex(){
        $UserIP=$_SERVER['REMOTE_ADDR'];
    // Date Time
    date_default_timezone_set("Asia/Dhaka");
    $timeDate= date("Y-m-d h:i:sa");

    Visitor::insert(['ip_address'=> $UserIP, 'visit_time'=> $timeDate]);

    $courses = json_decode(Course::orderBy('id','desc')->limit(6)->get());
    $Project = json_decode(Project::orderBy('id','desc')->limit(6)->get());
    $Blog = json_decode(Blog::orderBy('id','desc')->limit(3)->get());

    $services = json_decode(service::all());
    
    return view('home',['services'=> $services, 'courses'=>$courses, 'projects'=> $Project, 'blogs'=>$Blog]);
    }

    function MessageIndex(Request $request){
        $dataToInsert = [
            'name'=> $request->name,
            'mobile'=> $request->mobile,
            'email'=> $request->email,
            'msg'=> $request->msg
        ];
        $insert = message::insert($dataToInsert);
        if($insert){
            return 'Your Message Sent Successfully';
        }
        else{
            return 'Message Sent Faild';
        }
    }
}
