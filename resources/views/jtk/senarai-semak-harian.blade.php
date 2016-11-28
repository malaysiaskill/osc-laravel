@extends('master.app')
@section('title', 'Senarai Semak Harian')
@section('site.description', 'Senarai Semak Harian')

@section('jquery')
jQuery('.js-calendar').fullCalendar({
    firstDay: 1,
    editable: false,
    droppable: false,
    header: {
        left: 'title',
        right: 'prev,next'
    },
    eventRender: function(event, element) {
        element.attr('title', event.tooltip);
    },
    events:
    [
        @if (Auth::user()->hasRole('ppd'))

        @else
            @foreach (\App\SenaraiSemakHarian::where('user_id',Auth::user()->id) as $ssh)
            <?php
                $event = array();
                $tarikh = $ssh->tarikh_semakan;
                $id = $ssh->id;

                $Data_Tarikh = explode(' ', $tarikh);                   
                $DataTarikh = explode('-', $Data_Tarikh[0]);
                $Year = $DataTarikh[0];
                $Mon = $DataTarikh[1]-1;
                $Day = $DataTarikh[2];

                $event[] = "{
                    title: 'Semakan Selesai',
                    icon: \"check\",
                    start: new Date($Year, $Mon, $Day, 0, 0),
                    allDay: true,
                    color: '#26802c',
                    id: '$id',
                    tooltip: 'Semakan Harian Telah Dibuat'
                },";
                $events = implode(',', $event);
                echo $events;
            ?>
            @endforeach
        @endif
    ],
    eventRender: function(event, element) {
        if(event.icon) {
            element.find(".fc-title").prepend("<i class='fa fa-"+event.icon+"'></i> ");
        }
    },
    eventClick: function(calEvent, jsEvent, view) {
        EditSemakan(calEvent.id);
    }
});
@endsection

@section('content')

