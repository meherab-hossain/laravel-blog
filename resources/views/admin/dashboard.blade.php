@extends('layouts.backend.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in as <strong>{{Auth::user()->name}}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <!-- Morris Plugin Js -->
    <script src="{{asset('assets/backened/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/morrisjs/morris.js')}}"></script>

    <!-- ChartJs -->
    <script src="{{asset('assets/backened/plugins/chartjs/Chart.bundle.js')}}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{asset('assets/backened/plugins/flot-charts/jquery.flot.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/flot-charts/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/flot-charts/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/flot-charts/jquery.flot.categories.js')}}"></script>
    <script src="{{asset('assets/backened/plugins/flot-charts/jquery.flot.time.js')}}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{asset('assets/backened/plugins/jquery-sparkline/jquery.sparkline.js')}}"></script>

    <script src="{{asset('assets/backened/js/pages/index.js')}}"></script>

@endpush