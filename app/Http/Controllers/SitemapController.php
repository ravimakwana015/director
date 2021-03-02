<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages\Page;

class SitemapController extends Controller
{
    /**
     * index
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
    	$slug = Page::where('status',1)->get();	
    	return response()->view('sitemap.index', [
    		'slug' => $slug,
    	])->header('Content-Type', 'text/xml');
    }
}
