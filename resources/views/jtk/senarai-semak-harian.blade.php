@extends('master.app')
@section('title', 'Senarai Semak Harian')
@section('site.description', 'Senarai Semak Harian')

@section('jquery')
    @if (Auth::user()->hasRole('ppd'))

    @else
        @if ($error == 'already_exists')
            SweetAlert('error','Ops !',"Rekod telah wujud dalam pangkalan data!");
        @endif

        jQuery('.js-calendar').fullCalendar({
            firstDay: 1,
            editable: false,
            droppable: false,
            header: {
                left: 'title',
                right: 'today,prev,next'
            },
            eventRender: function(event, element, view) {
                element.attr('title', event.tooltip);
                if (view.name == 'month') {
                    $(element).height(50);
                }
                element.find(".fc-content").addClass("text-center push-5-t");
                if (event.icon) {
                    element.find(".fc-title").prepend("&nbsp;<i class='text-success fa fa-"+event.icon+"'></i> ");
                }
                if (event.semakan_ppd) {
                    element.find(".fc-title").append("&nbsp;<i class='fa fa-"+event.semakan_ppd+"'></i> ");
                }
            },
            events:
            [
                @foreach (\App\SenaraiSemakHarian::where('user_id',Auth::user()->id)->get() as $ssh)
                <?php
                    $event = array();
                    $tarikh = $ssh->tarikh_semakan;
                    $id = $ssh->id;

                    $dt = explode('-', $tarikh);
                    $Year = $dt[0];
                    $Mon = $dt[1]-1;
                    $Day = $dt[2];

                    if ($ssh->ppd_semak == '1') {
                        $_ppdsemak = 'check-circle fa-2x text-success';
                    } else {
                        $_ppdsemak = 'exclamation-circle fa-2x text-warning';
                    }

                    $event[] = "{
                        color: '#FFF',
                        icon: 'check-square fa-2x',
                        semakan_ppd: '$_ppdsemak',
                        start: new Date($Year, $Mon, $Day, 0, 0),
                        allDay: true,
                        id: '$id',
                        tooltip: 'Klik untuk lihat rekod'
                    },";
                    $events = implode(',', $event);
                    echo $events;
                ?>
                @endforeach
            ],
            eventClick: function(calEvent, jsEvent, view) {
                EditSemakan(calEvent.id);
            }
        });
    @endif
@endsection

@section('content')

