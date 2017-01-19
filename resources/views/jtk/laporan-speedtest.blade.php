@inject('jtkc', '\App\Http\Controllers\JTKController')

@extends('master.single')
@section('title', 'Laporan Speedtest')
@section('site.description', 'Laporan Speedtest')

@section('jquery')
<?php

# Daily
$daily = array();

if (strlen($month) != 0 && strlen($year) != 0)
{
    // MONTH
    $mon = ltrim($month, '0');
    $TotalDays = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
    for ($i=1; $i <= $TotalDays; $i++)
    {
        $hari = strtolower($jtkc->replaceDay(date('l',strtotime("$year-$mon-$i"))));
        if ($hari != 'sabtu' && $hari != 'ahad')
        {
            $tarikh = "$year-$mon-$i";
            $th = \App\TugasanHarian::whereYear('tarikh_semakan',$year)->whereMonth('tarikh_semakan',$mon)->whereDay('tarikh_semakan',$i)->where('user_id',$user_id)->first();
            if (count($th) == 0) {
                $daily_DPG_ZOOM_A[] = array($i, intval(0));
                $daily_DPG_ZOOM_B[] = array($i, intval(0));
                $daily_DPG_ZOOM_C[] = array($i, intval(0));                
                $daily_DPG_SUPER_ZOOM_A[] = array($i, intval(0));
                $daily_DPG_SUPER_ZOOM_B[] = array($i, intval(0));
                $daily_UPG_ZOOM_A[] = array($i, intval(0));
                $daily_UPG_ZOOM_B[] = array($i, intval(0));
                $daily_UPG_ZOOM_C[] = array($i, intval(0));                
                $daily_UPG_SUPER_ZOOM_A[] = array($i, intval(0));
                $daily_UPG_SUPER_ZOOM_B[] = array($i, intval(0));

                $daily_DPTG_ZOOM_A[] = array($i, intval(0));
                $daily_DPTG_ZOOM_B[] = array($i, intval(0));
                $daily_DPTG_ZOOM_C[] = array($i, intval(0));
                $daily_DPTG_SUPER_ZOOM_A[] = array($i, intval(0));
                $daily_DPTG_SUPER_ZOOM_B[] = array($i, intval(0));
                $daily_UPTG_ZOOM_A[] = array($i, intval(0));
                $daily_UPTG_ZOOM_B[] = array($i, intval(0));
                $daily_UPTG_ZOOM_C[] = array($i, intval(0));
                $daily_UPTG_SUPER_ZOOM_A[] = array($i, intval(0));
                $daily_UPTG_SUPER_ZOOM_B[] = array($i, intval(0));
            } else {
                $s = json_decode($th->status_semakan);
                if (count($s) > 0)
                {
                    foreach ($s as $v)
                    {
                        if ($v->id == '1')
                        {
                            $_ptg_speedtest_a = isset($v->_ptg_speedtest_a) ? $v->_ptg_speedtest_a:"";
                            $_ptg_speedtest_b = isset($v->_ptg_speedtest_b) ? $v->_ptg_speedtest_b:"";
                            $_ptg_speedtest_c = isset($v->_ptg_speedtest_c) ? $v->_ptg_speedtest_c:"";
                            $_ptg_speedtest_d = isset($v->_ptg_speedtest_d) ? $v->_ptg_speedtest_d:"";
                            $_ptg_speedtest_e = isset($v->_ptg_speedtest_e) ? $v->_ptg_speedtest_e:"";
                            $_ptg_speedtest_a1 = isset($v->_ptg_speedtest_a1) ? $v->_ptg_speedtest_a1:"";
                            $_ptg_speedtest_b1 = isset($v->_ptg_speedtest_b1) ? $v->_ptg_speedtest_b1:"";
                            $_ptg_speedtest_c1 = isset($v->_ptg_speedtest_c1) ? $v->_ptg_speedtest_c1:"";
                            $_ptg_speedtest_d1 = isset($v->_ptg_speedtest_d1) ? $v->_ptg_speedtest_d1:"";
                            $_ptg_speedtest_e1 = isset($v->_ptg_speedtest_e1) ? $v->_ptg_speedtest_e1:"";

                            $daily_DPG_ZOOM_A[] = array($i, floatval($v->_speedtest_a));
                            $daily_DPG_ZOOM_B[] = array($i, floatval($v->_speedtest_b));
                            $daily_DPG_ZOOM_C[] = array($i, floatval($v->_speedtest_c));
                            $daily_DPG_SUPER_ZOOM_A[] = array($i, floatval($v->_speedtest_d));
                            $daily_DPG_SUPER_ZOOM_B[] = array($i, floatval($v->_speedtest_e));
                            $daily_UPG_ZOOM_A[] = array($i, floatval($v->_speedtest_a1));
                            $daily_UPG_ZOOM_B[] = array($i, floatval($v->_speedtest_b1));
                            $daily_UPG_ZOOM_C[] = array($i, floatval($v->_speedtest_c1));
                            $daily_UPG_SUPER_ZOOM_A[] = array($i, floatval($v->_speedtest_d1));
                            $daily_UPG_SUPER_ZOOM_B[] = array($i, floatval($v->_speedtest_e1));

                            $daily_DPTG_ZOOM_A[] = array($i, floatval($_ptg_speedtest_a));
                            $daily_DPTG_ZOOM_B[] = array($i, floatval($_ptg_speedtest_b));
                            $daily_DPTG_ZOOM_C[] = array($i, floatval($_ptg_speedtest_c));
                            $daily_DPTG_SUPER_ZOOM_A[] = array($i, floatval($_ptg_speedtest_d));
                            $daily_DPTG_SUPER_ZOOM_B[] = array($i, floatval($_ptg_speedtest_e));
                            $daily_UPTG_ZOOM_A[] = array($i, floatval($_ptg_speedtest_a1));
                            $daily_UPTG_ZOOM_B[] = array($i, floatval($_ptg_speedtest_b1));
                            $daily_UPTG_ZOOM_C[] = array($i, floatval($_ptg_speedtest_c1));
                            $daily_UPTG_SUPER_ZOOM_A[] = array($i, floatval($_ptg_speedtest_d1));
                            $daily_UPTG_SUPER_ZOOM_B[] = array($i, floatval($_ptg_speedtest_e1));
                        }
                    }
                }
            }
        }
    }
}

