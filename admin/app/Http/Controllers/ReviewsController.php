<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ReviewsController extends Controller
{
    function ReviewsIndex(){
        return view('reviews');
    }

    function getReviews(){
        $all_reviews = Review::orderBy('id','desc')->get();
        return response()->json($all_reviews);
    }
    
    public function addReviews(Request $request)
    {
        $dataToInsert = [
            'name' => $request->name,
            'review' => $request->review,
            'img' => $request->img,
            // Add more columns and values as needed
        ];
        $review = Review::insert($dataToInsert);

        
        // Optionally, return a success message or updated data
        return response()->json(['message' => 'Review added successfully']);
    }

    function EditReviews(Request $request){
        $reviewId = $request->id;
        $review = Review::where('id','=',$reviewId)->first();
         return $review;
     }

     public function UpdateReviews(Request $request, $id)
     {
         $review = Review::where('id','=',$id);
         
         // Update fields based on the received data
         $review->update([
             'name' => $request->name,
             'review' => $request->review,
             'img' => $request->img,
             // Add more fields as needed
         ]);
         
         // Optionally, return a success message or updated data
         return response()->json(['message' => 'Review updated successfully']);
     }

     function DeleteReviews(Request $request){
        $reviewId = $request->id;
        $review = Review::where('id','=',$reviewId)->delete();
        if($review==true){
         return 1;
        }
        else{
         return 0;
        }
     }
}
