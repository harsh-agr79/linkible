<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog; // Assuming you have a Blog model

class BlogController extends Controller
{
   public function blogs(Request $request)
    {
        $pinned = Blog::where('type', 'blog')
            ->where('is_pinned', true)
            ->orderByDesc('published_at')
            ->first();

        // Fallback to latest published blog if no pinned one
        if (!$pinned) {
            $pinned = Blog::where('type', 'blog')
                ->orderByDesc('published_at')
                ->first();
        }

        // Exclude pinned from the list
        $blogs = Blog::where('type', 'blog')
            ->when($pinned, fn ($query) => $query->where('id', '!=', $pinned->id))
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json([
            'pinned' => $pinned,
            'blogs' => $blogs,
        ]);
    }

    public function caseStudies(Request $request)
    {
        $pinned = Blog::where('type', 'case_study')
            ->where('is_pinned', true)
            ->orderByDesc('published_at')
            ->first();

        // Fallback to latest published case study if no pinned one
        if (!$pinned) {
            $pinned = Blog::where('type', 'case_study')
                ->orderByDesc('published_at')
                ->first();
        }

        // Exclude pinned from the list
        $caseStudies = Blog::where('type', 'case_study')
            ->when($pinned, fn ($query) => $query->where('id', '!=', $pinned->id))
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json([
            'pinned' => $pinned,
            'case_studies' => $caseStudies,
        ]);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return response()->json($blog);
    }
}
