<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavouritePostListController extends Controller
{
    public function index(){
        $posts=Auth::user()->favourite_posts;
        return view('admin.favourite',compact('posts'));
    }
}
