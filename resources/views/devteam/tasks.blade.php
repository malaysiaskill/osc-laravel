@extends('master.app')
@section('title', 'Senarai Task Projek')
@section('site.description', 'Senarai Task Projek')
@section('app.helper', ",'summernote', 'ckeditor'")

@section('jquery')
$('#Tasks').DataTable({ responsive: true });
@endsection

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-th push-15-r"></i> Senarai Task Projek
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
                    <a href="/dev-team/projek/{{ $projek_id }}" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                        <i class="fa fa-arrow-circle-left"></i>
                    </a>
                    <div class="pull-right">
                        
                    </div>
                </div>
                
                <div class="block-content block-content-full border-b clearfix">
                    <div class="pull-left">
                        <h5>
                            <span class="font-w300">Projek : </span>
                            <b>{{ $nama_projek }}</b>
                        </h5>
                    </div>
                    <div class="pull-right text-right">
                        <h5>
                            <span class="font-w300">Kumpulan : </span>
                            <b>{{ $nama_kumpulan }}</b>
                        </h5>
                        <h5>
                            <span class="font-w300">PPD : </span>
                            <b>{{ App\Projek::find($projek_id)->devteam->ppd->ppd }}</b>
                        </h5>
                    </div>
                </div>
                <div class="block-content">
                    <table id="Tasks" class="table table-striped table-bordered responsive h6">
                        <thead>
                            <tr>
                                <th class="text-center">%</th>
                                <th class="text-center">Tajuk Task</th>
                                <th class="text-center">Ditugaskan Kepada</th>
                                <th class="text-center">Kemaskini Terakhir</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                                <td class="text-center h3 font-w300" width="80">{{ $task->peratus_siap }} %</td>
                                <td>
                                    <a href="/dev-team/projek/task/{{ $task->id }}">
                                        <span class="h5 text-primary">{{ $task->tajuk_task }}</span>
                                    </a>
                                </td>
                                <td class="text-left h6 font-w300">
                                    @if ($task->assigned != 0)
                                        <img class="img-avatar img-avatar32 push-5-r" src="/avatar/{{ $task->assigned }}" title="{{ $task->jtk->name }}"> {{ $task->jtk->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center h6 font-w300">{{ $task->updated_at }}</td>
                                <td class="text-center" width="150">
                                    
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
