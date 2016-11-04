@extends('master.app')
@section('title', 'Senarai Projek')
@section('site.description', 'Senarai Projek')
@section('app.helper', ",'summernote', 'ckeditor'")

@section('jquery')
$('#Projek').DataTable({ responsive: true });
@endsection

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-users push-15-r"></i> Senarai Projek
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
                    <a href="/dev-team/{{ $kodppd }}" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                        <i class="fa fa-arrow-circle-left"></i>
                    </a>
                    <div class="pull-right">
                        <select name="_ppdsel" id="_ppdsel" data-placeholder="Sila pilih PPD" class="form-control js-select2" onchange="jump('parent',this,1)">
                            <option></option>
                            @foreach (App\PPD::all() as $ppd)
                                <option value="/dev-team">LIHAT SEMUA</option>
                                <option value="/dev-team/{{ $ppd->kod_ppd }}" {{ ($kodppd == $ppd->kod_ppd) ? 'selected':'' }}>{{ $ppd->ppd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="block-content push-30">
                    <div class="pull-left">
                        <h4>
                            <span class="font-w300">Kumpulan : </span>
                            <b>{{ $nama_kumpulan }}</b>
                        </h4>
                    </div>
                    <div class="pull-right">
                        <h4>
                            <span class="font-w300">PPD : </span>
                            <b>{{ $namappd }}</b>
                        </h4>
                    </div>
                </div>
                <div class="block-content">
                    <table id="Projek" class="table table-striped table-bordered responsive h6">
                        <thead>
                            <tr>
                                <th class="text-center">Bil</th>
                                <th class="text-center">Nama Projek</th>
                                <th class="text-center">% Siap</th>
                                <th class="text-center">Jumlah Task</th>
                                <th class="text-center">Jumlah Task (Belum Selesai)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($projek as $prj)
                            <?php $i++; ?>
                            <tr>
                                <td class="text-center">{{ $i }}.</td>
                                <td>
                                    @if (Auth::user()->role == 'leader')
                                    <a href="#" onclick="javascript:EditProjek('{{ $prj->id }}');return false;">
                                        <span class="font-w300 h5 text-primary">{{ $prj->nama_projek }}</span>
                                    </a>
                                    @else
                                        <span class="font-w300 h5 text-primary">{{ $prj->nama_projek }}</span>
                                    @endif
                                </td>
                                <td class="text-center h3 font-w300">0 %</td>
                                <td class="text-center h3 font-w300">0</td>
                                <td class="text-center h3 font-w300">0</td>
                                <td class="text-center" width="150">
                                    <a href="#" class="btn btn-primary">
                                        <i class="fa fa-th-list push-5-r"></i>Lihat Semua Task
                                    </a>
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
<!-- END Page Content -->
@endsection
