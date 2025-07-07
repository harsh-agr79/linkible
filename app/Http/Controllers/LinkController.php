<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    public function getLinksList()
    {
        $links = Link::whereNull('parent_id')
            ->with('childrenRecursive')
            ->get()
            ->map(function ($link) {
                return $this->formatLinkTree($link);
            });

        return response()->json($links);
    }

    private function formatLinkTree(Link $link): array
    {
        return [
            'title' => $link->title,
            'slug' => $link->slug,
            'children' => $link->childrenRecursive->map(fn ($child) => $this->formatLinkTree($child))->toArray(),
        ];
    }

    public function getLinkData(Request $request,$slug){
        $link = Link::where('slug', $slug)->first();

        return response()->json($link);
    }
}
