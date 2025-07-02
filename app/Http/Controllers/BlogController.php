<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog; // Assuming you have a Blog model

class BlogController extends Controller
{
    public function blogs(Request $request)
    {
        $blogs = Blog::where('type', 'blog')->orderBy('published_at', 'desc')->get();
        return response()->json($blogs);
    }

    public function caseStudies(Request $request)
    {
        $caseStudies = Blog::where('type', 'case_study')->orderBy('published_at', 'desc')->get();
        return response()->json($caseStudies);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return response()->json($blog);
    }
}
