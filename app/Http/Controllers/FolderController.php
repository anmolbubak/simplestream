<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = Folder::all();
        return view('folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $folders = Folder::all();
        return view('folders.create', compact('folders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = new Folder();
        $folder->name = $request->name;
        $folder->slug = Str::slug($request->name);
        $folder->description = $request->description;
        $folder->parent_id = $request->parent_id;
        $folder->save();

        return redirect()->route('dashboard')->with('success', 'Folder created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $folder = Folder::with(['videos', 'children'])->findOrFail($id);
        return view('folders.show', compact('folder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $folder = Folder::findOrFail($id);
        $folders = Folder::where('id', '!=', $id)->get();
        return view('folders.edit', compact('folder', 'folders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = Folder::findOrFail($id);
        $folder->name = $request->name;
        $folder->slug = Str::slug($request->name);
        $folder->description = $request->description;
        $folder->parent_id = $request->parent_id;
        $folder->save();

        return redirect()->route('folders.show', $folder->id)->with('success', 'Folder updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();
        
        return redirect()->route('dashboard')->with('success', 'Folder deleted successfully');
    }
}
