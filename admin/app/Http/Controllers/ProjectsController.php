<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    function ProjectsIndex(){
        return view('project');
    }

    function getProjects(){
        $all_project = Project::orderBy('id','desc')->get();
        return response()->json($all_project);
    }
    public function addProject(Request $request)
    {
        $dataToInsert = [
            'project_name' => $request->name,
            'project_des' => $request->description,
            'project_img' => $request->image,
            'project_link'=> $request->link,
            // Add more columns and values as needed
        ];
        $project = Project::insert($dataToInsert);

        
        // Optionally, return a success message or updated data
        return response()->json(['message' => 'Project added successfully']);
    }

    function DeleteProject(Request $request){
        $projectId = $request->id;
        $result = Project::where('id','=',$projectId)->delete();
        if($result==true){
         return 1;
        }
        else{
         return 0;
        }
     }

     function EditProject(Request $request){
        $projectId = $request->id;
        $result = Project::where('id','=',$projectId)->first();
         return $result;
     }

     public function updateProject(Request $request, $id)
     {
         $project = Project::where('id','=',$id);
         
         // Update fields based on the received data
         $project->update([
             'project_name' => $request->name,
             'project_des' => $request->description,
             'project_link' => $request->link,
             'project_img' => $request->image,
             // Add more fields as needed
         ]);
         
         // Optionally, return a success message or updated data
         return response()->json(['message' => 'Project updated successfully']);
     }
}