$stats_daily_DPG_ZOOM_A = json_encode($daily_DPG_ZOOM_A);
$stats_daily_DPG_ZOOM_B = json_encode($daily_DPG_ZOOM_B);
$stats_daily_DPG_ZOOM_C = json_encode($daily_DPG_ZOOM_C);
$stats_daily_DPG_SUPER_ZOOM_A = json_encode($daily_DPG_SUPER_ZOOM_A);
$stats_daily_DPG_SUPER_ZOOM_B = json_encode($daily_DPG_SUPER_ZOOM_B);
$stats_daily_DPTG_ZOOM_A = json_encode($daily_DPTG_ZOOM_A);
$stats_daily_DPTG_ZOOM_B = json_encode($daily_DPTG_ZOOM_B);
$stats_daily_DPTG_ZOOM_C = json_encode($daily_DPTG_ZOOM_C);
$stats_daily_DPTG_SUPER_ZOOM_A = json_encode($daily_DPTG_SUPER_ZOOM_A);
$stats_daily_DPTG_SUPER_ZOOM_B = json_encode($daily_DPTG_SUPER_ZOOM_B);

$stats_daily_UPG_ZOOM_A = json_encode($daily_UPG_ZOOM_A);
$stats_daily_UPG_ZOOM_B = json_encode($daily_UPG_ZOOM_B);
$stats_daily_UPG_ZOOM_C = json_encode($daily_UPG_ZOOM_C);
$stats_daily_UPG_SUPER_ZOOM_A = json_encode($daily_UPG_SUPER_ZOOM_A);
$stats_daily_UPG_SUPER_ZOOM_B = json_encode($daily_UPG_SUPER_ZOOM_B);
$stats_daily_UPTG_ZOOM_A = json_encode($daily_UPTG_ZOOM_A);
$stats_daily_UPTG_ZOOM_B = json_encode($daily_UPTG_ZOOM_B);
$stats_daily_UPTG_ZOOM_C = json_encode($daily_UPTG_ZOOM_C);
$stats_daily_UPTG_SUPER_ZOOM_A = json_encode($daily_UPTG_SUPER_ZOOM_A);
$stats_daily_UPTG_SUPER_ZOOM_B = json_encode($daily_UPTG_SUPER_ZOOM_B);

