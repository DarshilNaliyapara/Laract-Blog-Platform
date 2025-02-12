<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class BlogController extends Controller
{

    public function index(Blog $blog,User $user)
    {

        if (!auth()->check()) {
            return redirect(route('dashboard'));
        }
            $posts= Auth::user()->blog()->with('user')->latest()->simplePaginate(2);
            // $comments = Auth::user()->blog()->comments()->with('user')->latest();
            return view('blogs',compact('user','posts','blog'));

    }
    public function edit(Blog $blog, User $user)
    {
        if (!auth()->check()) {
            return redirect(route('dashboard'));
        }
        if (! Gate::allows('update-post', $blog)) {
            abort(403);
        }
            $val = json_decode($blog->posts,true);
            $posts = Auth::user()->blog()->with('user')->latest()->simplePaginate(2);
            return view('blogs', compact('user','posts','blog','val'));


    }

    public function store(Request $request, Blog $blog)
    {

        $validated = $request->validate([
            'title' => 'required|min:5|string',
            'post'  => 'required|min:15|string',
        ]);
logger($validated);
        $request->user()->blog()->create(['posts' => json_encode($validated)]);

        return redirect(route('dashboard'));

    }
    public function update(Request $request, Blog $blog)
    {

        $validated = $request->validate([
            'title' => 'required|min:5|string',
            'post'  => 'required|min:15|string',
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
        return redirect(route('dashboard'));
    }
}
