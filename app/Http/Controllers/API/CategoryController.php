<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureTokenIsValid;


class categoryController extends Controller
{
    public function create(Request  $request){

        $category = Category::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
        ]);

        if(!$category){
            return response()->json(['msg' => 'error']);
        }
        return response()->json(['msg' => 'added']);
    }

    public function getAll(Request  $request){
        $categories = Category::select("id" ,"name_ar","name_en")->get();
        if($categories){
            return response()->json(['msg' => $categories]);
        }
        return response()->json(['msg' => "no categories to show"]);
    }

    public function update(Request  $request){
        $updated = Category::where("id" ,$request->id)->update([

            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,

        ]);
        if($updated){

            return response()->json(['msg' => 'updated']);
        }
        return response()->json(['msg' => "errore"]);
    }

    public function delete(Request  $request){

        $category = Category::find($request->id);
        if($category){
            $category->delete();
            return response()->json(['msg' => 'delete']);
        }
        return response()->json(['msg' => "errore"]);
    }

    public function getcategByid(Request  $request){

        try {
            // JWTAuth::validator($request->token);
            if($token){
                $category = Category::where("id",$request->id)->get();
            if($category){
                return response()->json(['msg' => $category]);
            }
            return response()->json(['msg' => "errore"]);
            }
            
        } catch(\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }

        
    }

}

