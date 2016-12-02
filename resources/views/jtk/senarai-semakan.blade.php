@extends('master.app')
@section('title', 'Senarai Semakan')
@section('site.description', 'Senarai Semakan')

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-list-ul push-15-r"></i> Senarai Semakan
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Menu -->
<div class="content padding-5-t bg-white border-b">
    <div class="push-15 push-10-t">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default" href="{{ url('/') }}">
                    <i class="fa fa-home"></i>
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a class="btn btn-primary" href="{{ url('/senarai-semak-harian') }}">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END Menu -->


<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-xs-12">
            
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
                        <i class="fa fa-list-ul push-10-r"></i>Senarai Semakan (SEMUA)
                    </h3>
                </div>
                @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                <div class="block-content block-content-full block-content-mini text-right border-b bg-gray-lighter">
                    <button type="button" class="btn btn-success" onclick="javascript:TambahSemakanDialog('1');">
                        <i class="fa fa-plus push-5-r"></i>Tambah
                    </button>
                </div>
                @endif
                <div class="block-content block-content-mini">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Perkara</th>
                                <th>Cara Pengujian</th>
                                @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                                    <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; ?>
                            @foreach ($ss_semua as $ss)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>{{ $ss->perkara }}</td>
                                <td>{{ $ss->cara_pengujian }}</td>
                                @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="javascript:EditSenaraiSemakan('{{ $ss->id }}');">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        @if ($ss->id != '1')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="javascript:DeleteSenaraiSemakan('{{ $ss->id }}');">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if (!Auth::user()->hasRole('ppd'))
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
                        <i class="fa fa-list-ul push-10-r"></i>Senarai Semakan (Tambahan)
                    </h3>
                </div>
                <div class="block-content block-content-full block-content-mini text-right border-b bg-gray-lighter">
                    <button type="button" class="btn btn-success" onclick="javascript:TambahSemakanDialog('0');">
                        <i class="fa fa-plus push-5-r"></i>Tambah
                    </button>
                </div>
                <div class="block-content block-content-mini">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Perkara</th>
                                <th>Cara Pengujian</th>                                
                                <th></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($ss_user) == 0)
                                <tr>
                                    <td colspan="4" class="text-left">- Tiada Rekod -</td>
                                </tr>
                            @else                            
                                <?php //$i=1; ?>
                                @foreach ($ss_user as $ssu)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ $ssu->perkara }}</td>
                                    <td>{{ $ssu->cara_pengujian }}</td>                                
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="javascript:EditSenaraiSemakan('{{ $ssu->id }}');">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="javascript:DeleteSenaraiSemakan('{{ $ssu->id }}');">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
<!-- END Page Content -->

<!-- Senarai Semakan Dialog //-->
<div id="SenaraiSemakanDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/senarai-semakan') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-list-ul push-10-r"></i>Senarai Semakan
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group items-push border-b">
                            <div class="col-sm-12 push-5">Perkara :</div>
                            <div class="col-sm-12">
                                <textarea name="_perkara" id="_perkara" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group items-push">
                            <div class="col-sm-12 push-5">Cara Pengujian :</div>
                            <div class="col-sm-12">
                                <textarea name="_cara_pengujian" id="_cara_pengujian" class="form-control" rows="5"></textarea>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_u_save" class="btn btn-primary" type="submit">
                        <i class="fa fa-save push-5-r"></i>Simpan Rekod
                    </button>
                    <button id="btn_u_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearSenaraiSemakan();">
                        <i class="fa fa-times push-5-r"></i>Batal
                    </button>                
                    <input id="_id" name="_id" type="hidden" value="0" />
                    <input id="_flag" name="_flag" type="hidden" value="0" />
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
