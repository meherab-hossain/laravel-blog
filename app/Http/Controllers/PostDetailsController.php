<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostDetailsController extends Controller
{
    public function index(){
        $posts=Post::latest()->paginate(4);
        return view('posts-list',compact('posts'));
    }
    public function details($slug){
        $post=Post::where('slug',$slug)->first();
        $blog_key='blog'.$post->id;
        if(!Session::has($blog_key)){
            $post->increment('view_count');
            Session::put($blog_key,1);
        }
        $randomPosts=Post::all()->random(3);
        return view('post-details',compact('post','randomPosts'));
    }
}
