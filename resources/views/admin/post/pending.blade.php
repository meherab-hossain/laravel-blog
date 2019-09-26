@extends('layouts.backend.app')
@section('title','post')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{asset('assets/backened/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">

@endpush

@section('content')
    <div class="container-fluid">
        <div class="block-header">

        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                           All post
                           <span class="badge bg-blue">{{$posts->count()}}</span>
                        </h2>

                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>title</th>
                                    <th>Author</th>
                                    <th>
                                        <i class="material-icons">visibility</i>
                                    </th>
                                    <th>Is Approved</th>
                                    <th>Status</th>
                                    <th>Created_at</th>
                                    {{--<th>Updated_at</th>--}}
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{str_limit($post->title,'10')}}</td>
                                        <td>{{$post->user->name}}</td>
                                        <td>{{$post->view_count}}</td>
                                        <td>
                                            @if($post->is_approved==true)
                                                <span class="badge bg-blue">approved</span>
                                            @else
                                                <span class="badge bg-pink">pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->status==true)
                                                <span class="badge bg-blue">approved</span>
                                            @else
                                                <span class="badge bg-pink">pending</span>
                                            @endif
                                        </td>
                                        <td>{{$post->created_at}}</td>
                                        {{--<td>{{$post->updated_at}}</td>--}}
                                        <td>
                                            @if($post->is_approved == false)
                                                <button type="button" class="btn btn-success waves-effect" onclick="approvePost({{$post->id}})">
                                                    <i class="material-icons">done</i>
                                                </button>
                                                <form method="post" action="{{route('admin.post.approve',$post->id)}}" id="approval-form" style="display: none">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                            @endif

                                            <a href="{{route('admin.post.show',$post->id)}}" class="btn btn-info waves-effect">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{route('admin.post.edit',$post->id)}}" class="btn btn-info waves-effect">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button class="btn btn-danger waves-effect" type="button" onclick="deletePost({{ $post->id }})">
                                                <i class="material-icons">delete</i>
                                            </button>
                                            <form id="delete-form-{{ $post->id }}" action="{{ route('admin.post.destroy',$post->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                 @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>
@endsection

@push('js')
    <!-- Jquery DataTable Plugin Js -->
    <script src="{{asset('assets/backened/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>

    <script src="{{asset('assets/backened/js/pages/tables/jquery-datatable.js')}}"></script>

    <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    <script type="text/javascript">
        function deletePost(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
        function approvePost(id) {
            swal({
                title: 'Are you sure?',
                text: "You want to approve this post!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('approval-form').submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'post is not approved :)',
                        'info'
                    )
                }
            })
        }
    </script>
@endpush