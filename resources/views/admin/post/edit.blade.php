@extends('layouts.backend.app')
@section('title','tag')

@push('css')
    <!-- Bootstrap Select Css -->
    <link href="{{asset('assets/backened/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <form action="{{route('admin.post.update',$post->id)}}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add New Post
                            </h2>
                        </div>
                        <div class="body">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="title" class="form-label">Post Title</label>
                                    <input type="text" id="title" class="form-control" name="title" value="{{$post->title}}">
                                </div>
                                <span class="text-danger"> {{$errors->has('title') ? $errors->first('title'):''}} </span>
                            </div>
                            <div class="form-line">
                                <label for="image"> Featured Image</label>
                                <input type="file" name="image">
                                <span class="text-danger"> {{$errors->has('image') ? $errors->first('image'):''}} </span>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="publish" class="filled-in" name="status" value="1"
                                        {{$post->status==true ? 'checked': ''}}>
                                <label for="publish">publish</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Categories and Tags
                            </h2>
                        </div>
                        <div class="body">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="category">Select Category</label>
                                    <select name="categories[]" id="category" class="form-control show-tick" data-live-search="true" multiple>
                                        @foreach($categories as $category)
                                            <option
                                                    @foreach($post->categories as $postCategory)
                                                            {{$postCategory->id == $category->id ? 'selected' : ''}}
                                                    @endforeach()
                                                    value="{{$category->id}}">
                                                {{$category->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="tag">Select Tag</label>
                                    <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true" multiple>
                                        @foreach($tags as $tag)
                                            <option
                                                    @foreach($post->tags as $postCategory)
                                                    {{$postCategory->id == $tag->id ? 'selected' : ''}}
                                                    @endforeach()
                                                    value="{{$tag->id}}">
                                                {{$tag->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger m-t-15 waves-effect" href="{{route('admin.post.index')}}">BACK</a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Body
                            </h2>
                        </div>
                        <div class="body">
                            <textarea id="tinymce" name="body">{{$post->body}}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@push('js')
    <!-- Select Plugin Js -->
    <script src="{{asset('assets/backened/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
    <!-- TinyMCE -->
    <script src="{{asset('assets/backened/plugins/tinymce/tinymce.js')}}"></script>
    <script>
        $(function () {
            //TinyMCE
            tinymce.init({
                selector: "textarea#tinymce",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{asset('assets/backened/plugins/tinymce')}}';
        });
    </script>
@endpush