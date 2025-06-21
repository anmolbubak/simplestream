<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest()->get();
        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $folders = Folder::all();
        return view('videos.create', compact('folders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-flv,video/x-ms-wmv,video/webm|max:1048576', // 1GB max
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $originalFilename = $file->getClientOriginalName();
            $filename = time() . '_' . $originalFilename;
            $path = $file->storeAs('videos', $filename, 'public');
            
            $video = new Video();
            $video->title = $request->title;
            $video->description = $request->description;
            $video->filename = $filename;
            $video->original_filename = $originalFilename;
            $video->mime_type = $file->getMimeType();
            $video->size = $file->getSize();
            $video->path = $path;
            $video->folder_id = $request->folder_id;
            $video->save();
            
            return redirect()->route('dashboard')->with('success', 'Video uploaded successfully');
        }
        
        return back()->with('error', 'Failed to upload video');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::findOrFail($id);
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video = Video::findOrFail($id);
        $folders = Folder::all();
        return view('videos.edit', compact('video', 'folders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $video = Video::findOrFail($id);
        $video->title = $request->title;
        $video->description = $request->description;
        $video->folder_id = $request->folder_id;
        $video->save();
        
        return redirect()->route('videos.show', $video->id)->with('success', 'Video updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::findOrFail($id);
        
        // Delete the file from storage
        if (Storage::disk('public')->exists($video->path)) {
            Storage::disk('public')->delete($video->path);
        }
        
        $video->delete();
        
        return redirect()->route('dashboard')->with('success', 'Video deleted successfully');
    }
}
