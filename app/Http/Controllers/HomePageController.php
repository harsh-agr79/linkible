<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\Process;
use App\Models\MetaTag;

class HomePageController extends Controller
{
    public function homepage(Request $request){
        $data = [
            'features' => Feature::all(),
            'processes' => Process::all(),
            'meta_tags' => MetaTag::where('slug', 'home')->first() 
        ];

        return response()->json($data, 200);
    }
}
