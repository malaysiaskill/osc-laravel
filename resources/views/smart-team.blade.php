@extends('master.app')
@section('title', 'Kumpulan SMART Team')
@section('site.description', 'Senarai Kumpulan SMART Team')
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
            <i class="fa fa-ambulance push-15-r"></i> Kumpulan SMART Team
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
                    @if (strlen($kod_ppd) != 0)
                        <a href="/smart-team" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                            <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    @endif
                    @if (Auth::user()->role == 'leader')
                        <button type="button" class="btn btn-success" onclick="javascript:AddSTDialog();" data-toggle="tooltip" title="Tambah Kumpulan SMART Team">
                            <i class="fa fa-plus push-5-r"></i><i class="fa fa-ambulance"></i>
                        </button>
                    @endif
                    <div class="pull-right">
                        <select name="_ppdsel" id="_ppdsel" data-placeholder="Sila pilih PPD" class="form-control js-select2" onchange="jump('parent',this,1)">
                            <option></option>
                            <option value="/smart-team">LIHAT SEMUA</option>
                            @foreach (App\PPD::all() as $_ppd)
                                <option value="/smart-team/{{ $_ppd->kod_ppd }}" {{ ($kod_ppd == $_ppd->kod_ppd) ? 'selected':'' }}>{{ $_ppd->ppd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="block-content">
                    @if (strlen($kod_ppd) != 0)
                        @foreach (App\PPD::where('kod_ppd',$kod_ppd)->get() as $ppd)
                            <div class="block block-rounded block-bordered">
                                <div class="block-header bg-gray-lighter">
                                    <ul class="block-options">
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">{{ $ppd->ppd }}</h3>
                                </div>
                                <div class="block-content">
                                    <?php $smart_team = App\SmartTeam::where('kod_ppd',$ppd->kod_ppd)->get(); ?>
                                    @if ($smart_team->count() == 0)
                                        <center><p>- Tiada Kumpulan SMART Team -</p></center>
                                    @else
                                        <div class="row">
                                            @foreach ($smart_team as $st)
                                            <div class="col-sm-6 col-md-4">
                                                <a class="block block-bordered block-link-hover3 remove-margin-b" href="{{ url('/smart-team/detail/'.$st->id.'') }}">
                                                    <div class="block-content bg-gray-lighter block-content-full text-center">
                                                        <div><i class="fa fa-ambulance fa-3x"></i></div>
                                                        <div class="h5 push-15-t push-5">{{ ucwords($st->nama_kumpulan) }}</div>
                                                        <div class="text-muted push-5"><b>Ketua :</b> {{ $st->ketua->name }}</div>
                                                        <div class="text-muted push-10">
                                                            @foreach (explode(',',$st->senarai_jtk) as $ahli)
                                                                <?php if (strlen($ahli) == 0) { continue; }?>
                                                                <img src="/avatar/{{ $ahli }}" class="img-avatar img-avatar32" data-toggle="tooltip" title="{{ App\User::find($ahli)->name }}"> 
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-6 border-r">
                                                                <h2 class="font-w300">{{ count($st->aktiviti) }}</h2>
                                                                <span class="text-primary"><small>Aktiviti</small></span>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <h2 class="font-w300">{{ $st->jumlah_ahli }}</h2>
                                                                <span class="text-primary"><small>Ahli</small></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                @if (Auth::user()->role == 'leader')
                                                <div class="push-5-t push-10 text-right">
                                                    <button type="button" class="btn btn-xs btn-primary" onclick="javascript:EditSTTeam('{{ $st->id }}');">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-danger" onclick="javascript:DeleteSTTeam('{{ $st->id }}');">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach (App\PPD::all() as $ppd)
                            <div class="block block-rounded block-bordered">
                                <div class="block-header bg-gray-lighter">
                                    <ul class="block-options">
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">
                                        <a href="/smart-team/{{ $ppd->kod_ppd }}">{{ $ppd->ppd }}</a>
                                    </h3>
                                </div>
                                <div class="block-content">
                                    <?php $smart_team = App\SmartTeam::where('kod_ppd',$ppd->kod_ppd)->get(); ?>
                                    @if ($smart_team->count() == 0)
                                        <center><p>- Tiada Kumpulan SMART Team -</p></center>
                                    @else
                                        <div class="row">
                                            @foreach ($smart_team as $st)
                                            <div class="col-sm-6 col-md-4">
                                                <a class="block block-bordered block-link-hover3" href="{{ url('/smart-team/detail/'.$st->id.'') }}">
                                                    <div class="block-content bg-gray-lighter block-content-full text-center">
                                                        <div><i class="fa fa-ambulance fa-3x"></i></div>
                                                        <div class="h5 push-15-t push-5">{{ ucwords($st->nama_kumpulan) }}</div>
                                                        <div class="text-muted push-5"><b>Ketua :</b> {{ $st->ketua->name }}</div>
                                                        <div class="text-muted push-10">
                                                            @foreach (explode(',',$st->senarai_jtk) as $ahli)
                                                                <?php if (strlen($ahli) == 0) { continue; }?>
                                                                <img src="/avatar/{{ $ahli }}" class="img-avatar img-avatar32" data-toggle="tooltip" title="{{ App\User::find($ahli)->name }}"> 
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-6 border-r">
                                                                <h2 class="font-w300">{{ count($st->aktiviti) }}</h2>
                                                                <span class="text-primary"><small>Aktiviti</small></span>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <h2 class="font-w300">{{ $st->jumlah_ahli }}</h2>
                                                                <span class="text-primary"><small>Ahli</small></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->role == 'leader')
<!-- Smart Team Dialog //-->
<div id="STDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/smart-team') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-user push-10-r"></i>Kumpulan SMART Team
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group items-push border-b">
                            <label class="col-sm-4 control-label">Jabatan (PPD) :</label>
                            <div class="col-sm-8">
                                <select id="_kodppd" name="_kodppd" data-placeholder="PPD" class="form-control js-select2" style="width:100%;" required>
                                    <option></option>
                                    @foreach (App\PPD::all() as $ppd)
                                        <option value="{{ $ppd->kod_ppd }}">{{ $ppd->ppd }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group items-push border-b">
                            <label class="col-sm-4 control-label">Nama Kumpulan :</label>
                            <div class="col-sm-8">
                                <input type="text" id="_name" name="_name" class="form-control" maxlength="255" placeholder="Nama Kumpulan" required>
                            </div>
                        </div>
                        <div class="form-group items-push border-b">
                            <label class="col-sm-4 control-label">Ketua Kumpulan :</label>
                            <div class="col-sm-8">
                                <select id="_ketua" name="_ketua" data-placeholder="Ketua Kumpulan" class="form-control js-select2-avatar" style="width:100%;" required>
                                    <option></option>
                                    @foreach (App\User::where('kod_ppd',Auth::user()->kod_ppd)
                                    ->whereIn('role',['user','leader'])->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Senarai Ahli Kumpulan (Termasuk Ketua) :</label>
                            <div class="col-sm-8">
                                <select multiple id="_jtk" name="_jtk[]" data-placeholder="Ahli-Ahli" class="form-control js-select2-avatar" style="width:100%;" required>
                                    @foreach (App\User::where('kod_ppd',Auth::user()->kod_ppd)
                                    ->whereIn('role',['user','leader'])->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_u_save" class="btn btn-primary" type="submit">
                        <i class="fa fa-save push-5-r"></i>Simpan Rekod
                    </button>
                    <button id="btn_u_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearAddGroup();">
                        <i class="fa fa-times push-5-r"></i>Batal
                    </button>                
                    <input id="_gid" name="_gid" type="hidden" value="0" />
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<!-- END Page Content -->
@endsection
