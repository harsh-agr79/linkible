<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Industry;

class IndustryController extends Controller
{
    public function getIndustriesList()
    {
       $data = Industry::select('title', 'slug')->get();

        return response()->json($data);
    }

    public function getIndustryData(Request $request,$slug){
        $data = Industry::where('slug', $slug)->first();

        return response()->json($data);
    }
}
