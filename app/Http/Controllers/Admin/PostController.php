<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Notifications\AuthorPostApproved;
use App\Notifications\NewPostNotify;
use App\Post;
use App\Subscriber;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        $posts=Post::latest()->get();
        return view('admin.post.index',compact('posts'));
    }

    public function create()
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.post.create',compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'title'=>'required',
           'image'=>'required',
           'categories'=>'required',
           'tags'=>'required',
           'body'=>'required',
        ]);
        //get image
        $image=$request->file('image');
        $slug=str_slug($request->title);

        //checking and creating the  image directory
        if (!Storage::disk('public')->exists('post')) {
            Storage::disk('public')->makeDirectory('post');
        }
        if(isset($image)){
            //unique name for image
            $currentDate=Carbon::now()->toDateString();
            $imageName=$slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $postImage=Image::make($image)->resize(1600,1046)->stream();

            Storage::disk('public')->put('post/'.$imageName,$postImage);


        }
        else{
            $imageName="default.png";
        }

        $post=new Post();
        $post->title=$request->title;
        $post->user_id=Auth::id();
        $post->slug=$slug;
        $post->image=$imageName;
        $post->body=$request->body;
        if(isset($request->status)){
            $post->status=true;
        }else{
            $post->status=false;
        }
        $post->is_approved=true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);


        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber)
        {
            Notification::route('mail',$subscriber->email)
                          ->notify(new NewPostNotify($post));
        }

        Toastr::success('post added successfully', 'Success');
        return redirect()->route('admin.post.index');
    }

    public function show(Post $post)
    {
        return view('admin.post.show',compact('post'));
    }

    public function edit(Post $post)
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.post.edit',compact('post','categories','tags'));
    }


    public function update(Request $request, Post $post)
    {
        $this->validate($request,[
            'title'=>'required',
            'image'=>'required',
            'categories'=>'required',
            'tags'=>'required',
            'body'=>'required',
        ]);
        //get image
        $image=$request->file('image');
        $slug=str_slug($request->title);


        if(isset($image)){
            //unique name for image
            $currentDate=Carbon::now()->toDateString();
            $imageName=$slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //checking and creating the  image directory
            if (!Storage::disk('public')->exists('post')) {
                Storage::disk('public')->makeDirectory('post');
            }
            if (Storage::disk('public')->exists('post/'.$post->image)) {
                Storage::disk('public')->delete('post/'.$post->image);
            }
            //post image resize and saved in the directory
            $postImage=Image::make($image)->resize(1600,1046)->stream();
            Storage::disk('public')->put('post/'.$imageName,$postImage);


        }
        else{
            $imageName="default.png";
        }

        $post->title=$request->title;
        $post->user_id=Auth::id();
        $post->slug=$slug;
        $post->image=$imageName;
        $post->body=$request->body;
        if(isset($request->status)){
            $post->status=true;
        }else{
            $post->status=false;
        }
        $post->is_approved=true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('post updated successfully', 'Success');
        return redirect()->route('admin.post.index');
    }

    public function pending(){
        $posts = Post::where('is_approved',false)->get();
        return view('admin.post.pending',compact('posts'));
    }


    public function approval($id){
        $post=Post::find($id);

        if($post->is_approved == false){
            $post->is_approved = true;
            $post->save();


            $post->user->notify(new AuthorPostApproved($post));


            //sending notification to subscribers
            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber)
            {
                Notification::route('mail',$subscriber->email)
                    ->notify(new NewPostNotify($post));
            }
            Toastr::success('post approved successfully', 'Success');
        }else{
            Toastr::info('post already approved', 'info');
        }
        return redirect()->back();
    }
    public function destroy(Post $post)
    {
        if (Storage::disk('public')->exists('post/'.$post->image)) {
            Storage::disk('public')->delete('post/'.$post->image);
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
        Toastr::success('post deleted successfully', 'Success');
        return redirect()->back();
    }

}
