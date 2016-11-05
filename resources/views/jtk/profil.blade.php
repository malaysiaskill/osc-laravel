@extends('master.app')
@section('title', 'Profil')
@section('site.description', 'Profil Juruteknik')

@section('jquery')
    @if (!empty(Auth::user()->gred))
        $('#gred').val("{{ Auth::user()->gred }}").trigger("change");
    @endif

    @if (Auth::user()->role == 'jpn')
        $('#jabatan_jpn').val("{{ Auth::user()->kod_jpn }}").trigger("change");
    @elseif (Auth::user()->role == 'ppd')
        $('#jabatan_jpn').val("{{ Auth::user()->kod_jpn }}").trigger("change");
        $('#jabatan_ppd').val("{{ Auth::user()->kod_ppd }}").trigger("change");
    @elseif (Auth::user()->role == 'leader' || Auth::user()->role == 'user')
        $('#jabatan_jpn').val("{{ Auth::user()->kod_jpn }}").trigger("change");
        $('#jabatan_ppd').val("{{ Auth::user()->kod_ppd }}").trigger("change");
        $('#jabatan').val("{{ Auth::user()->kod_jabatan }}").trigger("change");
    @endif

    $("#btn-delete-avatar").click(function(){
        var ajax = new sack();
        ajax.requestFile = "/avatar/delete";
        Ajx(ajax);
    });

    var UserAvatar = $("#btn-avatar").dropzone({ 
        url: '{{ url('/avatar/upload') }}',
        params: {
            _token: "{{ csrf_token() }}"
        },
        acceptedFiles: 'image/jpg,image/jpeg,image/png',
        maxFilesize: 2,
        maxFiles: 1,
        createImageThumbnails: false,
        previewTemplate : '<div style="display:none"></div>',
        init: function()
        {
            this.on("uploadprogress", function(file, progress, bytesSent) {
                console.log(progress);
            });
            this.on("processing", function(file) {
                $("#btn-avatar").html('<i class="fa fa-cog fa-spin push-5-r"></i>');
            });
            this.on("success", function(file) {
                var ret = file.xhr.response;
                if (ret == "OK") {
                    var randomId = new Date().getTime();
                    $("#btn-avatar").html('Tukar Avatar');
                    $(".img-avatar").attr("src","/avatar?cache="+randomId);
                }
            });
            this.on("complete", function() {
                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                    this.removeAllFiles();
                }
            });
        }
    });
@endsection

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-user push-15-r"></i> Profil
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <form class="js-validation-profile form-horizontal" method="POST" action="/profil">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <div class="row">

            <div class="col-xs-12 remove-margin-b">
                <div class="block block-themed block-rounded">
                    <div class="block-content block-content-full block-content-mini border-b text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save push-5-r"></i> Simpan Maklumat Profil
                        </button>
                    </div>
                </div>
            </div>

            <!-- Avatar -->
            <div class="col-sm-12 col-md-4 col-md-push-8">
                <div class="block block-themed block-rounded">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="content_toggle">
                                    <i class="si si-arrow-up"></i>
                                </button>
                            </li>
                        </ul>
                        <h3 class="block-title">
                            <i class="fa fa-camera push-10-r"></i>Avatar
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group clearfix">
                            <div class="col-xs-12 text-center">
                                <img class="img-avatar img-avatar96" title="{{ Auth::user()->name }}" src="/avatar" width="100"><br><br>
                                <button type="button" id="btn-avatar" class="btn btn-sm btn-primary">
                                    <i class="fa fa-user push-5-r"></i>Tukar Avatar
                                </button>
                                <button type="button" id="btn-delete-avatar" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile -->
            <div class="col-sm-12 col-md-8 col-md-pull-4">
                @if ($status == 'success')
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-check push-5-r text-success"></i> Profil anda telah berjaya dikemaskini
                    </div>
                @endif
                <div class="block block-themed block-rounded">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="content_toggle">
                                    <i class="si si-arrow-up"></i>
                                </button>
                            </li>
                        </ul>
                        <h3 class="block-title">
                            <i class="fa fa-user push-10-r"></i>Profil
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group clearfix items-push border-b">
                            <label class="col-sm-3 control-label" for="name">Nama Penuh</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" id="name" name="name" placeholder="Nama penuh anda" value="{{ Auth::user()->name }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group clearfix items-push border-b">
                            <label class="col-sm-3 control-label" for="email">Alamat E-mel</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="email" id="email" name="email" placeholder="Alamat e-mel anda" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>
                        <div class="form-group clearfix items-push border-b">
                            <label class="col-sm-3 control-label" for="gred">Gred Jawatan</label>
                            <div class="col-sm-9">
                                <select id="gred" name="gred" data-placeholder="Sila pilih gred jawatan" class="form-control js-select2" style="width: 100%" required>
                                    <option></option>
                                    @foreach (App\Gred::all() as $gred)
                                        <option value="{{ $gred->id }}">{{ $gred->gred }} - {{ $gred->nama_jawatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label" for="jabatan_jpn">Jabatan (JPN)</label>
                            <div class="col-sm-9">
                                <select id="jabatan_jpn" name="jabatan_jpn" data-placeholder="Sila pilih jabatan" class="form-control js-select2" style="width: 100%" required>
                                    <option></option>
                                    @foreach (App\JPN::all() as $jpn)
                                        <option value="{{ $jpn->kod_jpn }}">{{ $jpn->kod_jpn }} - {{ $jpn->jpn }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (Auth::user()->role == 'ppd' || Auth::user()->role == 'leader' || Auth::user()->role == 'user')
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label" for="jabatan_ppd">Jabatan (PPD)</label>
                            <div class="col-sm-9">
                                <select id="jabatan_ppd" name="jabatan_ppd" data-placeholder="Sila pilih jabatan" class="form-control js-select2" style="width: 100%" required>
                                    <option></option>
                                    @foreach (App\PPD::all() as $ppd)
                                        <option value="{{ $ppd->kod_ppd }}">{{ $ppd->kod_ppd }} - {{ $ppd->ppd }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        @if (Auth::user()->role == 'leader' || Auth::user()->role == 'user')
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label" for="jabatan">Jabatan</label>
                            <div class="col-sm-9">
                                <select id="jabatan" name="jabatan" data-placeholder="Sila pilih jabatan" class="form-control js-select2" style="width: 100%" required>
                                    <option></option>
                                    @foreach (App\Sekolah::all() as $sek)
                                        <option value="{{ $sek->kod_sekolah }}">{{ $sek->kod_sekolah }} - {{ $sek->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="block block-themed block-rounded">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="content_toggle">
                                    <i class="si si-arrow-up"></i>
                                </button>
                            </li>
                        </ul>
                        <h3 class="block-title">
                            <i class="fa fa-lock push-10-r"></i>Tukar Kata Laluan
                        </h3>
                    </div>
                    <div class="block-content block-content-full block-content-mini border-b">
                        <span class="text-danger">Biarkan kosong jika tiada pertukaran pada kata laluan anda.</span>
                    </div>
                    <div class="block-content">
                        <div class="form-group clearfix items-push border-b">
                            <label class="col-sm-3 control-label" for="pwd">Kata Laluan Baru</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="pwd" name="pwd" placeholder="Kata laluan baru anda">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label" for="rpwd">Sahkan Kata Laluan</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="rpwd" name="rpwd" placeholder="Sahkan kata laluan baru anda">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END Page Content -->
@endsection
