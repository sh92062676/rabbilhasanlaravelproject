<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    function ProjectsIndex(){
        $all_projects = Project::orderBy('id','desc')->get();
        return view('allprojects', ['projects'=>$all_projects]);
    }
}