?>
var DPG_StatDaily = [
    {label: "ZOOM-A", data: {{ $stats_daily_DPG_ZOOM_A }} },
    {label: "ZOOM-B", data: {{ $stats_daily_DPG_ZOOM_B }} },
    {label: "ZOOM-C", data: {{ $stats_daily_DPG_ZOOM_C }} },
    {label: "SUPER ZOOM (A)", data: {{ $stats_daily_DPG_SUPER_ZOOM_A }} },
    {label: "SUPER ZOOM (B)", data: {{ $stats_daily_DPG_SUPER_ZOOM_B }} },
];
var DPTG_StatDaily = [
    {label: "ZOOM-A", data: {{ $stats_daily_DPTG_ZOOM_A }} },
    {label: "ZOOM-B", data: {{ $stats_daily_DPTG_ZOOM_B }} },
    {label: "ZOOM-C", data: {{ $stats_daily_DPTG_ZOOM_C }} },
    {label: "SUPER ZOOM (A)", data: {{ $stats_daily_DPTG_SUPER_ZOOM_A }} },
    {label: "SUPER ZOOM (B)", data: {{ $stats_daily_DPTG_SUPER_ZOOM_B }} },
];
var UPG_StatDaily = [
    {label: "ZOOM-A", data: {{ $stats_daily_UPG_ZOOM_A }} },
    {label: "ZOOM-B", data: {{ $stats_daily_UPG_ZOOM_B }} },
    {label: "ZOOM-C", data: {{ $stats_daily_UPG_ZOOM_C }} },
    {label: "SUPER ZOOM (A)", data: {{ $stats_daily_UPG_SUPER_ZOOM_A }} },
    {label: "SUPER ZOOM (B)", data: {{ $stats_daily_UPG_SUPER_ZOOM_B }} },
];
var UPTG_StatDaily = [
    {label: "ZOOM-A", data: {{ $stats_daily_UPTG_ZOOM_A }} },
    {label: "ZOOM-B", data: {{ $stats_daily_UPTG_ZOOM_B }} },
    {label: "ZOOM-C", data: {{ $stats_daily_UPTG_ZOOM_C }} },
    {label: "SUPER ZOOM (A)", data: {{ $stats_daily_UPTG_SUPER_ZOOM_A }} },
    {label: "SUPER ZOOM (B)", data: {{ $stats_daily_UPTG_SUPER_ZOOM_B }} },
];

"use strict";

function showTooltip(x, y, contents) {
    jQuery('<div id="tooltip" class="tooltipflot">' + contents + ' Mbps</div>').css({
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5
    }).appendTo("body").fadeIn(200);
}
/* STATISTICS DAILY */
var DPG_PlotDaily = jQuery.plot(jQuery("#DPG_DailyChart"),DPG_StatDaily,
{
    series: {
        lines: {
            show: false
        },
        splines: {
            show: true,
            tension: 0.4,
            lineWidth: 1,
            fill: 0
        },
        shadowSize: 0
    },
    points: {
        show: true,
    },
    legend: {
        container: '#DPG_DC_Legend',
        noColumns: 5
    },
    grid: {
        hoverable: true,
        clickable: true,
        borderColor: '#ddd',
        borderWidth: 0,
        labelMargin: 5,
        backgroundColor: '#fff'
    },
    yaxis: {
        label: "test",
        min: 0,
        color: '#eee'
    },
    xaxis: {
        color: '#eee',
        tickSize: 1,
        tickDecimals: 0
    },

    axisLabels: {
        show: true
    },
    xaxes: [{
        axisLabel: 'HARIBULAN',
    }],
    yaxes: [{
        position: 'left',
        axisLabel: 'KELAJUAN INTERNET (Mbps)',
        axisLabelPadding: 5,
    }, {
        position: 'right',
        axisLabel: 'bleem'
    }]
});
var DPG_iDaily = null;
jQuery("#DPG_DailyChart").bind("plothover",function(event,pos,item){
    if (item) {
        if (DPG_iDaily != item.dataIndex) {
            DPG_iDaily = item.dataIndex;           
            jQuery("#tooltip").remove();
            var x = item.datapoint[0],y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY, y);
        }
    } else {
        jQuery("#tooltip").remove();
        DPG_iDaily = null;
    }
});

