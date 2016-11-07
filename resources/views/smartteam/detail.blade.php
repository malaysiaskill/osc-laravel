@extends('master.app')
@section('title', 'Detail Kumpulan SMART Team')
@section('site.description', 'Detail Kumpulan SMART Team')
@section('app.helper', ",'summernote', 'ckeditor'")

@section('jquery')
@if (Auth::user()->role == 'leader')

@endif
@endsection

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-ambulance push-15-r"></i> <span class="font-w300">Detail :</span> {{ $st->nama_kumpulan }}
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-xs-12">
            <div class="block block-themed block-rounded push-5">
                <div class="block-content block-content-full block-content-mini border-b bg-gray-lighter clearfix">
                    <a href="/smart-team/{{ $st->kod_ppd }}" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                        <i class="fa fa-arrow-circle-left"></i>
                    </a>
                    @if (Auth::user()->role == 'leader' || Auth::user()->id == $st->ketua_kumpulan)
                        <button type="button" class="btn btn-success" onclick="javascript:AddAktivitiDialog();" data-toggle="tooltip" title="Tambah Aktiviti SMART Team">
                            <i class="fa fa-plus push-5-r"></i><i class="fa fa-bicycle"></i>
                        </button>
                    @endif
                    <div class="pull-right">
                        
                    </div>
                </div>
                
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="block block-bordered">
                                <div class="block-header bg-gray-lighter">
                                    <h3 class="block-title">
                                        <i class="fa fa-users push-10-r"></i>Senarai Ahli Kumpulan
                                    </h3>
                                </div>
                                <div class="block-content">
                                    <ul class="nav-users push">
                                        @foreach ($st->ahli as $ahli)
                                        <?php $_user = App\User::find($ahli); ?>
                                        <li>
                                            <a href="#">
                                                <img class="img-avatar img-avatar48" src="/avatar/{{ $_user->id }}" title="{{ $_user->name }}">
                                                {{ $_user->name }}
                                                <div class="font-w300 text-muted"><small><i class="fa fa-envelope push-5-r"></i>{{ $_user->email }}</small></div>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if ($st->aktiviti->count() == 0)
                                <div class="panel panel-primary text-center padding-20-all h6 font-w300">
                                    - Tiada aktiviti buat masa ini -
                                </div>
                            @else
                                @foreach ($st->aktiviti as $xtvt)
                                <div class="block block-bordered">
                                    <div class="block-header bg-gray-lighter">
                                        <h3 class="block-title">
                                            <i class="fa fa-bicycle push-10-r"></i>{{ $xtvt->nama_aktiviti }}
                                        </h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="row items-push">
                                            <label class="col-sm-12 h5 font-w300 push-5">Objektif Aktiviti :</label>
                                            <div class="col-sm-12">
                                                <div id="v_objektif" class="h6 panel panel-primary padding-10-all remove-margin-b">
                                                    <?php $objektif = \Emojione\Emojione::toImage($xtvt->objektif); echo nl2br($objektif); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row items-push">
                                            <label class="col-sm-12 h5 font-w300 push-5">Keterangan Aktiviti :</label>
                                            <div class="col-sm-12">
                                                <div id="v_detail" class="h6 panel panel-primary padding-10-all remove-margin-b">
                                                    <?php $detail = \Emojione\Emojione::toImage($xtvt->detail); echo nl2br($detail); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Auth::user()->role == 'leader' || Auth::user()->id == $st->ketua_kumpulan)
<!-- Aktiviti Dialog //-->
<div id="AktivitiDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/smart-team/aktiviti') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-ambulance push-10-r"></i>Aktiviti : Kumpulan SMART Team
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 h5 font-w300 push-5">Tajuk Aktiviti :</label>
                                    <div class="col-sm-12">
                                        <input type="text" id="_tajuk_xtvt" name="_tajuk_xtvt" class="form-control" maxlength="255" placeholder="Tajuk aktiviti dijalankan..." required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 h5 font-w300 push-5">Sekolah Terlibat :</label>
                                    <div class="col-sm-12">
                                        <select multiple id="_sekolah_terlibat" name="_sekolah_terlibat[]" data-placeholder="Sekolah.." class="form-control js-select2" style="width:100%;" required>
                                            @foreach (App\Sekolah::where('kod_ppd',Auth::user()->kod_ppd)->get() as $sekolah)
                                                <option value="{{ $sekolah->kod_sekolah }}">{{ $sekolah->kod_sekolah }} - {{ $sekolah->nama_sekolah }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 h5 font-w300 push-5">Tarikh Aktiviti Dijalankan :</label>
                                    <div class="col-sm-12">
                                        <div class="input-daterange input-group" data-date-format="dd/mm/yyyy">
                                            <input class="form-control" type="text" id="_tarikh_xtvt_dari" name="_tarikh_xtvt_dari" placeholder="Dari" required>
                                            <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                            <input class="form-control" type="text" id="_tarikh_xtvt_hingga" name="_tarikh_xtvt_hingga" placeholder="Hingga">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group push-5">
                                    <label class="col-sm-12 h5 font-w300 push-5">Juruteknik Terlibat :</label>
                                    <div class="col-sm-12">
                                        <select disabled multiple id="_jtk_terlibat" name="_jtk_terlibat[]" data-placeholder="Juruteknik.." class="form-control js-select2-avatar" style="width:100%;" required>
                                            @foreach (explode(',', trim($st->senarai_jtk,',')) as $user)
                                                <?php $_user = App\User::find($user); ?>
                                                <option value="{{ $_user->id }}">{{ $_user->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="push-5-t">
                                            <label class="css-input css-radio css-radio-primary push-10-r">
                                                <input type="radio" id="_jtk_semua" name="jtk_terlibat_type" value="0" onclick="javascript:$('#_jtk_terlibat').val('').trigger('change');$('#_jtk_terlibat').attr('disabled','disabled');" checked><span></span> Semua
                                            </label>
                                            <label class="css-input css-radio css-radio-primary">
                                                <input type="radio" id="_jtk_adhoc" name="jtk_terlibat_type" value="1" onclick="javascript:$('#_jtk_terlibat').removeAttr('disabled');"><span></span> Ad Hoc
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 h5 font-w300 push-5">Objektif Aktiviti :</label>
                            <div class="col-sm-12">
                                <textarea id="_objektif" name="_objektif" class="form-control js-emojis" placeholder="Objektif aktiviti" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 h5 font-w300 push-5">Detail Aktiviti :</label>
                            <div class="col-sm-12">
                                <textarea id="_detail" name="_detail" class="form-control js-emojis" placeholder="Keterangan detail mengenai aktiviti yang akan dijalankan..." rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_u_save" class="btn btn-primary" type="submit">
                        <i class="fa fa-save push-5-r"></i>Simpan Rekod
                    </button>
                    <button id="btn_u_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearAktivitiDialog();">
                        <i class="fa fa-times push-5-r"></i>Batal
                    </button>                
                    <input id="_smart_team_id" name="_smart_team_id" type="hidden" value="{{ $st->id }}" />
                    <input id="_xtvtid" name="_xtvtid" type="hidden" value="0" />
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<!-- END Page Content -->
@endsection
