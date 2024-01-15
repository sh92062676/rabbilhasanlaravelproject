<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    function BlogsIndex(){
        return view('blog');
    }

    function getBlogs(){
        $all_blogs = Blog::orderBy('id','desc')->get();
        return response()->json($all_blogs);
    }

    public function addBlogs(Request $request)
    {
        $dataToInsert = [
            'caption' => $request->name,
            'date' => $request->date,
            'des' => $request->description,
            'link'=> $request->link,
            'img'=> $request->image,
            // Add more columns and values as needed
        ];
        $blog = Blog::insert($dataToInsert);

        
        // Optionally, return a success message or updated data
        return response()->json(['message' => 'Blog added successfully']);
    }

    function EditBlogs(Request $request){
        $blogId = $request->id;
        $blog = Blog::where('id','=',$blogId)->first();
         return $blog;
     }

     public function UpdateBlogs(Request $request, $id)
     {
         $blog = Blog::where('id','=',$id);
         
         // Update fields based on the received data
         $blog->update([
             'caption' => $request->caption,
             'des' => $request->description,
             'date' => $request->date,
             'link' => $request->link,
             'img' => $request->img,
             // Add more fields as needed
         ]);
         
         // Optionally, return a success message or updated data
         return response()->json(['message' => 'Blog updated successfully']);
     }

     function DeleteBlogs(Request $request){
        $blogId = $request->id;
        $result = Blog::where('id','=',$blogId)->delete();
        if($result==true){
         return 1;
        }
        else{
         return 0;
        }
     }
}
