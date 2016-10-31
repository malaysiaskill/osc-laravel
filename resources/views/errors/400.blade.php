@extends('master.single')
@section('title', 'Error 400')
@section('site.description', 'Error 400')

@section('content')
<!-- Error Content -->
<div class="content bg-white text-center pulldown overflow-hidden">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <!-- Error Titles -->
            <h1 class="font-s128 font-w300 text-primary animated bounceInDown">400</h1>
            <h2 class="h3 font-w300 push-50 animated fadeInUp">We are sorry but your request contains bad syntax and cannot be fulfilled.</h2>
            <!-- END Error Titles -->
        </div>
    </div>
</div>
<!-- END Error Content -->
@endsection