var DPTG_PlotDaily = jQuery.plot(jQuery("#DPTG_DailyChart"),DPTG_StatDaily,
{
    series: {
        lines: {
            show: false
        },
        splines: {
            show: true,
            tension: 0.4,
            lineWidth: 1,
            fill: 0
        },
        shadowSize: 0
    },
    points: {
        show: true,
    },
    legend: {
        container: '#DPTG_DC_Legend',
        noColumns: 5
    },
    grid: {
        hoverable: true,
        clickable: true,
        borderColor: '#ddd',
        borderWidth: 0,
        labelMargin: 5,
        backgroundColor: '#fff'
    },
    yaxis: {
        label: "test",
        min: 0,
        color: '#eee'
    },
    xaxis: {
        color: '#eee',
        tickSize: 1,
        tickDecimals: 0
    },

    axisLabels: {
        show: true
    },
    xaxes: [{
        axisLabel: 'HARIBULAN',
    }],
    yaxes: [{
        position: 'left',
        axisLabel: 'KELAJUAN INTERNET (Mbps)',
        axisLabelPadding: 5,
    }, {
        position: 'right',
        axisLabel: 'bleem'
    }]
});
var DPTG_iDaily = null;
jQuery("#DPTG_DailyChart").bind("plothover",function(event,pos,item){
    if (item) {
        if (DPTG_iDaily != item.dataIndex) {
            DPTG_iDaily = item.dataIndex;           
            jQuery("#tooltip").remove();
            var x = item.datapoint[0],y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY, y);
        }
    } else {
        jQuery("#tooltip").remove();
        DPTG_iDaily = null;
    }
});

/* UPLOAD */
var UPG_PlotDaily = jQuery.plot(jQuery("#UPG_DailyChart"),UPG_StatDaily,
{
    series: {
        lines: {
            show: false
        },
        splines: {
            show: true,
            tension: 0.4,
            lineWidth: 1,
            fill: 0
        },
        shadowSize: 0
    },
    points: {
        show: true,
    },
    legend: {
        container: '#UPG_DC_Legend',
        noColumns: 5
    },
    grid: {
        hoverable: true,
        clickable: true,
        borderColor: '#ddd',
        borderWidth: 0,
        labelMargin: 5,
        backgroundColor: '#fff'
    },
    yaxis: {
        label: "test",
        min: 0,
        color: '#eee'
    },
    xaxis: {
        color: '#eee',
        tickSize: 1,
        tickDecimals: 0
    },

    axisLabels: {
        show: true
    },
    xaxes: [{
        axisLabel: 'HARIBULAN',
    }],
    yaxes: [{
        position: 'left',
        axisLabel: 'KELAJUAN INTERNET (Mbps)',
        axisLabelPadding: 5,
    }, {
        position: 'right',
        axisLabel: 'bleem'
    }]
});
var UPG_iDaily = null;
jQuery("#UPG_DailyChart").bind("plothover",function(event,pos,item){
    if (item) {
        if (UPG_iDaily != item.dataIndex) {
            UPG_iDaily = item.dataIndex;           
            jQuery("#tooltip").remove();
            var x = item.datapoint[0],y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY, y);
        }
    } else {
        jQuery("#tooltip").remove();
        UPG_iDaily = null;
    }
});

