@extends('master.app')
@section('title', 'Dashboard')
@section('site.description', 'Dashboard Sistem Bersepadu JTKPK')

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Dashboard</h1>
        <h2 class="h5 text-white-op animated zoomIn font-w300">Hi, <b>{{ Auth::user()->name }}</b></h2>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="block">
                <div class="block-content block-content-full bg-gray-lighter text-center">
                    MODULES
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
@endsection
