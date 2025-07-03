<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    public function policy(){
        $policy = Policy::where('id', 1)->first();
        return response()->json($policy, 200);
    }

    public function terms(){
        $terms = Policy::where('id', 2)->first();
        return response()->json($terms, 200);
    }
}
