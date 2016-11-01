@extends('master.app')
@section('title', 'Senarai Pengguna')
@section('site.description', 'Senarai Pengguna Sistem')

@section('jquery')
$('#Users').DataTable({ responsive: true });
@endsection

@section('content')
<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated fadeInUp">
            <i class="fa fa-users push-15-r"></i> Senarai Pengguna
        </h1>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-xs-12">
            <div id="_users" class="block block-themed block-rounded push-5">
                
                <!--
                <div class="block-content block-content-full block-content-mini border-b bg-gray-lighter">
                    <button type="button" class="btn btn-primary" onclick="javascript:void();">
                        <i class="fa fa-plus push-5-r"></i><i class="fa fa-user"></i>
                    </button>
                </div>
                -->

                <div class="block-content">
                    <table id="Users" class="table table-striped table-bordered responsive h6">
                        <thead>
                            <tr>
                                <th class="text-center">Bil</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">E-mel</th>
                                <th class="text-center">Gred</th>
                                <th class="text-center">Peranan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($users as $user)
                            <?php $i++; ?>
                            <tr>
                                <td class="text-center">{{ $i }}.</td>
                                <td>
                                    <span class="font-w300 h4 text-primary">{{ $user->name }}</span>
                                </td>
                                <td class="text-left">
                                    <a href="mailto:{{ $user->email }}"><i class="fa fa-envelope push-5-r"></i>{{ $user->email }}</a>
                                </td>
                                <td class="text-center">{{ $user->greds->gred_title }}</td>
                                <td class="text-center">{{ $user->roles->role_name }}</td>
                                <td class="text-center" width="150">
                                    <a href="{{ url('/admin/users/'.$user->id.'/delete') }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Padam Pengguna">
                                        <i class="fa fa-trash-o"></i>
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