@if (!Auth::user()->hasRole('ppd'))
<script type="text/javascript">
function ClearSemakan() {
    $('#_speedtest_a').val('');
    $('#_speedtest_b').val('');
    $('#_speedtest_c').val('');
    $('#_speedtest_d').val('');
    $('#_speedtest_e').val('');
    $('#_speedtest_a1').val('');
    $('#_speedtest_b1').val('');
    $('#_speedtest_c1').val('');
    $('#_speedtest_d1').val('');
    $('#_speedtest_e1').val('');
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
    $('#_semakan_ppd').removeClass('block-content block-content-full block-content-mini text-white bg-success bg-warning');
    $('#_semakan_ppd').html('');
}
</script>
@endif

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
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
            @if (Auth::user()->hasRole('ppd'))
                <div class="col-md-7">
                    <a class="btn btn-default" href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </div>
                <div class="col-md-5 pull-right">
                    <div class="row">
                        <div class="col-xs-6">
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
                        <div class="col-xs-3">
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
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-primary" onclick="javascript:ViewSSH($('#qmonth').val(),$('#qyear').val());">
                                <i class="fa fa-eye push-5-r"></i>Lihat
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
                    @if (Auth::user()->hasRole('ppd'))
                        <div class="row items-push border-b push">
                            <div class="col-xs-12 h5 font-w300 text-right">
                                <span class="push-10-r"><img src="{{ asset('/img/default_avatar.jpg') }}" class="img-avatar img-avatar32 ko push-5-r"> Tiada Semakan Harian Dibuat</span>
                                <span class="push-10-r"><img src="{{ asset('/img/default_avatar.jpg') }}" class="img-avatar img-avatar32 warning push-5-r"> Perlu Semakan</span>
                                <span><img src="{{ asset('/img/default_avatar.jpg') }}" class="img-avatar img-avatar32 ok push-5-r"> Telah Disemak Oleh PPD</span>
                            </div>
                        </div>
                        
                        @if (strlen($mon) != 0 && strlen($year) != 0)
                            <?php
                                $TotalDay = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
                                $rowday = '';
                                for ($k=1; $k <= $TotalDay; $k++)
                                {
                                    $listjtk = '';
                                    foreach (\App\User::where('kod_ppd',Auth::user()->kod_ppd)->where('kod_jabatan','<>','')->orderBy('name','asc')->get() as $jtk)
                                    {
                                        $date = "$year-$mon-".str_pad($k,2,'0',STR_PAD_LEFT);
                                        $_ssh = $jtk->ssh->where('tarikh_semakan',$date)->first();
                                        
                                        if (count($_ssh) == 1)
                                        {
                                            if ($_ssh->ppd_semak == '0') {
                                                $status = "warning";
                                                $status_semakan = "(Perlu Semakan)";
                                            } else {
                                                $status = "ok";
                                                $status_semakan = "(OK)";
                                            }

                                            $listjtk .= '<a href="#" onclick="javascript:Cetak_SSH(\''.$_ssh->id.'\');return false;">
                                                <img src="/avatar/'.$jtk->id.'" class="push-5 img-avatar img-avatar32 '.$status.'" data-toggle="tooltip" title="'.$jtk->name.' '.$status_semakan.'">
                                            </a> ';
                                        }
                                        else
                                        {
                                            $status = "ko";
                                            $status_semakan = "(Tiada Semakan)";
                                            $listjtk .= '<img src="/avatar/'.$jtk->id.'" class="push-5 img-avatar img-avatar32 '.$status.'" data-toggle="tooltip" title="'.$jtk->name.' '.$status_semakan.'"> ';
                                        }
                                    }

                                    $mon = str_pad($mon,2,'0',STR_PAD_LEFT);
                                    $k = str_pad($k,2,'0',STR_PAD_LEFT);

                                    if ($k == date('d') && $mon == date('m')) {
                                        $tr_bgcolor = "bg-success-light";
                                    } else {
                                        $tr_bgcolor = "bg-white";
                                    }

                                    $rowday .= '<tr class="'.$tr_bgcolor.'">
                                        <td class="text-center">'.$k.'/'.$mon.'/'.$year.'</td>
                                        <td class="border-l">'.$listjtk.'</td>
                                    </tr>';
                                }
                            ?>
                            <div class="row">
                                <div class="col-md-6"><h2 class="text-left">{{ $bulan[ltrim($mon,'0')-1] }}, {{ $year }}</h2></div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-success" onclick="javascript:PPDSemakSSH('{{ $mon }}-{{ $year }}');">
                                        <i class="fa fa-check push-5-r"></i>Semak Untuk Bulan Ini ({{ $bulan[ltrim($mon,'0')-1] }})
                                    </button>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">Tarikh</th>
                                        <th>Semakan Harian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $rowday; ?>
                                </tbody>
                            </table> 
                        @else
                            <div class="row">
                                <div class="col-md-6"><h2 class="text-left">{{ $bulan[date('m')-1] }}, {{ date('Y') }}</h2></div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-success" onclick="javascript:PPDSemakSSH('{{ date('m') }}-{{ date('Y') }}');">
                                        <i class="fa fa-check push-5-r"></i>Semak Untuk Bulan Ini ({{ $bulan[date('m')-1] }})
                                    </button>
                                </div>
                            </div>
                            <?php
                                $TotalDay = cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y'));
                                $rowday = '';
                                for ($k=1; $k <= $TotalDay; $k++)
                                {
                                    $listjtk = '';
                                    foreach (\App\User::where('kod_ppd',Auth::user()->kod_ppd)->where('kod_jabatan','<>','')->orderBy('name','asc')->get() as $jtk)
                                    {
                                        $date = date('Y')."-".date('m')."-".str_pad($k,2,'0',STR_PAD_LEFT);
                                        $_ssh = $jtk->ssh->where('tarikh_semakan',$date)->first();
                                        
                                        if (count($_ssh) == 1)
                                        {
                                            if ($_ssh->ppd_semak == '0') {
                                                $status = "warning";
                                                $status_semakan = "(Perlu Semakan)";
                                            } else {
                                                $status = "ok";
                                                $status_semakan = "(OK)";
                                            }

                                            $listjtk .= '<a href="#" onclick="javascript:Cetak_SSH(\''.$_ssh->id.'\');return false;">
                                                <img src="/avatar/'.$jtk->id.'" class="push-5 img-avatar img-avatar32 '.$status.'" data-toggle="tooltip" title="'.$jtk->name.' '.$status_semakan.'">
                                            </a> ';
                                        }
                                        else
                                        {
                                            $status = "ko";
                                            $status_semakan = "(Tiada Semakan)";
                                            $listjtk .= '<img src="/avatar/'.$jtk->id.'" class="push-5 img-avatar img-avatar32 '.$status.'" data-toggle="tooltip" title="'.$jtk->name.' '.$status_semakan.'"> ';
                                        }
                                    }

                                    $i = str_pad($i,2,'0',STR_PAD_LEFT);
                                    $k = str_pad($k,2,'0',STR_PAD_LEFT);

                                    if ($k == date('d')) {
                                        $tr_bgcolor = "bg-success-light";
                                    } else {
                                        $tr_bgcolor = "bg-white";
                                    }

                                    $rowday .= '<tr class="'.$tr_bgcolor.'">                                        
                                        <td class="text-center">'.$k.'/'.date('m').'/'.date('Y').'</td>
                                        <td class="border-l">'.$listjtk.'</td>
                                    </tr>';
                                }
                            ?>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">Tarikh</th>
                                        <th>Semakan Harian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $rowday; ?>
                                </tbody>
                            </table>                            
                        @endif
                    @else
                        <div class="row items-push">
                            <div class="col-xs-12 h5 font-w300">
                                <span class="push-10-r"><i class="fa fa-check-square text-success push-5-r"></i> Semakan Harian Telah Dibuat</span>
                                <span class="push-10-r"><i class="fa fa-exclamation-circle text-warning push-5-r"></i> PPD Belum Membuat Semakan</span>
                                <span><i class="fa fa-check-circle text-success push-5-r"></i> Telah Disemak Oleh PPD</span>
                            </div>
                        </div>
                        <div class="js-calendar"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

