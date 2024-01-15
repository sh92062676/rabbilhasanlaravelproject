<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    function BlogsIndex(){
        $all_blogs = Blog::orderBy('id','desc')->get();
        return view('allblogs',['blogs'=>$all_blogs]);
    }
}