var UPTG_PlotDaily = jQuery.plot(jQuery("#UPTG_DailyChart"),UPTG_StatDaily,
{
    series: {
        lines: {
            show: false
        },
        splines: {
            show: true,
            tension: 0.4,
            lineWidth: 1,
            fill: 0
        },
        shadowSize: 0
    },
    points: {
        show: true,
    },
    legend: {
        container: '#UPTG_DC_Legend',
        noColumns: 5
    },
    grid: {
        hoverable: true,
        clickable: true,
        borderColor: '#ddd',
        borderWidth: 0,
        labelMargin: 5,
        backgroundColor: '#fff'
    },
    yaxis: {
        label: "test",
        min: 0,
        color: '#eee'
    },
    xaxis: {
        color: '#eee',
        tickSize: 1,
        tickDecimals: 0
    },

    axisLabels: {
        show: true
    },
    xaxes: [{
        axisLabel: 'HARIBULAN',
    }],
    yaxes: [{
        position: 'left',
        axisLabel: 'KELAJUAN INTERNET (Mbps)',
        axisLabelPadding: 5,
    }, {
        position: 'right',
        axisLabel: 'bleem'
    }]
});
var UPTG_iDaily = null;
jQuery("#UPTG_DailyChart").bind("plothover",function(event,pos,item){
    if (item) {
        if (UPTG_iDaily != item.dataIndex) {
            UPTG_iDaily = item.dataIndex;           
            jQuery("#tooltip").remove();
            var x = item.datapoint[0],y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY, y);
        }
    } else {
        jQuery("#tooltip").remove();
        UPTG_iDaily = null;
    }
});
@endsection

@section('js')
<script src="/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/assets/js/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="/assets/js/plugins/chartjs/Chart.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.pie.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.stack.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.spline.min.js"></script>
<script src="/assets/js/plugins/flot/jquery.flot.axislabels.js"></script>
@endsection

@section('content')
<!-- Menu -->
<div class="content content-mini bg-white border-b">
    <div class="push-15 text-center">
        <p>LAPORAN BULANAN KELAJUAN INTERNET 1BESTARINET BAGI BULAN <b>{{ strtoupper($bulan_tahun) }}</b><br><b>{{ $nama_sekolah }}</b></p>
    </div>
</div>
<!-- END Menu -->

<div class="content">
    <div class="row">
        <div class="col-xs-12 push-5">
            <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
                <i class="fa fa-print push-5-r"></i> Cetak
            </button>
        </div>
        <div class="col-xs-12">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title"><i class="fa fa-line-chart push-10-r"></i>Statistik Kelajuan Internet 1BestariNet</h3>
                </div>
                <div class="block-content block-content-full">
                    <h3 class="push-5"><b>DOWNLOAD - PAGI</b></h3>
                    <div id="DPG_DC_Legend" class="flotLegend"></div>
                    <div id="DPG_DailyChart" class="flotGraph"></div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title"><i class="fa fa-line-chart push-10-r"></i>Statistik Kelajuan Internet 1BestariNet</h3>
                </div>
                <div class="block-content block-content-full">
                    <h3 class="push-5"><b>DOWNLOAD - PETANG</b></h3>
                    <div id="DPTG_DC_Legend" class="flotLegend"></div>
                    <div id="DPTG_DailyChart" class="flotGraph"></div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title"><i class="fa fa-line-chart push-10-r"></i>Statistik Kelajuan Internet 1BestariNet</h3>
                </div>
                <div class="block-content block-content-full">
                    <h3 class="push-5"><b>UPLOAD - PAGI</b></h3>
                    <div id="UPG_DC_Legend" class="flotLegend"></div>
                    <div id="UPG_DailyChart" class="flotGraph"></div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title"><i class="fa fa-line-chart push-10-r"></i>Statistik Kelajuan Internet 1BestariNet</h3>
                </div>
                <div class="block-content block-content-full">
                    <h3 class="push-5"><b>UPLOAD - PETANG</b></h3>
                    <div id="UPTG_DC_Legend" class="flotLegend"></div>
                    <div id="UPTG_DailyChart" class="flotGraph"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
