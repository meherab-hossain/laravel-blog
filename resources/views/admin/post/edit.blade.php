@extends('layouts.backend.app')
@section('title','tag')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Edit Post
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{route('admin.post.update',$post->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" id="title" class="form-control" name="name" value="{{$post->title}}">
                                    <label for="title" class="form-label">Post title</label>
                                </div>
                                <span class="text-danger"> {{$errors->has('title') ? $errors->first('title'):''}} </span>
                            </div>
                            <div class="form-group form-float">
                                    <input type="file" id="image" class="form-control" name="image" value="{{$category->image}}">
                                <span class="text-danger"> {{$errors->has('image') ? $errors->first('image'):''}} </span>
                            </div>
                            <a type="button" class="btn btn-danger m-t-15 waves-effect" href="{{route('admin.category.index')}}">BACK</a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('css')

@endpush