@extends('master.app')
@section('title', 'Senarai Aduan Kerosakan')
@section('site.description', 'Senarai Aduan Kerosakan Peralatan ICT')

@section('jquery')
$('#data').DataTable({ responsive: true });
@endsection

@section('content')

<script type="text/javascript">
function ClearAKP() {
    $('#_tarikh_aduan').val('');
    $('#_tarikh_aduan').removeAttr('disabled');
    $('#_no_siri_aduan').val('');
    $('#_no_siri_aduan').removeAttr('disabled');
    $('#_nama').val('');
    $('#_email').val('');
    $('#_jawatan').val('');
    $('#_no_telefon').val('');

    @foreach (\App\KategoriKerosakan::where('parent_id','0')->get() as $_kk)
        @foreach (\App\KategoriKerosakan::where('parent_id',$_kk->id)->get() as $_skk)
        $('#_kerosakan_{{ $_skk->id }}').prop('checked', false);
        @if (strtolower($_skk->kategori) == 'lain-lain')
            $('#_lainlain_{{ $_skk->id }}').val('');            
        @endif
        @endforeach
    @endforeach

    $('input[name="_hakmilik"]').prop('checked', false);
    $('input[name="_kategori_aduan"]').prop('checked', false);
    $('input[name="_status_aduan"]').prop('checked', false);
    $('#_lokasi_peralatan').val('');    
    $('#_no_dhm').val('');
    $('#_keterangan_kerosakan').val('');    
    $('#_laporan_tindakan').val('');
    $('#_tarikh_pemeriksaan').val('');
    $('#_tarikh_selesai').val('');

    $('#_id').val('0');
}
</script>

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-wrench push-15-r"></i> Aduan Kerosakan Peralatan ICT
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
                <button type="button" class="btn btn-primary" onclick="javascript:AddAKP();return false;">
                    <i class="fa fa-plus"></i> Tambah Aduan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END Menu -->

<!-- Page Content -->
<div class="content">
    @if ($error == 'already_exists')
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-exclamation push-5-r text-danger"></i> Rekod telah wujud dalam pangkalan data.
        </div>
    @endif                            
    <div class="row">
        <div class="col-xs-12">
            <div id="_users" class="block block-themed block-rounded push-5">
                
                <div class="block-content">
                    <table id="data" class="table table-striped table-bordered responsive h6">
                        <thead>
                            <tr>
                                <th class="text-center">Bil</th>
                                <th class="text-center">Tarikh Aduan</th>
                                <th class="text-center">No. Siri Aduan</th>
                                <th class="text-center">Nama Pengadu</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($akp as $akpd)
                            <?php $i++; ?>
                            <tr>
                                <td class="text-center">{{ $i }}.</td>
                                <td class="text-center">
                                    {{ $akpd->tarikh_aduan_formatted }}
                                </td>
                                <td class="text-center">
                                    <a href="#" onclick="javascript:EditAKP('{{ $akpd->id }}');return false;">
                                        <div class="font-w300 text-primary">
                                            {{ $akpd->no_siri_akp }}
                                        </div>
                                    </a>
                                </td>
                                <td class="text-left">
                                    {{ $akpd->nama }}
                                </td>
                                <td class="text-center">
                                    {{ $akpd->status_aduan_view }}
                                </td>
                                <td class="text-center" width="150">
                                    <button id="btn_print" class="btn btn-sm btn-primary" type="button" onClick="javascript:CetakAKP('{{ $akpd->id }}');return false;">
                                        <i class="fa fa-print push-5-r"></i>Cetak
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit" onclick="javascript:EditAKP('{{ $akpd->id }}');return false;">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Padam" onclick="javascript:PadamAKP('{{ $akpd->id }}');return false;">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
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

