<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageApiController extends Controller
{
    public function index()
    {
        $pages = Page::where('status', 'published')
            ->select('id', 'title', 'slug', 'meta_title', 'meta_description')
            ->orderBy('sort_order')
            ->get();

        return response()->json($pages);
    }

    public function show(Page $page)
    {
        if ($page->status !== 'published') {
            abort(404);
        }

        return response()->json($page);
    }
}
