<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class JTKController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Profil(Request $r)
    {
    	return view('jtk.profil',[
    		'greds' => \App\Gred::get(),
    		'sekolah' => \App\Sekolah::get(),
            'status' => $r->status
    	]);
    }

    public function SaveProfil(Request $r)
    {
    	$Nama = $r->input('name');
    	$Emel = $r->input('email');
    	$Gred = $r->input('gred');
    	$Jabatan = $r->input('jabatan');
    	$PasswordBaru = $r->input('pwd');

        if (strlen($PasswordBaru) != 0)
        {
            $UpdateProfile = \App\User::where('id', Auth::user()->id)->update(
                [
                    'name' => $Nama,
                    'email' => $Emel,
                    'password' => bcrypt($PasswordBaru),
                    'gred' => $Gred,
                    'kod_jabatan' => $Jabatan,
                    'updated_at' => DB::raw('NOW()')
                ]
            );
        }
        else
        {
            $UpdateProfile = \App\User::where('id', Auth::user()->id)->update(
                [
                    'name' => $Nama,
                    'email' => $Emel,
                    'gred' => $Gred,
                    'kod_jabatan' => $Jabatan,
                    'updated_at' => DB::raw('NOW()')
                ]
            );
        }

    	return redirect('/profil/?status=success');
    }
}
