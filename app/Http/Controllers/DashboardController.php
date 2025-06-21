<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $folders = \App\Models\Folder::whereNull('parent_id')->with('children')->get();
        $videos = \App\Models\Video::whereNull('folder_id')->latest()->get();
        
        return view('dashboard', compact('folders', 'videos'));
    }
}