@if (!Auth::user()->hasRole('ppd'))
<!-- Senarai Semak Dialog //-->
<div id="SemakanDialog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout">
        <div class="modal-content">
            <form method="post" class="form-horizontal" action="{{ url('/save-senarai-semakan') }}">
                {{ csrf_field() }}
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">
                            <i class="fa fa-list-ul push-10-r"></i>Senarai Semak Harian
                        </h3>
                    </div>
                    <div id="_semakan_ppd" class="h5 font-w300"></div>
                    <div class="block-content block-content-mini">
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
                                    <td width="250">
                                        @if ($ss->id == 1)
                                            <div class="row items-push">
                                                <div class="col-xs-12 push-5">ZOOM-A</div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                                        <input type="text" id="_speedtest_a" name="_speedtest_a" class="form-control input-sm" placeholder="0.00" title="Download">
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                                        <input type="text" id="_speedtest_a1" name="_speedtest_a1" class="form-control input-sm" placeholder="0.00" title="Upload">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 push-5">ZOOM-B</div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                                        <input type="text" id="_speedtest_b" name="_speedtest_b" class="form-control input-sm" placeholder="0.00" title="Download">
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                                        <input type="text" id="_speedtest_b1" name="_speedtest_b1" class="form-control input-sm" placeholder="0.00" title="Upload">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xs-12 push-5">ZOOM-C</div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                                        <input type="text" id="_speedtest_c" name="_speedtest_c" class="form-control input-sm" placeholder="0.00" title="Download">
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                                        <input type="text" id="_speedtest_c1" name="_speedtest_c1" class="form-control input-sm" placeholder="0.00" title="Upload">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 push-5">SUPER ZOOM (A)</div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                                        <input type="text" id="_speedtest_d" name="_speedtest_d" class="form-control input-sm" placeholder="0.00" title="Download">
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                                        <input type="text" id="_speedtest_d1" name="_speedtest_d1" class="form-control input-sm" placeholder="0.00" title="Upload">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 push-5">SUPER ZOOM (B)</div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                                        <input type="text" id="_speedtest_e" name="_speedtest_e" class="form-control input-sm" placeholder="0.00" title="Download">
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                                        <input type="text" id="_speedtest_e1" name="_speedtest_e1" class="form-control input-sm" placeholder="0.00" title="Upload">
                                                    </div>
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
                                        <textarea id="_catatan_{{ $ss->id }}" name="_catatan_{{ $ss->id }}" class="form-control input-sm"></textarea>
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
@endif

@endsection
