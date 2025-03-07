<?php

use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesAndPermissionController;

Route::get('/', function (User $user) {
    $authuser = Auth::user();
    if ($authuser) {
        $posts = Blog::latest()->simplePaginate(4);
        $comments = Comment::latest()->get();
        return redirect(route('dashboard'));
    } else{
    $posts = Blog::latest()->simplePaginate(4);
    $comments = Comment::latest()->get();
    return view('guest', compact('user', 'posts', 'comments'));
    }
});

Route::get('/home', function (User $user) {
    $authuser = Auth::user();
    if ($authuser) {
        $posts = Blog::latest()->simplePaginate(5);
        $comments = Comment::latest()->get();
        return view('Dashboard', compact('user', 'posts', 'comments'));
    } else {
        return redirect(route('login'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('blogs', BlogController::class)->middleware(['auth', 'verified']);
Route::resource('comments', CommentController::class)->middleware(['auth', 'verified']);


Route::get('/users/roles-and-permission', [RolesAndPermissionController::class, 'index'])->name('users.index');
Route::get('/users/create', [RolesAndPermissionController::class, 'showcreateform'])->name('users.create');
Route::post('/users/create-roles-and-permission', [RolesAndPermissionController::class, 'createrolesandpermission'])->name('users.createroles');
Route::get('/users/roles-and-permission/set', [RolesAndPermissionController::class, 'set_roles_and_permission'])->name('users.setroles');
Route::post('/users/remove/roles-and-permission', [RolesAndPermissionController::class, 'removeroles'])->name('users.removeroles');
Route::get('/users/roles-and-permission/remove', [RolesAndPermissionController::class, 'index'])->name('users.showremoveroles');
Route::post('/users/set-roles-and-permission', [RolesAndPermissionController::class, 'setroles'])->name('users.setroles');
Route::get('/users/roles-and-permission/set', [RolesAndPermissionController::class, 'index'])->name('users.showsetroles');


require __DIR__ . '/auth.php';
