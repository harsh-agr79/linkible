<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\MetaTag;
// Assuming you have a Blog model

class BlogController extends Controller {
    public function blogs( Request $request ) {
        $pinned = Blog::where( 'type', 'blog' )
        ->where( 'is_pinned', true )
        ->orderByDesc( 'published_at' )
        ->first();

        // Fallback to latest published blog if no pinned one
        if ( !$pinned ) {
            $pinned = Blog::where( 'type', 'blog' )
            ->orderByDesc( 'published_at' )
            ->first();
        }

        // Manually load recommended posts
        $pinned?->setRelation( 'recommended_posts', $pinned->recommended_posts );

        // Exclude pinned from the list
        $blogs = Blog::where( 'type', 'blog' )
        ->when( $pinned, fn ( $query ) => $query->where( 'id', '!=', $pinned->id ) )
        ->orderBy( 'published_at', 'desc' )
        ->get();

        // Manually load recommended posts for each blog
        $blogs->each ( fn ( $blog ) => $blog->setRelation( 'recommended_posts', $blog->recommended_posts ) );

        return response()->json( [
            'meta_tags' => MetaTag::where('slug', 'blogs')->first(),
            'pinned' => $pinned,
            'blogs' => $blogs,
        ] );
    }

    public function caseStudies( Request $request ) {
        $pinned = Blog::where( 'type', 'case_study' )
        ->where( 'is_pinned', true )
        ->orderByDesc( 'published_at' )
        ->first();

        // Fallback to latest published case study if no pinned one
        if ( !$pinned ) {
            $pinned = Blog::where( 'type', 'case_study' )
            ->orderByDesc( 'published_at' )
            ->first();
        }

        // Manually load recommended posts
        $pinned?->setRelation( 'recommended_posts', $pinned->recommended_posts );

        // Exclude pinned from the list
        $caseStudies = Blog::where( 'type', 'case_study' )
        ->when( $pinned, fn ( $query ) => $query->where( 'id', '!=', $pinned->id ) )
        ->orderBy( 'published_at', 'desc' )
        ->get();

        // Manually load recommended posts for each case study
        $caseStudies->each ( fn ( $blog ) => $blog->setRelation( 'recommended_posts', $blog->recommended_posts ) );

        return response()->json( [
            'meta_tags' => MetaTag::where('slug', 'case-studies')->first(),
            'pinned' => $pinned,
            'case_studies' => $caseStudies,
        ] );
    }

    public function show( $slug ) {
        $blog = Blog::where( 'slug', $slug )->firstOrFail();
        $blog->setRelation( 'recommended_posts', $blog->recommended_posts );

        return response()->json( $blog );
    }

    public function incrementView($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->increment('view_count');

        return response()->json([
            'message' => 'View count incremented',
            // 'view_count' => $blog->view_count
        ]);
    }

}
