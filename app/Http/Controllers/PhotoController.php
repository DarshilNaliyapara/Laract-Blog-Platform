<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Photo $file)
    {
        $files = Photo::all();
        return view('dashboard', ['photos' => $files]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png|max:3000',
        ]);
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $path = $request->file('file')->storeAs('files', $name, 'public');
        $url  = Storage::url($path);
        logger(asset($url));
        $blog = Blog::findOrFail($request->blog_id);

        $blog->photos()->create([
            
        ]);
        return redirect(route('dashboard'))->with('status', "File uploaded successfully");
    }
    public function destroy(string $id)
    {
        $file = Photo::find($id);
        if (! $file) {
            return redirect(route('dashboard'))->with('error', "File not found.");
        }
        $path = "public/" . $file->file_name;
        if (Storage::exists($path)) {
            Storage::delete($path);

            $file->delete();
            return redirect(route('dashboard'))->with('status', "File deleted successfully");
        } else {
            return redirect(route('dashboard'))->with('error', "Failed to  delete File");
        }

    }
}
