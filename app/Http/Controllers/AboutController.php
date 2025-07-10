<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function getAboutUs(Request $request){
        $about = About::first();
        return response()->json($about, 200);
    }
}
