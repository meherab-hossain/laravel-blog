<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{

    public function index()
    {
        $categories=Category::latest()->get();
        return view('admin.category.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:categories',
            'image'=>'required|mimes:jpeg,bmp,png,jpg'
        ]);
        //get image
        $image=$request->file('image');
        $slug=str_slug($request->name);

        //checking and creating the  image directory
        if (!Storage::disk('public')->exists('category/')) {
            Storage::disk('public')->makeDirectory('category/');
        }
        //checking and creating the  imageSmall directory
        if (!Storage::disk('public')->exists('category/slider/')) {
            Storage::disk('public')->makeDirectory('category/slider/');
        }

        if(isset($image)){
            //unique name for image
            $currentDate=Carbon::now()->toDateString();
            $imageName=$slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            /*//image path
            $imageCategory='storage/category/';
            $imageCategorySlider='storage/category/slider/';

            //image url
            $imageCategoryUrl=$imageCategory.$imageName;
            $imageCategorySliderUrl=$imageCategorySlider.$imageName;

            //image saving in the directory
            Image::make($image)->resize(1600,479)->save($imageCategoryUrl);
            Image::make($image)->resize(500,333)->save($imageCategorySliderUrl);*/

            $categoryImage=Image::make($image)->resize(1600,479)->stream();
            $categorySliderImage=Image::make($image)->resize(500,333)->stream();

            Storage::disk('public')->put('category/'.$imageName,$categoryImage);
            Storage::disk('public')->put('category/slider/'.$imageName,$categorySliderImage);


        }
        else{
            $imageName="default.png";
        }
        //saving image in the database
        $category=new Category();
        $category->name=$request->name;
        $category->slug=$slug;
        $category->image=$imageName;
        $category->save();

        Toastr::success('Category saved successfully', 'Success');
        return redirect()->route('admin.category.index');
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category=Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'image'=>'mimes:jpeg,bmp,png,jpg'
        ]);

        //get image
        $image=$request->file('image');
        $slug=str_slug($request->name);
        $category=Category::find($id);



        if(isset($image)){

            //unique name for image
            $currentDate=Carbon::now()->toDateString();
            $imageName=$slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //checking and creating the  image directory
            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }


            //checking and creating the  image Slider directory
            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            //delete old image
            if (Storage::disk('public')->exists('category/'.$category->image)) {
                Storage::disk('public')->delete('category/'.$category->image);
            }

            //delete old  Slider image
            if (Storage::disk('public')->exists('category/slider/'.$category->image)) {
                Storage::disk('public')->delete('category/slider/'.$category->image);
            }

            //category image resize and saved in the directory
            $categoryImage=Image::make($image)->resize(1600,479)->stream();
            Storage::disk('public')->put('category/'.$imageName,$categoryImage);

            //category image slider resize and saved in the directory
            $categorySliderImage=Image::make($image)->resize(500,333)->stream();
            Storage::disk('public')->put('category/slider/'.$imageName,$categorySliderImage);

        }
        else{
            $imageName=$category->image;
        }

        //saving image in the database
        $category->name=$request->name;
        $category->slug=$slug;
        $category->image=$imageName;
        $category->save();

        Toastr::success('Category updated successfully', 'Success');
        return redirect()->route('admin.category.index');
    }

    public function destroy($id)
    {
        $category=Category::find($id);
        //delete old image
        if (Storage::disk('public')->exists('category/'.$category->image)) {
            Storage::disk('public')->delete('category/'.$category->image);
        }

        //delete old  Slider image
        if (Storage::disk('public')->exists('category/slider/'.$category->image)) {
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        $category->delete();
        Toastr::success('Category deleted successfully', 'Success');
        return redirect()->route('admin.category.index');
    }
}
