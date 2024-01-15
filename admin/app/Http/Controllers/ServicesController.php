<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    function ServiceIndex(){
        return view('service');
    }
    function getServices(){
       $all_services= json_encode(service::orderBy('id','desc')->get());
       return $all_services;
    }
    function DeleteService(Request $request){
       $serviceId = $request->id;
       $result = service::where('id','=',$serviceId)->delete();
       if($result==true){
        return 1;
       }
       else{
        return 0;
       }
    }
    function EditService(Request $request){
       $serviceId = $request->id;
       $result = service::where('id','=',$serviceId)->first();
        return $result;
    }

    public function updateService(Request $request, $id)
    {
        $service = service::where('id','=',$id);
        
        // Update fields based on the received data
        $service->update([
            'service_name' => $request->input('name'),
            'service_des' => $request->input('description'),
            // Add more fields as needed
        ]);
        
        // Optionally, return a success message or updated data
        return response()->json(['message' => 'Service updated successfully']);
    }
    public function addService(Request $request)
    {
        $dataToInsert = [
            'service_name' => $request->name,
            'service_des' => $request->description,
            'service_img' => 'images/custom.svg'
            // Add more columns and values as needed
        ];
        $service = service::insert($dataToInsert);

        
        // Optionally, return a success message or updated data
        return response()->json(['message' => 'Service added successfully']);
    }
}
