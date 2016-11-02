<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Gred;
use App\JPN;
use App\PPD;
use App\Sekolah;
use App\DevTeam;

class JTKController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:leader',[
            'only' => ['SaveDevTeam', 'getDevTeam', 'DeleteDevTeam']
        ]);
    }

    public function Profil(Request $r)
    {
    	return view('jtk.profil',[
    		'status' => $r->status
    	]);
    }
    public function SaveProfil(Request $r)
    {
    	$Nama = $r->input('name');
    	$Emel = $r->input('email');
    	$Gred = $r->input('gred');
        $JPN = $r->input('jabatan_jpn');
        $PPD = $r->input('jabatan_ppd');
    	$SEK = $r->input('jabatan');
    	$PasswordBaru = $r->input('pwd');

        $user = User::find(Auth::user()->id);
        $user->name = $Nama;
        $user->email = $Emel;
        if (strlen($PasswordBaru) != 0) {
            $user->password = bcrypt($PasswordBaru);
        }
        $user->gred = $Gred;

        if (Auth::user()->role == 'jpn') {
            $user->kod_jpn = $JPN;
        } else if (Auth::user()->role == 'ppd') {
            $user->kod_jpn = $JPN;
            $user->kod_ppd = $PPD;
        } else if (Auth::user()->role == 'leader' || Auth::user()->role == 'user') {
            $user->kod_jpn = $JPN;
            $user->kod_ppd = $PPD;
            $user->kod_jabatan = $SEK;
        } else {
            // nothing
        }
        $user->save();

    	return redirect('/profil/?status=success');
    }

    public function DevTeam($id = null)
    {
        if ($id != null)
        {
            $ppd = PPD::where('kod_ppd',$id)->first();
            $nama_ppd = $ppd->ppd;
        }
        else
        {
            $nama_ppd = '';
        }

        return view('devteam',[
            'id' => $id,
            'nama_ppd' => $nama_ppd
        ]);
    }
    public function SaveDevTeam(Request $r)
    {
        $kod_ppd = $r->input('_kodppd');
        $nama_kumpulan = $r->input('_name');
        $ketua = $r->input('_ketua');
        $ahli = implode(',', $r->input('_jtk'));

        if ($r->_gid != 0 || $r->_gid != '0')
        {
            # Update
            $devteam = DevTeam::find($r->_gid);
            $devteam->kod_ppd = $kod_ppd;
            $devteam->nama_kumpulan = $nama_kumpulan;
            $devteam->ketua_kumpulan = $ketua;
            $devteam->senarai_jtk = $ahli;
            $devteam->save();
        }
        else
        {
            # Insert
            $devteam = new DevTeam;
            $devteam->kod_ppd = $kod_ppd;
            $devteam->nama_kumpulan = $nama_kumpulan;
            $devteam->ketua_kumpulan = $ketua;
            $devteam->senarai_jtk = $ahli;
            $devteam->save();
        }

        return redirect('/dev-team/'.$kod_ppd);
    }
    public function getDevTeam(Request $r, $id)
    {
        $devteam = DevTeam::find($id);
        $kod_ppd = $devteam->kod_ppd;
        $nama_kumpulan = $devteam->nama_kumpulan;
        $ketua_kumpulan = $devteam->ketua_kumpulan;
        $senarai_jtk = $devteam->senarai_jtk;

        echo "$('#_gid').val('$id');";
        echo "$('#GroupDialog').modal('show');";

        echo "$('#_kodppd').val(\"$kod_ppd\").trigger(\"change\");";
        echo "$('#_name').val('$nama_kumpulan');";
        echo "$('#_ketua').val(\"$ketua_kumpulan\").trigger(\"change\");";
        echo "$('#_jtk').val([$senarai_jtk]).trigger(\"change\");";
    }
    public function DeleteDevTeam($id)
    {
        $devteam = DevTeam::find($id);
        $kod_ppd = $devteam->kod_ppd;
        $devteam = DevTeam::destroy($id);
        echo "window.location.href='/dev-team/$kod_ppd';";
    }
}