<script type="text/javascript">
function ClearSemakan() {
    $('#_speedtest_a').val('');
    $('#_speedtest_b').val('');
    $('#_speedtest_c').val('');
    @foreach ($ss_semua as $ss_h)
        $('#_catatan_{{ $ss_h->id }}').val('');
        $('input[name="_status_{{ $ss_h->id }}"]').filter(':checked').each(function(){
            $(this).prop('checked', false);
        });
    @endforeach
    @foreach ($ss_user as $ss_hu)
        $('#_catatan_{{ $ss_hu->id }}').val('');
        $('input[name="_status_{{ $ss_hu->id }}"]').filter(':checked').each(function(){
            $(this).prop('checked', false);
        });
    @endforeach
    $('#_id_ssh').val('0');
    $('#_masa_semakan').val('');
    $('#btn_u_print').addClass('hide');
    $('#_tarikh_semakan').removeAttr('disabled');
}
</script>

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-check-square-o push-15-r"></i> Senarai Semak Harian
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Menu -->
<div class="content padding-5-t bg-white border-b">
    <div class="push-15 push-10-t">
        <div class="row">
            @if (Auth::user()->hasRole('ppd') || Auth::user()->hasRole('jpn'))
                <div class="col-md-7">
                    <a class="btn btn-default" href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </div>
                <div class="col-md-5 pull-right">
                    <div class="row">
                        <div class="col-xs-5">
                            <select name="qmonth" id="qmonth" data-placeholder="Bulan" class="form-control js-select2">
                                <option></option>
                                <?php
                                    $bulan = array('Januari','Februari','Mac','April','Mei','Jun','Julai','Ogos','September','Oktober','November','Disember');
                                    for ($i = 0; $i < 12; $i++) { 
                                ?>
                                @if (str_pad($i+1,2,'0',STR_PAD_LEFT) == date('m'))
                                    <option value="{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}" selected>{{ $bulan[$i] }}</option>
                                @else
                                    <option value="{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}">{{ $bulan[$i] }}</option>
                                @endif
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-5">
                            <select name="qyear" id="qyear" data-placeholder="Tahun" class="form-control js-select2">
                                <option></option>
                                <?php
                                    for ($i = 2016; $i < date('Y')+10; $i++) { 
                                ?>
                                @if ($i == date('Y'))
                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                @else
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endif

                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-search push-5-r"></i>Cari
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <a class="btn btn-default" href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-primary" href="{{ url('/senarai-semakan') }}">
                        <i class="fa fa-list-ul push-5-r"></i>Senarai Semakan
                    </a>
                    <button type="button" class="btn btn-success" onclick="javascript:Semakan();">
                        <i class="fa fa-check-square-o push-5-r"></i>Buat Semakan
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- END Menu -->


<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="block block-themed block-rounded">
                <div class="block-content block-content-full">
                    <div class="js-calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<!-- Senarai Semak Dialog //-->
<div id="SemakanDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/save-senarai-semakan') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-list-ul push-10-r"></i>Senarai Semakan
                        </h3>
                    </div>
                    <div class="block-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Perkara</th>
                                    <th>Status Semakan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; ?>
                                @foreach ($ss_semua as $ss)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>
                                        <div class="push-5">{{ $ss->perkara }}</div>
                                        <div class="h6">
                                            <div class="font-w700"><u>Cara Pengujian</u></div>
                                            {{ $ss->cara_pengujian }}
                                        </div>
                                    </td>
                                    <td width="200">
                                        @if ($ss->id == 1)
                                            <div class="row items-push">
                                                <div class="col-xs-4">ZOOM-A</div>
                                                <div class="col-xs-8">
                                                    <input type="text" id="_speedtest_a" name="_speedtest_a" class="form-control input-sm" placeholder="0.00">
                                                </div>
                                                <div class="col-xs-4">ZOOM-B</div>
                                                <div class="col-xs-8">
                                                    <input type="text" id="_speedtest_b" name="_speedtest_b" class="form-control input-sm" placeholder="0.00">
                                                </div>
                                                <div class="col-xs-4">ZOOM-C</div>
                                                <div class="col-xs-8">
                                                    <input type="text" id="_speedtest_c" name="_speedtest_c" class="form-control input-sm" placeholder="0.00">
                                                </div>
                                            </div>
                                        @else
                                            <label class="css-input css-radio css-radio-success push-10-r">
                                                <input type="radio" value="1" name="_status_{{ $ss->id }}" checked><span></span> <i class="fa fa-check text-success"></i>
                                            </label>
                                            <label class="css-input css-radio css-radio-danger">
                                                <input type="radio" value="0" name="_status_{{ $ss->id }}"><span></span> <i class="fa fa-times text-danger"></i>
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        <textarea id="_catatan_{{ $ss->id }}" name="_catatan_{{ $ss->id }}" class="form-control"></textarea>
                                    </td>
                                </tr>
                                @endforeach

                                @foreach ($ss_user as $ssu)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>
                                        <div class="push-5">{{ $ssu->perkara }}</div>
                                        <div class="h6">
                                            <div class="font-w700"><u>Cara Pengujian</u></div>
                                            {{ $ssu->cara_pengujian }}
                                        </div>
                                    </td>
                                    <td>
                                        <label class="css-input css-radio css-radio-success push-10-r">
                                            <input type="radio" value="1" name="_status_{{ $ssu->id }}" checked><span></span> <i class="fa fa-check text-success"></i>
                                        </label>
                                        <label class="css-input css-radio css-radio-danger">
                                            <input type="radio" value="0" name="_status_{{ $ssu->id }}"><span></span> <i class="fa fa-times text-danger"></i>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea id="_catatan_{{ $ssu->id }}" name="_catatan_{{ $ssu->id }}" class="form-control"></textarea>
                                    </td>
                                </tr>
                                @endforeach

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>Tarikh Semakan:</td>
                                    <td>
                                        <input class="js-datepicker form-control" type="text" id="_tarikh_semakan" name="_tarikh_semakan" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" value="{{ date('d/m/Y') }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>Waktu Semakan (24-Jam):</td>
                                    <td>
                                        <input class="js-masked-time form-control" type="text" id="_masa_semakan" name="_masa_semakan" placeholder="00:00" value="08:30">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_u_print" class="btn btn-primary hide" type="button" onClick="javascript:CetakSSH();">
                        <i class="fa fa-print push-5-r"></i>Cetak
                    </button>
                    <button id="btn_u_save" class="btn btn-primary" type="submit">
                        <i class="fa fa-save push-5-r"></i>Simpan Rekod
                    </button>
                    <button id="btn_u_cancel" data-dismiss="modal" class="btn btn-danger" type="button" onClick="javascript:ClearSemakan();">
                        <i class="fa fa-times push-5-r"></i>Batal
                    </button>
                    <input id="_id_ssh" name="_id_ssh" type="hidden" value="0" />
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
