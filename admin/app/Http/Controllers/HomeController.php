<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Course;
use App\Models\message;
use App\Models\Project;
use App\Models\Review;
use App\Models\service;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function HomeIndex(){
        $totalVisitors = Visitor::count();
        $totalServices = service::count();
        $totalProjects = Project::count();
        $totalCourses = Course::count();
        $totalBlogs = Blog::count();
        $totalmessages = message::count();
        $totalreviews = Review::count();
        return view('home',[
            'totalVisitors'=>$totalVisitors,
            'totalServices'=>$totalServices,
            'totalProjects'=>$totalProjects,
            'totalCourses'=>$totalCourses,
            'totalBlogs'=>$totalBlogs,
            'totalmessages'=>$totalmessages,
            'totalreviews'=>$totalreviews,
        ]);
    }


}
