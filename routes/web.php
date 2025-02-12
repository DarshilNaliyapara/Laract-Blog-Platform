<?php

use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

Route::get('/', function (User $user) {
    $authuser = Auth::user();
    if ($authuser) {
        $posts = Blog::latest()->simplePaginate(4);

        $comments = Comment::latest()->get();

        return view('Dashboard',compact('user','posts','comments'));
    }else{
        return redirect(route('login'));
    }

})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('blogs',BlogController::class)->middleware(['auth', 'verified']);
Route::resource('comments',CommentController::class)->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
