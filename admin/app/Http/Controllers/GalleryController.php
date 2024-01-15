<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    function GalleryIndex(){
        return view('gallery');
    }
    function PhotoJson(){
        return Photo::take(12)->get();
    }
    function PhotoJsonById(Request $request){
        $firstId = $request->id;
        $lastId = $firstId+12;
        return Photo::where('id','>=',$firstId)->where('id','<',$lastId)->get();
    }
    function PhotoUpload(Request $request){
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $newFileName = 'Gallery_Photo_' . time() . '.' . $file->getClientOriginalExtension(); // Generate a custom name
    
            // Store the file with the custom name
            $photoPath = $file->storeAs('public', $newFileName);
            $photoName = explode('/',$photoPath)[1];

            $host = $_SERVER['HTTP_HOST'];
            $location = "http://".$host."/storage/".$photoName;
            $result = Photo::insert(['location'=> $location]);
            return $result; // Return the path where the file is stored
        }
        
        return "No file uploaded"; // Return a message if no file was uploaded
    }

    function PhotoDelete(Request $request){
        $id = $request->input('OldPhotoId');
        $location = $request->input('OldPhotoURL');
    
        $oldPhotoURL = explode('/', $location);
        $oldPhoto = end($oldPhotoURL);
    
        // Constructing the file path for deletion
        $deletePhotoFile = Storage::delete('public/'.$oldPhoto);
    
        if (!$deletePhotoFile) {
            return response()->json(['success' => false, 'error' => 'Failed to delete photo file']);
        }
    
        $deleteRow = Photo::where('id', $id)->delete();
    
        if ($deleteRow) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'Failed to delete photo entry from database']);
        }
    }
    
    
}
