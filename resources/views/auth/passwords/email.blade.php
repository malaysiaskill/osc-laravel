@extends('master.single')
@section('title', 'Lupa Kata Laluan')
@section('site.description', 'Lupa Kata Laluan')

@section('content')
<div class="bg-white push-50-t">
    <div class="content content-boxed overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="push-50 animated fadeIn">
                    <!-- Reset Password Title -->
                    <div class="text-center">
                        <img src="{{ asset('/img/logo.png') }}" width="100">
                        <p class="text-muted push-10-t font-w300">Sistem Bersepadu Juruteknik Komputer Negeri Perak (JTKPK)</p>
                    </div>
                    <!-- END Reset Password Title -->

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Reset Password Form -->
                    <form class="js-validation-reminder form-horizontal push-30-t" action="{{ url('/password/email') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                    <label for="email">Alamat E-mel</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group push-30-t">
                            <div class="col-xs-6">
                                <button class="btn btn-block btn-primary" type="submit">
                                    <i class="fa fa-send push-5-r"></i>Reset Kata Laluan
                                </button>
                            </div>
                            <div class="col-xs-6">
                                <a href="{{ url('/login') }}" class="btn btn-block btn-success">
                                    <i class="fa fa-sign-in push-5-r"></i>Log Masuk
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- END Reset Password Form -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection