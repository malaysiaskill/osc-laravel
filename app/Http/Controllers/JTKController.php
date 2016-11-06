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
use App\Projek;

class JTKController extends Controller
{
    public function __construct()
    {
        # Memastikan semua pengguna log masuk sebelum mengakses
        # method di dalam Controller JTKPK

        $this->middleware('auth');

        # Terdapat beberapa method yang memerlukan pengguna
        # mempunyai peranan untuk diakses
        
        $this->middleware('role:leader',[
            'only' => ['SaveDevTeam', 'getDevTeam', 'DeleteDevTeam']
        ]);

    }

    /**

    PROFIL JURUTEKNIK KOMPUTER

    */
    public function Profil(Request $r)
    {
    	return view('jtk.profil',[
    		'status' => $r->status
    	]);
    }
    public function Avatar($id = null)
    {
        if ($id != null)
        {
            $user = User::find($id);
            $avatarType = $user->avatarType;
            $avatar = $user->avatar;

            if (strlen($avatarType) != 0)
            {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Content-Type: $avatarType");
                echo $avatar;
            }
            else
            {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Content-Type: image/jpg");
                echo file_get_contents(public_path()."/img/default_avatar.jpg");
            }
        }
        else
        {
            $avatarType = Auth::user()->avatarType;
            $avatar = Auth::user()->avatar;

            if (strlen($avatarType) != 0)
            {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Content-Type: $avatarType");
                echo $avatar;
            }
            else
            {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Content-Type: image/jpg");
                echo file_get_contents(public_path()."/img/default_avatar.jpg");
            }
        }
    }
    public function DeleteAvatar()
    {
        $user = Auth::user();
        $user->avatarType = '';
        $user->avatar = '';
        $user->save();
        echo "window.location.href='/profil';";
    }
    public function UploadAvatar(Request $r)
    {
        $FileType = strtolower($_FILES['file']['type']);
        $tmpName = $_FILES['file']['tmp_name']; 
        $isUploadedFile = is_uploaded_file($_FILES['file']['tmp_name']);
        if ($isUploadedFile == true)
        {
            $user = Auth::user();
            $user->avatarType = $FileType;
            $user->avatar = file_get_contents($tmpName);
            $user->save();
            echo "OK";
        }
        else
        {
            echo "KO";
        }
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
        }
        $user->save();

