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
use App\ProjekTask;
use App\ProjekTaskDetail;
use App\SmartTeam;
use App\AktivitiSmartTeam;
use App\GambarAktivitiSmartTeam;
use App\AktivitiAdhoc;

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

        # Ruangan JPN & PPD
        $this->middleware('role:jpn-ppd',[
            'only' => ['SenaraiProjekAll']
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

    #
    # JPN & PPD
    #
    public function SenaraiProjekAll($ppd = null)
    {
        # Semua PPD
        $projek = Projek::all();
        
        return view('devteam.senarai-projek',[
            'projek' => $projek
        ]);
    }

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

        return view('dev-team',[
            'id' => $id,
            'ppd' => $ppd
        ]);
    }
    public function SaveDevTeam(Request $r)
    {
        $kod_ppd = $r->input('_kodppd');
        $nama_kumpulan = $r->input('_name');
        $ketua = $r->input('_ketua');
        $ahli = ','.implode(',', $r->input('_jtk')).',';

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
    public function EditDevTeam(Request $r, $id)
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
        }

        return redirect('/dev-team/projek/' . $devteam);
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
        
        $nama_projek = addslashes(html_entity_decode($projek->nama_projek,ENT_QUOTES));
        $nama_projek = str_replace('<br />', '\n', nl2br($nama_projek));

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
            'devteam_id' => $projek->devteam->id,
            'senarai_jtk' => $projek->devteam->senarai_jtk,
            'nama_projek' => $projek->nama_projek,
            'nama_kumpulan' => $projek->devteam->nama_kumpulan,
            'tasks' => $tasks
        ]);
    }
    public function SaveTask(Request $r)
    {
        $tajuk_task = htmlentities($r->input('_tajuk_task'),ENT_QUOTES);
        $assigned = ','.implode(',', $r->input('_assigned')).',';
        $peratus_siap = $r->input('_peratus_siap');
        $detail = htmlentities($r->input('_detail'),ENT_QUOTES);
        $projek_id = $r->input('_projekid');

        if ($r->_taskid != 0 || $r->_taskid != '0')
        {
            # Update
            $task = ProjekTask::find($r->_taskid);
            $task->projek_id = $projek_id;
            $task->tajuk_task = $tajuk_task;
            $task->detail_task = $detail;
            $task->assigned = $assigned;
            $task->peratus_siap = $peratus_siap;
            $task->save();
        }
        else
        {
            # Insert
            $task = new ProjekTask;
            $task->projek_id = $projek_id;
            $task->tajuk_task = $tajuk_task;
            $task->detail_task = $detail;
            $task->assigned = $assigned;
            $task->peratus_siap = $peratus_siap;
            $task->save();
        }

        return redirect('/dev-team/projek/' . $projek_id . '/tasks');
    }
    public function DeleteTask(Request $r, $taskid)
    {
        $task = ProjekTask::find($taskid);
        $projek_id = $task->projek_id;
        ProjekTask::destroy($taskid);

        echo "SweetAlert('success','Berjaya Dipadam !','Maklumat task tugasan ini telah berjaya dipadam !',\"window.location.href='/dev-team/projek/$projek_id/tasks';\");";
    }
    public function EditTask(Request $r, $taskid)
    {
        $task = ProjekTask::find($taskid);
        
        $tajuk_task = addslashes(html_entity_decode($task->tajuk_task,ENT_QUOTES));
        $tajuk_task = str_replace('<br />', '\n', nl2br($tajuk_task));

        $detail_task = addslashes(html_entity_decode($task->detail_task,ENT_QUOTES));
        $detail_task = str_replace('<br />', '\n', nl2br($detail_task));
        $detail_task = trim(preg_replace('/\s\s+/', '', $detail_task));

        $assigned = $task->assigned;
        $peratus_siap = $task->peratus_siap;

        echo "$('#_taskid').val('$taskid');";
        echo "$('#TaskDialog').modal('show');";

        echo "$('#_tajuk_task').val(\"".$tajuk_task."\");";
        echo "$('#_assigned').val([$assigned]).trigger('change');";
        echo "$('#_detail').val(\"".$detail_task."\");";

        echo "$('#_peratus_siap').data('ionRangeSlider').update({ from: $peratus_siap});";
    }
    public function DetailTask($taskid)
    {
        $task = ProjekTask::find($taskid);
        $projek = Projek::find($task->projek_id);

        return view('devteam.task-details',[
            'task' => $task,
            'devteam_id' => $projek->devteam->id,
        ]);
    }
    public function SaveTaskTimeline(Request $r)
    {
        $task_id = $r->task_id;
        $peratus_siap = $r->peratus_siap;
        $progress_type = $r->progress_type;
        $timeline_by = $r->timeline_by;
        $detail = htmlentities($r->detail,ENT_QUOTES);

        # Tambah Timeline
        $task_timeline = new ProjekTaskDetail;
        $task_timeline->task_id = $task_id;
        $task_timeline->timeline_by = $timeline_by;
        $task_timeline->detail = $detail;
        $task_timeline->progress_type = $progress_type;
        $task_timeline->save();

        # Kemaskini Task
        $task = ProjekTask::find($task_id);
        $task->peratus_siap = $peratus_siap;
        $task->save();
        $task->touch();

        echo "$('#_timeline').val('');";
        echo "$('#btn-save-timeline').removeAttr('disabled');";
        echo "SweetAlert('success','Berjaya Dikemaskini !','Maklumat tugasan telah berjaya dikemaskini !',\"window.location.href='/dev-team/projek/task/$task_id';\");";
    }
    public function DeleteTaskDetail(Request $r)
    {
        $task_detail = ProjekTaskDetail::find($r->task_id);
        $task_id = $task_detail->task_id;
        ProjekTaskDetail::destroy($r->task_id);

        $task = ProjekTask::find($task_id);
        $task->touch();

        echo "SweetAlert('success','Berjaya Dipadam !','Rekod ini telah berjaya dipadam !',\"window.location.href='/dev-team/projek/task/$task_id';\");";
    }

    /**

    KUMPULAN SMART TEAM

    */

    public function SmartTeam($ppd = null)
    {
        if ($ppd != null)
        {
            $_ppd = PPD::where('kod_ppd',$ppd)->first();
        }
        else
        {
            $_ppd = '';
        }

        return view('smart-team',[
            'kod_ppd' => $ppd,
            'data_ppd' => $_ppd
        ]);
    }
    public function DetailSmartTeam($team_id)
    {
        $smart_team = SmartTeam::find($team_id);
        return view('smartteam.detail',[
            'st' => $smart_team
        ]);
    }
    public function SaveSmartTeam(Request $r)
    {
        $kod_ppd = $r->input('_kodppd');
        $nama_kumpulan = $r->input('_name');
        $ketua = $r->input('_ketua');
        $ahli = ','.implode(',', $r->input('_jtk')).',';

        if ($r->_gid != 0 || $r->_gid != '0')
        {
            # Update
            $devteam = SmartTeam::find($r->_gid);
            $devteam->kod_ppd = $kod_ppd;
            $devteam->nama_kumpulan = $nama_kumpulan;
            $devteam->ketua_kumpulan = $ketua;
            $devteam->senarai_jtk = $ahli;
            $devteam->save();
        }
        else
        {
            # Insert
            $devteam = new SmartTeam;
            $devteam->kod_ppd = $kod_ppd;
            $devteam->nama_kumpulan = $nama_kumpulan;
            $devteam->ketua_kumpulan = $ketua;
            $devteam->senarai_jtk = $ahli;
            $devteam->save();
        }

        return redirect('/smart-team/'.$kod_ppd);
    }
    public function EditSmartTeam(Request $r, $id)
    {
        $smart_team = SmartTeam::find($id);
        $kod_ppd = $smart_team->kod_ppd;
        $nama_kumpulan = $smart_team->nama_kumpulan;
        $ketua_kumpulan = $smart_team->ketua_kumpulan;
        $senarai_jtk = $smart_team->senarai_jtk;

        echo "$('#_gid').val('$id');";
        echo "$('#STDialog').modal('show');";

        echo "$('#_kodppd').val(\"$kod_ppd\").trigger(\"change\");";
        echo "$('#_name').val('$nama_kumpulan');";
        echo "$('#_ketua').val(\"$ketua_kumpulan\").trigger(\"change\");";
        echo "$('#_jtk').val([$senarai_jtk]).trigger(\"change\");";
    }
    public function DeleteSmartTeam($id)
    {
        $smart_team = SmartTeam::find($id);
        $kod_ppd = $smart_team->kod_ppd;
        $smart_team = SmartTeam::destroy($id);
        echo "SweetAlert('success','Berjaya Dipadam !','Rekod kumpulan ini telah berjaya dipadam !',\"window.location.href='/smart-team/$kod_ppd';\");";
    }
    public function SaveAktivitiSmartTeam(Request $r)
    {
        $smart_team_id = $r->input('_smart_team_id');
        $tajuk_xtvt = htmlentities($r->input('_tajuk_xtvt'),ENT_QUOTES);
        $sekolah_terlibat = ','.implode(',', $r->input('_sekolah_terlibat')).',';
        $tarikh_xtvt_dari = $r->input('_tarikh_xtvt_dari');
        $tarikh_xtvt_hingga = $r->input('_tarikh_xtvt_hingga');
        if ($r->jtk_terlibat_type == 1 || $r->jtk_terlibat_type == '1') {
            $jtk_terlibat = ','.implode(',', $r->input('_jtk_terlibat')).',';
        } else {
            $jtk_terlibat = '';
        }
        $objektif = htmlentities($r->input('_objektif'),ENT_QUOTES);
        $detail = htmlentities($r->input('_detail'),ENT_QUOTES);

        if ($r->_xtvtid != 0 || $r->_xtvtid != '0')
        {
            # Update
            $xtvt = AktivitiSmartTeam::find($r->_xtvtid);
            $xtvt->nama_aktiviti = $tajuk_xtvt;
            $xtvt->sekolah_terlibat = $sekolah_terlibat;
            $xtvt->tarikh_dari = $tarikh_xtvt_dari;
            $xtvt->tarikh_hingga = $tarikh_xtvt_hingga;
            $xtvt->jtk_terlibat = $jtk_terlibat;
            $xtvt->objektif = $objektif;
            $xtvt->detail = $detail;
            $xtvt->save();
        }
        else
        {
            # Insert
            if (AktivitiSmartTeam::where('nama_aktiviti',$tajuk_xtvt)->count() == 1)
            {
                return redirect('/smart-team/detail/'.$smart_team_id.'/?error=title_exists');
            }
            else
            {
                $xtvt = new AktivitiSmartTeam;
                $xtvt->smart_team_id = $smart_team_id;
                $xtvt->nama_aktiviti = $tajuk_xtvt;
                $xtvt->sekolah_terlibat = $sekolah_terlibat;
                $xtvt->tarikh_dari = $tarikh_xtvt_dari;
                $xtvt->tarikh_hingga = $tarikh_xtvt_hingga;
                $xtvt->jtk_terlibat = $jtk_terlibat;
                $xtvt->objektif = $objektif;
                $xtvt->detail = $detail;
                $xtvt->save();
            }
        }

        return redirect('/smart-team/detail/'.$smart_team_id);
    }
    public function EditAktivitiSmartTeam(Request $r, $xtvtid)
    {
        $flag = $r->flag;
        if ($flag == 1 || $flag == '1')
        {
            $xtvt = AktivitiAdhoc::find($xtvtid);
        }
        else
        {
            $xtvt = AktivitiSmartTeam::find($xtvtid);
        }
        
        $nama_aktiviti = addslashes(html_entity_decode($xtvt->nama_aktiviti,ENT_QUOTES));
        $nama_aktiviti = str_replace('<br />', '\n', nl2br($nama_aktiviti));
        $sekolah_terlibat = '"' . str_replace(',', '","', trim($xtvt->sekolah_terlibat,',')) . '"';
        $tarikh_dari = \Carbon\Carbon::createFromFormat('Y-m-d', $xtvt->tarikh_dari)->format('d/m/Y');
        $tarikh_hingga = \Carbon\Carbon::createFromFormat('Y-m-d', $xtvt->tarikh_hingga)->format('d/m/Y');
        $jtk_terlibat = $xtvt->jtk_terlibat;
        $objektif = addslashes(html_entity_decode($xtvt->objektif,ENT_QUOTES));
        $objektif = str_replace('<br />', '\n', nl2br($objektif));
        $objektif = trim(preg_replace('/\s\s+/', '', $objektif));
        $detail = addslashes(html_entity_decode($xtvt->detail,ENT_QUOTES));
        $detail = str_replace('<br />', '\n', nl2br($detail));
        $detail = trim(preg_replace('/\s\s+/', '', $detail));

        echo "$('#_xtvtid').val('$xtvtid');";
        echo "$('#AktivitiDialog').modal('show');";

        echo "$('#_tajuk_xtvt').val('$nama_aktiviti');";
        echo "$('#_sekolah_terlibat').val([$sekolah_terlibat]).trigger(\"change\");";
        echo "$('#_tarikh_xtvt_dari').val('$tarikh_dari');";
        echo "$('#_tarikh_xtvt_hingga').val('$tarikh_hingga');";
        if (strlen($jtk_terlibat) != 0) {
            echo "$('#_jtk_terlibat').removeAttr('disabled');";
            echo "$('#_jtk_adhoc').prop('checked','checked');";
            echo "$('#_jtk_terlibat').val([$jtk_terlibat]).trigger('change');";
        }
        else
        {
            echo "$('#_jtk_terlibat').attr('disabled','disabled');";
            echo "$('#_jtk_semua').prop('checked','checked');";   
        }
        
        echo "$('#_objektif').val(\"$objektif\");";
        echo "$('#_detail').val(\"$detail\");";
    }
    public function PadamAktivitiSmartTeam(Request $r, $xtvtid)
    {
        $flag = $r->flag;

        if ($flag == 1 || $flag == '1')
        {
            $xtvt = AktivitiAdhoc::destroy($xtvtid);
            $kod_ppd = Auth::user()->kod_ppd;
            echo "SweetAlert('success','Berjaya Dipadam !','Rekod aktiviti ini telah berjaya dipadam !',\"window.location.href='/smart-team/$kod_ppd';\");";
        }
        else
        {
            $xtvt = AktivitiSmartTeam::find($xtvtid);
            $smart_team_id = $xtvt->smart_team_id;
            $xtvt = AktivitiSmartTeam::destroy($xtvtid);
            echo "SweetAlert('success','Berjaya Dipadam !','Rekod aktiviti ini telah berjaya dipadam !',\"window.location.href='/smart-team/detail/$smart_team_id';\");";
        }
    }
    public function DetailAktivitiSmartTeam($xtvtid)
    {
        $xtvt = AktivitiSmartTeam::find($xtvtid);
        
        return view('smartteam.aktiviti-detail',[
            'xtvt' => $xtvt
        ]);
    }
    public function DetailAktivitiAdhoc($xtvtid)
    {
        $xtvt = AktivitiAdhoc::find($xtvtid);
        
        return view('smartteam.aktiviti-adhoc-detail',[
            'xtvt' => $xtvt
        ]);
    }
    public function UploadGambarAktivitiSmartTeam(Request $r, $xtvtid)
    {
        \Cloudinary::config(array( 
          "cloud_name" => config('jtkpk.cloudinary.cloud_name'),
          "api_key" => config('jtkpk.cloudinary.api_key'),
          "api_secret" => config('jtkpk.cloudinary.api_secret')
        ));

        // Get Data
        $FileSize = round($_FILES['file']['size']/1024); // in KB
        $FileName = $_FILES['file']['name'];
        $FileType = strtolower($_FILES['file']['type']);
        $FileExt = substr($FileType, 6);
        $tmpName = $_FILES['file']['tmp_name']; 
        $isUploadedFile = is_uploaded_file($_FILES['file']['tmp_name']);

        if ($r->_adhoc == 1 || $r->_adhoc == '1') {
            $JenisAktiviti = "ADHOC";
        } else {
            $JenisAktiviti = "SMART";
        }

        if ($isUploadedFile == true)
        {
            $cloud_file = \Cloudinary\Uploader::upload($tmpName);
            if ($cloud_file)
            {
                $cloud_filename = $cloud_file["url"];
                $cloud_publicid = $cloud_file["public_id"];

                # Insert into database
                $gambar = new GambarAktivitiSmartTeam;
                $gambar->JenisAktiviti = $JenisAktiviti;
                $gambar->xtvt_id = $xtvtid;
                $gambar->url_img = $cloud_filename;
                $gambar->public_id = $cloud_publicid;
                $gambar->save();
            }
            else
            {
                $cloud_filename = "";
                $cloud_publicid = "";
            }

            echo "OK|".$cloud_publicid;
        }
        else
        {
            echo "Muat naik gambar gagal. Sila cuba sekali lagi !";
        }
    }
    public function PadamGambarAktivitiSmartTeam(Request $r, $public_id)
    {
        if (strlen($public_id) != 0)
        {
            GambarAktivitiSmartTeam::where('public_id',$public_id)->delete();
            
            \Cloudinary::config(array( 
              "cloud_name" => config('jtkpk.cloudinary.cloud_name'),
              "api_key" => config('jtkpk.cloudinary.api_key'),
              "api_secret" => config('jtkpk.cloudinary.api_secret')
            ));

            \Cloudinary\Uploader::destroy($public_id);

            echo "SweetAlert('success','Berjaya Dipadam !','Gambar aktiviti ini telah berjaya dipadam !');";
            echo "$('.gambar_$public_id').fadeOut();";
        }
    }

    /**

    AKTIVITI AD HOC

    */
    public function SaveAktivitiAdhoc(Request $r)
    {
        $tajuk_xtvt = htmlentities($r->input('_tajuk_xtvt'),ENT_QUOTES);
        $sekolah_terlibat = ','.implode(',', $r->input('_sekolah_terlibat')).',';
        $tarikh_xtvt_dari = $r->input('_tarikh_xtvt_dari');
        $tarikh_xtvt_hingga = $r->input('_tarikh_xtvt_hingga');
        if ($r->jtk_terlibat_type == 1 || $r->jtk_terlibat_type == '1') {
            $jtk_terlibat = ','.implode(',', $r->input('_jtk_terlibat')).',';
        } else {
            $jtk_terlibat = '';
        }
        $objektif = htmlentities($r->input('_objektif'),ENT_QUOTES);
        $detail = htmlentities($r->input('_detail'),ENT_QUOTES);

        if ($r->_xtvtid != 0 || $r->_xtvtid != '0')
        {
            # Update
            $xtvt = AktivitiAdhoc::find($r->_xtvtid);
            $xtvt->kod_ppd = Auth::user()->kod_ppd;
            $xtvt->nama_aktiviti = $tajuk_xtvt;
            $xtvt->sekolah_terlibat = $sekolah_terlibat;
            $xtvt->tarikh_dari = $tarikh_xtvt_dari;
            $xtvt->tarikh_hingga = $tarikh_xtvt_hingga;
            $xtvt->jtk_terlibat = $jtk_terlibat;
            $xtvt->objektif = $objektif;
            $xtvt->detail = $detail;
            $xtvt->save();
        }
        else
        {
            # Insert
            if (AktivitiAdhoc::where('nama_aktiviti',$tajuk_xtvt)->count() == 1)
            {
                return redirect('/smart-team/'.Auth::user()->kod_ppd.'/?error=title_exists');
            }
            else
            {
                $xtvt = new AktivitiAdhoc;
                $xtvt->kod_ppd = Auth::user()->kod_ppd;
                $xtvt->nama_aktiviti = $tajuk_xtvt;
                $xtvt->sekolah_terlibat = $sekolah_terlibat;
                $xtvt->tarikh_dari = $tarikh_xtvt_dari;
                $xtvt->tarikh_hingga = $tarikh_xtvt_hingga;
                $xtvt->jtk_terlibat = $jtk_terlibat;
                $xtvt->objektif = $objektif;
                $xtvt->detail = $detail;
                $xtvt->save();
            }
        }

        return redirect('/smart-team/'.Auth::user()->kod_ppd);
    }
}
