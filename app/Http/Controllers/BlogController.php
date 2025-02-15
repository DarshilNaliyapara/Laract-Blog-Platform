<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    public function index(Blog $blog, User $user)
    {

        if (!auth()->check()) {
            return redirect(route('dashboard'));
        }
        $posts = Auth::user()->blog()->with('user')->latest()->simplePaginate(2);
        // $comments = Auth::user()->blog()->comments()->with('user')->latest();

        return view('blogs', compact('user', 'posts', 'blog'));

    }
    public function edit(Blog $blog, User $user)
    {
        if (!auth()->check()) {
            return redirect(route('dashboard'));
        }
        if (!Gate::allows('update-post', $blog)) {
            abort(403);
        }

        $val = json_decode($blog->posts, true);
        $posts = Auth::user()->blog()->with('user')->latest()->simplePaginate(2);
        return view('blogs', compact('user', 'posts', 'blog', 'val'));


    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|min:5|string',
            'post' => 'required|min:15|string',
            'file' => 'mimes:jpeg,jpg,png|max:3000',
        ]);
        logger($validated);
        $blog = $request->user()->blog()->create(['posts' => json_encode($validated)]);
        $file = $request->file('file');
        if($file){
        $name = $file->getClientOriginalName();
        $path = $request->file('file')->storeAs('files', $name, 'public');
        $url = Storage::url($path);
        logger(asset($url));

        
        logger($blog);
        register_shutdown_function(function () use ($blog, $path) {
            $blog->photos()->create([
                'photo_name' => $path,
            ]);
        });
    }

        return redirect(route('dashboard'));

    }
    public function update(Request $request, Blog $blog)
    {

        $validated = $request->validate([
            'title' => 'required|min:5|string',
            'post' => 'required|min:15|string',
        ]);

        $blog->update(['posts' => json_encode($validated)]);

        return redirect(route('dashboard'));

    }
    public function show(Blog $blog, User $user)
    {
        $latest = $user->blog()->with('user')->latest()->first();

        return view('dashboard', ['blog' => $blog, 'latest' => $latest]);
    }
    public function destroy(Blog $blog)
    {

        $blog->delete();
        $file = Photo::find($blog->id);
        if (! $file) {
            return redirect(route('dashboard'))->with('error', "File not found.");
        }
        $path = "public/" . $file->photo_name;
        if (Storage::exists($path)) {
            Storage::delete($path);

            $file->delete();
            return redirect(route('dashboard'))->with('status', "File deleted successfully");
        }
       
        return redirect(route('dashboard'));
    }
}