    	return redirect('/profil/?status=success');
    }

    /**

    KUMPULAN DEVELOPMENT TEAM

    */
    public function DevTeam($id = null)
    {
        if ($id != null)
        {
            $ppd = PPD::where('kod_ppd',$id)->first();
        }
        else
        {
            $ppd = '';
        }

        return view('devteam',[
            'id' => $id,
            'ppd' => $ppd
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
        echo "SweetAlert('success','Berjaya Dipadam !','Rekod kumpulan ini telah berjaya dipadam !',\"window.location.href='/dev-team/$kod_ppd';\");";
    }

    /**

    SENARAI PROJEK DEVELOPMENT TEAM

    */
    public function SaveProjek(Request $r)
    {
        $devteam = $r->input('_devteam');
        $nama_projek = htmlentities($r->input('_nama_projek'),ENT_QUOTES);
        $objektif = htmlentities($r->input('_objektif'),ENT_QUOTES);
        $keterangan = htmlentities($r->input('_detail'),ENT_QUOTES);
        $repositori = htmlentities($r->input('_repo'),ENT_QUOTES);
        if (strlen($r->input('kk')) != 0) {
            $kk = explode('|', $r->input('kk'));
            $nama_kertas_kerja = htmlentities($kk[0],ENT_QUOTES);
            $kertas_kerja = $kk[1];
        }

        if ($r->_projekid != 0 || $r->_projekid != '0')
        {
            # Update
            $projek = Projek::find($r->_projekid);
            $projek->devteam_id = $devteam;
            $projek->nama_projek = $nama_projek;
            $projek->objektif = $objektif;
            $projek->detail = $keterangan;
            $projek->repositori = $repositori;
            if (strlen($r->input('kk')) != 0) {
                $projek->nama_kertas_kerja = $nama_kertas_kerja;
                $projek->kertas_kerja = $kertas_kerja;
            }
            $projek->save();

            return redirect('/dev-team/projek/' . $devteam);
        }
        else
        {
            # Insert
            $projek = new Projek;
            $projek->devteam_id = $devteam;
            $projek->nama_projek = $nama_projek;
            $projek->objektif = $objektif;
            $projek->detail = $keterangan;
            $projek->repositori = $repositori;
            if (strlen($r->input('kk')) != 0) {
                $projek->nama_kertas_kerja = $nama_kertas_kerja;
                $projek->kertas_kerja = $kertas_kerja;
            }
            $projek->save();
            
            $dev_team = DevTeam::find($devteam);

            return redirect('/dev-team/' . $dev_team->kod_ppd);
        }
    }
    public function SenaraiProjek($groupid)
    {
        $devteam = DevTeam::find($groupid)->first();
        $ppd = PPD::where('kod_ppd',$devteam->kod_ppd)->first();

        return view('devteam.projek',[
            'projek' => Projek::where('devteam_id',$groupid)->get(),
            'kodppd' => $devteam->kod_ppd,
            'namappd' => $ppd->ppd,
            'nama_kumpulan' => $devteam->nama_kumpulan,
        ]);
    }
    public function ViewProjek(Request $r, $id)
    {
        $projek = Projek::find($id);
        $devteam_id = $projek->devteam_id;
        $nama_projek = $projek->nama_projek;
        $objektif = nl2br($projek->objektif);
        $detail = nl2br($projek->detail);
        $nama_kertas_kerja = $projek->nama_kertas_kerja;
        $kertas_kerja = $projek->kertas_kerja;
        $repositori = $projek->repositori;

        $devteam = DevTeam::find($devteam_id);

        $objektif = addslashes(\Emojione\Emojione::toImage($objektif));
        $detail = addslashes(\Emojione\Emojione::toImage($detail));

        $output = "$('#ViewProjekDialog').modal('show');";
        $output .= "$('#v_devteam').html(\"".$devteam->nama_kumpulan."\").trigger(\"change\");";
        $output .= "$('#v_nama_projek').html(\"$nama_projek\");";
        $output .= "$('#v_objektif').html(\"$objektif\");";
        $output .= "$('#v_detail').html(\"$detail\");";
        
        $_repositori = (strlen($repositori) > 0) ? '<a href=\"'.$repositori.'\" target=\"_blank\">'.$repositori.'</a>':'-';
        $output .= "$('#v_repo').html(\"$_repositori\");";

        if (strlen($kertas_kerja) != 0)
        {
            $output_template = '<div class="template panel panel-primary remove-margin-b push-5-t">
                            <div class="panel-body">
                                <div class="push-5 pull-left">
                                    <span class="h5">Fail Kertas Kerja :</span><br>
                                    <a href="/devteam/kertas-kerja/'.$kertas_kerja.'" target="_blank">
                                        <i class="fa fa-file push-5-r"></i>
                                        <span class="h6">'.$nama_kertas_kerja.'</span>
                                    </a>
                                </div>
                            </div>
                        </div>';
            $output .= "$('#v_previews').html('".addslashes($output_template)."');";
        }
        else
        {
            $output .= "$('#v_previews').html('Tiada Kertas Kerja');";
        }

        $output = trim(preg_replace('/\s\s+/', '', $output));
        echo $output;
    }
    public function EditProjek(Request $r, $id)
    {
        $projek = Projek::find($id);
        $devteam_id = $projek->devteam_id;
        $nama_projek = $projek->nama_projek;

        $objektif = addslashes(html_entity_decode($projek->objektif,ENT_QUOTES));
        $objektif = str_replace('<br />', '\n', nl2br($objektif));

        $detail = addslashes(html_entity_decode($projek->detail,ENT_QUOTES));
        $detail = str_replace('<br />', '\n', nl2br($detail));

        $nama_kertas_kerja = $projek->nama_kertas_kerja;
        $kertas_kerja = $projek->kertas_kerja;
        $repositori = $projek->repositori;

        $output = '$(\'#_projekid\').val(\''.$id.'\');';
        $output .= '$(\'#ProjekDialog\').modal(\'show\');';

        $output .= '$(\'#_devteam\').val("'.$devteam_id.'").trigger("change");';
        $output .= '$(\'#_nama_projek\').val("'.$nama_projek.'");';
        $output .= '$(\'#_objektif\').val("'.$objektif.'");';
        $output .= '$(\'#_detail\').val("'.$detail.'");';
        $output .= '$(\'#_repo\').val("'.$repositori.'");';

        if (strlen($kertas_kerja) != 0)
        {
            $output .= "$('#btn-kertas-kerja').addClass('hide');";
            $output_template = '<div class="template panel panel-primary remove-margin-b push-5-t">
                            <div class="panel-body">
                                <div class="push-5 pull-left">
                                    <span class="h5">Fail Kertas Kerja :</span><br>
                                    <a href="/devteam/kertas-kerja/'.$kertas_kerja.'" target="_blank">
                                        <i class="fa fa-file push-5-r"></i>
                                        <span class="h6">'.$nama_kertas_kerja.'</span>
                                    </a>
                                </div>
                                <div class="push pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="javascript:PadamKertasKerja(\''.$id.'\',\''.$kertas_kerja.'\');">
                                        <i class="fa fa-trash-o"></i> Padam
                                    </button>
                                </div>
                            </div>
                        </div>';
            $output .= "$('#_previews').html('".addslashes($output_template)."');";
        }

        $output = trim(preg_replace('/\s\s+/', '', $output));
        echo $output;
    }
    public function DeleteProjek(Request $r, $projekid)
    {
        $projek = Projek::find($projekid);
        $devteam_id = $projek->devteam_id;
        $kertas_kerja = $projek->kertas_kerja;
        Projek::destroy($projekid);

        # Delete Kertas Kerja
        if (strlen($kertas_kerja) > 0)
        {
            if (file_exists(public_path().'/devteam/kertas-kerja/'.$kertas_kerja))
            {
                unlink(public_path().'/devteam/kertas-kerja/'.$kertas_kerja);
            }
        }

        echo "SweetAlert('success','Berjaya Dipadam !','Maklumat projek ini telah berjaya dipadam !',\"window.location.href='/dev-team/projek/$devteam_id';\");";
    }
    public function UploadKertasKerja(Request $r)
    {
        $FileType = strtolower($_FILES['file']['type']);
        $FileName = strtolower($_FILES['file']['name']);
        $ext = end((explode(".", $FileName)));
        $tmpName = $_FILES['file']['tmp_name']; 
        $isUploadedFile = is_uploaded_file($_FILES['file']['tmp_name']);
        if ($isUploadedFile == true)
        {
            $newFilename = strtoupper(str_random(32)).'.'.$ext;
            $newPathfile = public_path().'/devteam/kertas-kerja/'.$newFilename;
            $isMove = move_uploaded_file($tmpName, $newPathfile);
            if ($isMove) {
                echo "OK|$newFilename";
            } else {
                echo "Ralat semasa muat naik dokumen kertas kerja anda. Sila cuba lagi !";
            }
        }
        else
        {
            echo "KO";
        }
    }
    public function PadamKertasKerja(Request $r, $filename)
    {
        if (file_exists(public_path().'/devteam/kertas-kerja/'.$filename))
        {
            unlink(public_path().'/devteam/kertas-kerja/'.$filename);
        }

        $projek = Projek::find($r->projek_id);
        $projek->nama_kertas_kerja = DB::raw('NULL');
        $projek->kertas_kerja = DB::raw('NULL');
        $projek->save();

        if ($r->return_alert == 1 || $r->return_alert == '1')
        {
            echo "SweetAlert('success','Berjaya Dipadam !','Maklumat kertas kerja projek ini telah berjaya dipadam !');";
            echo "$('.template').remove();";
            echo "$('#btn-kertas-kerja').removeClass('hide');";
        }
    }
    public function SenaraiTask($projekid)
    {
        $projek = Projek::find($projekid);
        $tasks = $projek->tasks;

        return view('devteam.tasks',[
            'projek_id' => $projekid,
            'nama_projek' => $projek->nama_projek,
            'nama_kumpulan' => $projek->devteam->nama_kumpulan,
            'tasks' => $tasks
        ]);
    }
}
