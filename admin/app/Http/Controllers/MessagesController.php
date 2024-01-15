<?php

namespace App\Http\Controllers;

use App\Models\message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    function MessagesIndex(){
        return view('messages');
    }

    function getMessages(){
        $all_messages = message::orderBy('id','desc')->get();
        return response()->json($all_messages);
    }

    function DeleteMessage(Request $request){
        $messageId = $request->id;
        $result = message::where('id','=',$messageId)->delete();
        if($result==true){
         return 1;
        }
        else{
         return 0;
        }
     }
}
