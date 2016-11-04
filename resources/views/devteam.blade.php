@extends('master.app')
@section('title', 'Senarai Kumpulan Development Team')
@section('site.description', 'Senarai Kumpulan Development Team')

@section('app.helper', ",'summernote', 'ckeditor'")

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-users push-15-r"></i> Senarai Kumpulan Development Team
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-xs-12">
            <div id="_users" class="block block-themed block-rounded push-5">
                
                <div class="block-content block-content-full block-content-mini border-b bg-gray-lighter clearfix">
                    @if (isset($id) && strlen($id)!=0)
                    <a href="/dev-team" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                        <i class="fa fa-arrow-circle-left"></i>
                    </a>
                    @endif
                    @if (Auth::user()->role == 'leader')
                    <button type="button" class="btn btn-primary" onclick="javascript:AddGroupDialog();" data-toggle="tooltip" title="Tambah Kumpulan DevTeam">
                        <i class="fa fa-plus push-5-r"></i><i class="fa fa-users"></i>
                    </button>
                        @if (Request::is('dev-team/*'))
                            <button type="button" class="btn btn-primary" onclick="javascript:AddProjekDialog();" data-toggle="tooltip" title="Tambah Projek Kumpulan DevTeam">
                                <i class="fa fa-plus push-5-r"></i><i class="fa fa-th-large"></i>
                            </button>
                        @endif
                    @endif
                    <div class="pull-right">
                        <select name="_ppdsel" id="_ppdsel" data-placeholder="Sila pilih PPD" class="form-control js-select2" onchange="jump('parent',this,1)">
                            <option></option>
                            @foreach (App\PPD::all() as $ppd)
                                <option value="/dev-team">LIHAT SEMUA</option>
                                <option value="/dev-team/{{ $ppd->kod_ppd }}" {{ ($id == $ppd->kod_ppd) ? 'selected':'' }}>{{ $ppd->ppd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="block-content">
                    @if (isset($id) && strlen($id)!=0)
                        <center><h3 class="font-w300">{{ $nama_ppd }}</h2></center>
                        <div class="content content-narrow content-full">
                            
                            @foreach (App\DevTeam::where('kod_ppd',$id)->get() as $devteam)
                            <div class="block block-rounded block-bordered">
                                <div class="block-header bg-gray-lighter">
                                    <a name="{{ $devteam->id }}" id="{{ $devteam->id }}"></a>
                                    <div class="block-options-simple">
                                        @if (count($devteam->projek) > 0)
                                            <a href="/dev-team/projek/{{ $devteam->id }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Lihat Projek">
                                                <i class="fa fa-th push-5-r"></i> Lihat Projek ({{ count($devteam->projek) }})
                                            </a>
                                        @endif

                                        @if (Auth::user()->role == 'leader')
                                        <a href="#" onclick="javascript:EditDevTeam('{{ $devteam->id }}');" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit Kumpulan">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="#" onclick="javascript:DeleteDevTeam('{{ $devteam->id }}');" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Padam Kumpulan">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        @endif
                                    </div>
                                    <h3 class="h3 font-w300">{{ $devteam->nama_kumpulan }}</h3>
                                </div>
                                <div class="block-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 120px;"><i class="fa fa-user"></i></th>
                                                    <th>Nama Juruteknik</th>
                                                    <th><i class="fa fa-envelope push-10-r"></i>Alamat E-mel</th>
                                                    <th>Sekolah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (explode(',',$devteam->senarai_jtk) as $userid)
                                                    <?php $_user = App\User::find($userid); ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <img class="img-avatar img-avatar48" src="/avatar/{{ $_user->id }}" title="{{ $_user->name }}">
                                                        </td>
                                                        <td>
                                                            @if ($devteam->ketua_kumpulan == $userid)
                                                                {{ $_user->name }} (<i>Ketua Kumpulan</i>)
                                                            @else
                                                                {{ $_user->name }}
                                                            @endif
                                                        </td>
                                                        <td><a href="mailto:{{ $_user->email }}">{{ $_user->email }}</a></td>
                                                        <td>{{ $_user->jabatan->nama_sekolah_detail }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    @else
                        <?php
                            $dev_team = App\DevTeam::all();
                        ?>
                        @if (count($dev_team) == 0)
                            <center><h2 class="h5 font-w300 push-20">- Tiada Rekod -</h2></center>
                        @else
                        <div class="row">
                            @foreach (App\DevTeam::all() as $devteam)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <a class="block block-bordered block-link-hover3" href="{{ url('/dev-team/'.$devteam->kod_ppd.'') }}">
                                    <div class="block-content block-content-full text-center">
                                        <div><i class="fa fa-users fa-3x"></i></div>
                                        <div class="h4 font-w300 push-15-t push-5">{{ ucwords($devteam->nama_kumpulan) }}</div>
                                        <div class="text-muted push-5"><b>Ketua :</b> {{ $devteam->ketua->name }}</div>
                                        <div class="text-muted push-10">
                                            @foreach (explode(',',$devteam->senarai_jtk) as $ahli)
                                                <img src="/avatar/{{ $ahli }}" class="img-avatar img-avatar32" data-toggle="tooltip" title="{{ App\User::find($ahli)->name }}"> 
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 border-r">
                                                <h2 class="font-w300">{{ count($devteam->projek) }}</h2>
                                                <span class="text-primary"><i class="fa fa-th-list push-5-r"></i> Projek</span>
                                            </div>
                                            <div class="col-xs-6">
                                                <h2 class="font-w300">{{ $devteam->jumlah_ahli }}</h2>
                                                <span class="text-primary"><i class="fa fa-users push-5-r"></i> Ahli</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

@if (Auth::user()->role == 'leader')
<!-- Group Dialog //-->
<div id="GroupDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/dev-team') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-user push-10-r"></i>Kumpulan Development Team
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

    @if (Request::is('dev-team/*'))
    <!-- Projek Dialog //-->
    <div id="ProjekDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-popout">
            <div class="modal-content">
                <form method="post" class="form-horizontal" action="{{ url('/dev-team/projek') }}">
                    {{ csrf_field() }}
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">
                                <i class="fa fa-user push-10-r"></i>Projek : Kumpulan Development Team
                            </h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group items-push border-b">
                                <label class="col-sm-4 control-label">Kumpulan Dev Team :</label>
                                <div class="col-sm-8">
                                    <select id="_devteam" name="_devteam" data-placeholder="Kumpulan Dev Team" class="form-control js-select2" style="width:100%;" required>
                                        <option></option>
                                        @foreach (App\DevTeam::where('kod_ppd',Auth::user()->kod_ppd)->get() as $devteam)
                                            <option value="{{ $devteam->id }}">{{ $devteam->nama_kumpulan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group items-push border-b">
                                <label class="col-sm-4 control-label">Nama Projek :</label>
                                <div class="col-sm-8">
                                    <input type="text" id="_nama_projek" name="_nama_projek" class="form-control" maxlength="255" placeholder="Nama Projek" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 h4 font-w300 push-10">Objektif Projek :</label>
                                <div class="col-sm-12">
                                    <textarea id="_objektif" name="_objektif" class="form-control js-emojis" placeholder="Objektif projek" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 h4 font-w300 push-10">Keterangan Projek :</label>
                                <div class="col-sm-12">
                                    <textarea id="_detail" name="_detail" class="form-control js-emojis" placeholder="Keterangan detail mengenai projek yang akan dilaksanakan..." rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_u_save" class="btn btn-primary" type="submit">
                            <i class="fa fa-save push-5-r"></i>Simpan Rekod
                        </button>
                        <button id="btn_u_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearAddProjek();">
                            <i class="fa fa-times push-5-r"></i>Batal
                        </button>                
                        <input id="_projekid" name="_projekid" type="hidden" value="0" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

@endif
@endsection