<!-- AKP Dialog //-->
<div id="AKPDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/aduan-kerosakan') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-wrench push-10-r"></i>Aduan Kerosakan
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="row push">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <span class="col-sm-12 push-5">Tarikh Aduan :</span>
                                    <div class="col-sm-12">
                                        <input type="text" id="_tarikh_aduan" name="_tarikh_aduan" class="js-datepicker form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" value="{{ date('d/m/Y') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="col-sm-12 push-5">No. Siri Aduan :</span>
                                    <div class="col-sm-12">
                                        <input type="text" id="_no_siri_aduan" name="_no_siri_aduan" class="form-control" maxlength="255" placeholder="No. Siri Aduan" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9 border-l">

                                <div class="block block-themed block-transparent remove-margin-b">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">
                                            <i class="fa fa-user push-10-r"></i>Maklumat Pengadu
                                        </h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Nama Pengadu :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_nama" name="_nama" class="form-control" maxlength="255" placeholder="Nama Pengadu" required>
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Alamat E-mel :</label>
                                            <div class="col-sm-8">
                                                <input type="email" id="_email" name="_email" class="form-control" maxlength="255" placeholder="Alamat E-mel">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Jawatan :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_jawatan" name="_jawatan" class="form-control" maxlength="255" placeholder="Jawatan">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">No. Telefon :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_no_telefon" name="_no_telefon" class="form-control" maxlength="255" placeholder="No. Telefon">
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>

                                <div class="block block-themed block-transparent remove-margin-b">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">
                                            <i class="fa fa-wrench push-10-r"></i>Maklumat Kerosakan
                                        </h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="row push border-b">
                                            @foreach (\App\KategoriKerosakan::where('parent_id','0')->get() as $_kk)
                                            <div class="col-sm-12 h5 font-w700 push-5"><u>{{ $_kk->kategori }}</u></div>
                                                <div class="col-sm-12 push-10 h6">
                                                    <div class="row">
                                                        @foreach (\App\KategoriKerosakan::where('parent_id',$_kk->id)->get() as $_skk)
                                                        <div class="col-sm-4">
                                                            <label class="css-input css-checkbox css-checkbox-primary">
                                                                <input type="checkbox" value="{{ $_skk->id }}" id="_kerosakan_{{ $_skk->id }}" name="_kerosakan_{{ $_skk->id }}"><span></span> {{ $_skk->kategori }}
                                                            </label>
                                                        </div>
                                                        @if (strtolower($_skk->kategori) == 'lain-lain')
                                                            <div class="col-sm-4">                                                            
                                                                <input type="text" id="_lainlain_{{ $_skk->id }}" name="_lainlain_{{ $_skk->id }}" class="form-control input-sm" maxlength="255" placeholder="Jika Lain-Lain">
                                                            </div>
                                                        @endif

                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Hak Milik Peralatan :</label>
                                            <div class="col-sm-8">
                                                <label class="css-input css-radio css-radio-primary push-10-r">
                                                    <input type="radio" value="KERAJAAN" name="_hakmilik" checked><span></span> Kerajaan
                                                </label>
                                                <label class="css-input css-radio css-radio-primary">
                                                    <input type="radio" value="PERSENDIRIAN" name="_hakmilik"><span></span> Persendirian
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Kategori Aduan :</label>
                                            <div class="col-sm-8">
                                                <label class="css-input css-radio css-radio-primary push-10-r">
                                                    <input type="radio" value="BIASA" name="_kategori_aduan" checked><span></span> Biasa
                                                </label>
                                                <label class="css-input css-radio css-radio-primary">
                                                    <input type="radio" value="SEGERA" name="_kategori_aduan"><span></span> Segera
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Lokasi Peralatan :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_lokasi_peralatan" name="_lokasi_peralatan" class="form-control" maxlength="255" placeholder="Lokasi Peralatan">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">No. Siri DHM Peralatan :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_no_dhm" name="_no_dhm" class="form-control" maxlength="255" placeholder="No. Siri DHM Peralatan">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <span class="col-sm-12 push-5">Keterangan Kerosakan/Masalah :</span>
                                            <div class="col-sm-12">
                                                <textarea id="_keterangan_kerosakan" name="_keterangan_kerosakan" class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="block block-themed block-transparent remove-margin-b">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">
                                            <i class="fa fa-wrench push-10-r"></i>Laporan Tindakan
                                        </h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="form-group push">
                                            <span class="col-sm-12 push-5">Laporan Tindakan :</span>
                                            <div class="col-sm-12">
                                                <textarea id="_laporan_tindakan" name="_laporan_tindakan" class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Tarikh Pemeriksaan :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_tarikh_pemeriksaan" name="_tarikh_pemeriksaan" class="js-datepicker form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Tarikh Selesai :</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="_tarikh_selesai" name="_tarikh_selesai" class="js-datepicker form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>
                                        <div class="form-group push">
                                            <label class="col-sm-4 control-label">Status Aduan :</label>
                                            <div class="col-sm-8">
                                                <label class="css-input css-radio css-radio-primary push-10-r">
                                                    <input type="radio" value="SELESAI" name="_status_aduan"><span></span> Selesai
                                                </label>
                                                <label class="css-input css-radio css-radio-primary push-10-r">
                                                    <input type="radio" value="TIDAK SELESAI" name="_status_aduan"><span></span> Tidak Selesai
                                                </label>
                                                <label class="css-input css-radio css-radio-primary">
                                                    <input type="radio" value="HANTAR KE PEMBEKAL" name="_status_aduan"><span></span> Hantar ke Pembekal
                                                </label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_save" class="btn btn-primary" type="submit">
                        <i class="fa fa-save push-5-r"></i>Simpan Rekod
                    </button>
                    <button id="btn_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearAKP();">
                        <i class="fa fa-times push-5-r"></i>Batal
                    </button>                
                    <input id="_id" name="_id" type="hidden" value="0" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
