<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Template;

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
use App\SenaraiSemakan;
use App\SenaraiSemakHarian;
use App\AKP;
use App\KategoriKerosakan;

class JTKController extends Controller
{
    
    protected $req;

    public function __construct(Request $r)
    {
        $this->req = $r;

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

    public function replaceDay($day) {
        $day = strtolower($day);
        if ($day == 'monday') {
            return 'Isnin';
        } else if ($day == 'tuesday') {
            return 'Selasa';
        } else if ($day == 'wednesday') {
            return 'Rabu';
        } else if ($day == 'thursday') {
            return 'Khamis';
        } else if ($day == 'friday') {
            return 'Jumaat';
        } else if ($day == 'saturday') {
            return 'Sabtu';
        } else {
            return 'Ahad';
        }
    }

    public function replaceMonth($MonthNo) {
        $MonthNo = intval($MonthNo);
        
        if ($MonthNo == 1) {
            $ret = "Januari";
        } else if ($MonthNo == 2) {
            $ret = "Februari";
        } else if ($MonthNo == 3) {
            $ret = "Mac";
        } else if ($MonthNo == 4) {
            $ret = "April";
        } else if ($MonthNo == 5) {
            $ret = "Mei";
        } else if ($MonthNo == 6) {
            $ret = "Jun";
        } else if ($MonthNo == 7) {
            $ret = "Julai";
        } else if ($MonthNo == 8) {
            $ret = "Ogos";
        } else if ($MonthNo == 9) {
            $ret = "September";
        } else if ($MonthNo == 10) {
            $ret = "Oktober";
        } else if ($MonthNo == 11) {
            $ret = "November";
        } else if ($MonthNo == 12) {
            $ret = "Disember";
        }
        return $ret;
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

        if (Auth::user()->hasRole('jpn')) {
            $user->kod_jpn = $JPN;
        } else if (Auth::user()->hasRole('ppd')) {
            $user->kod_jpn = $JPN;
            $user->kod_ppd = $PPD;
        } else if (!Auth::user()->hasRole('jpn') || !Auth::user()->hasRole('ppd')) {
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
            'st' => $smart_team,
            'error' => $this->req->error
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

            return redirect('/smart-team/aktiviti-adhoc-detail/'.$r->_xtvtid);
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

            return redirect('/smart-team/'.Auth::user()->kod_ppd);
        }
    }

    /**

    SENARAI SEMAK HARIAN

    */
    public function PPDSemakSSH(Request $r)
    {
        $monyear = explode('-', $r->monyear);

        $ssh = SenaraiSemakHarian::whereYear('tarikh_semakan',$monyear[1])->whereMonth('tarikh_semakan',$monyear[0])->update([
            'ppd_semak' => 1,
            'tarikh_ppd_semak' => Carbon::now(),
            'id_penyemak' => Auth::user()->id
        ]);

        echo "SweetAlert('success','Berjaya !','Semua rekod semakan harian juruteknik telah berjaya disemak !',\"window.location.href='/senarai-semak-harian';\");";
    }
    public function SenaraiSemakHarian($mon=null, $year=null)
    {
        $ss_semua = SenaraiSemakan::where('user_id','0')->get();
        $ss_user = SenaraiSemakan::where('user_id',Auth::user()->id)->get();        

        return view('jtk.senarai-semak-harian',[
            'ss_semua' => $ss_semua,
            'ss_user' => $ss_user,
            'mon' => $mon,
            'year' => $year,
            'error' => $this->req->error
        ]);
    }
    public function SenaraiSemakan()
    {
        $ss_semua = SenaraiSemakan::where('user_id','0')->get();
        $ss_user = SenaraiSemakan::where('user_id',Auth::user()->id)->get();        
        return view('jtk.senarai-semakan',[
            'ss_semua' => $ss_semua,
            'ss_user' => $ss_user
        ]);
    }
    public function SaveSenaraiSemakan(Request $r)
    {
        $perkara = htmlentities($r->input('_perkara'),ENT_QUOTES);
        $cara_pengujian = htmlentities($r->input('_cara_pengujian'),ENT_QUOTES);

        if ($r->_id != 0 || $r->_id != '0')
        {
            # Update
            $ss = SenaraiSemakan::find($r->_id);
            $ss->perkara = $perkara;
            $ss->cara_pengujian = $cara_pengujian;
            $ss->save();
        }
        else
        {
            # Insert
            $ss = new SenaraiSemakan;
            $ss->perkara = $perkara;
            $ss->cara_pengujian = $cara_pengujian;
            if ($r->_flag == '1' || $r->_flag == 1) {
                $ss->user_id = '0';
            } else {
                $ss->user_id = Auth::user()->id;
            }
            $ss->save();
        }

        return redirect('/senarai-semakan');
    }
    public function EditSenaraiSemakan(Request $r, $id)
    {
        $ss = SenaraiSemakan::find($id);
        $perkara = addslashes(html_entity_decode($ss->perkara,ENT_QUOTES));
        $perkara = str_replace('<br />', '\n', nl2br($perkara));
        $perkara = trim(preg_replace('/\s\s+/', '', $perkara));
        
        $cara_pengujian = addslashes(html_entity_decode($ss->cara_pengujian,ENT_QUOTES));
        $cara_pengujian = str_replace('<br />', '\n', nl2br($cara_pengujian));
        $cara_pengujian = trim(preg_replace('/\s\s+/', '', $cara_pengujian));

        echo "$('#_id').val('$id');";
        echo "$('#SenaraiSemakanDialog').modal('show');";

        echo "$('#_perkara').val(\"$perkara\");";
        echo "$('#_cara_pengujian').val(\"$cara_pengujian\");";
    }
    public function PadamSenaraiSemakan(Request $r, $id)
    {
        $ss = SenaraiSemakan::destroy($id);
        echo "SweetAlert('success','Berjaya Dipadam !','Rekod semakan ini telah berjaya dipadam !',\"window.location.href='/senarai-semakan';\");";
    }
    public function SaveSenaraiSemak(Request $r)    
    {
        $ss_semua = SenaraiSemakan::where('user_id','0')->get();
        $record = array();
        foreach ($ss_semua as $ss)
        {
            if ($ss->id == 1)
            {
                eval("\$_speedtest_a = \$r->_speedtest_a;");
                eval("\$_speedtest_b = \$r->_speedtest_b;");
                eval("\$_speedtest_c = \$r->_speedtest_c;");
                eval("\$_speedtest_d = \$r->_speedtest_d;");
                eval("\$_speedtest_e = \$r->_speedtest_e;");
                eval("\$_speedtest_a1 = \$r->_speedtest_a1;");
                eval("\$_speedtest_b1 = \$r->_speedtest_b1;");
                eval("\$_speedtest_c1 = \$r->_speedtest_c1;");
                eval("\$_speedtest_d1 = \$r->_speedtest_d1;");
                eval("\$_speedtest_e1 = \$r->_speedtest_e1;");
                eval("\$catatan = \$r->_catatan_".$ss->id.";");
                $record[] = array(
                    'id' => $ss->id,
                    '_speedtest_a' => $_speedtest_a,
                    '_speedtest_b' => $_speedtest_b,
                    '_speedtest_c' => $_speedtest_c,
                    '_speedtest_d' => $_speedtest_d,
                    '_speedtest_e' => $_speedtest_e,
                    '_speedtest_a1' => $_speedtest_a1,
                    '_speedtest_b1' => $_speedtest_b1,
                    '_speedtest_c1' => $_speedtest_c1,
                    '_speedtest_d1' => $_speedtest_d1,
                    '_speedtest_e1' => $_speedtest_e1,
                    'catatan' => $catatan
                );
            }
            else
            {
                eval("\$status = \$r->_status_".$ss->id.";");
                eval("\$catatan = \$r->_catatan_".$ss->id.";");            
                $record[] = array('id' => $ss->id, 'status' => $status, 'catatan' => $catatan);
            }
        }        

        $ss_user = SenaraiSemakan::where('user_id',Auth::user()->id)->get();
        foreach ($ss_user as $ssu)
        {
            eval("\$status = \$r->_status_".$ssu->id.";");
            eval("\$catatan = \$r->_catatan_".$ssu->id.";");            
            $record[] = array('id' => $ssu->id, 'status' => $status, 'catatan' => $catatan);
        }

        $record = json_encode($record);

        if ($r->_id_ssh == '0') {
            $tarikh_semakan = $r->_tarikh_semakan;
            $db_tarikh_semakan = \Carbon\Carbon::createFromFormat('d/m/Y', $tarikh_semakan)->format('Y-m-d');
        } else {
            $dbts = DB::table('semakan_harian')->where('id',$r->_id_ssh)->first();
            $db_tarikh_semakan = $dbts->tarikh_semakan;
        }
        $masa_semakan = $r->_masa_semakan;

        // Search
        if (SenaraiSemakHarian::where('user_id',Auth::user()->id)->where('tarikh_semakan',$db_tarikh_semakan)->count() == 1)
        {
            if ($r->_id_ssh == '0')
            {
                return redirect('/senarai-semak-harian/?error=already_exists');
            }
            else
            {
                // Update
                $ssh = SenaraiSemakHarian::find($r->_id_ssh);
                $ssh->masa_semakan = $masa_semakan;
                $ssh->status_semakan = $record;
                $ssh->save();
            }
        }
        else
        {
            // Insert
            $ssh = new SenaraiSemakHarian;
            $ssh->user_id = Auth::user()->id;
            $ssh->tarikh_semakan = $db_tarikh_semakan;
            $ssh->masa_semakan = $masa_semakan;
            $ssh->status_semakan = $record;
            $ssh->save();
        }

        return redirect('/senarai-semak-harian');
    }
    public function EditSenaraiSemakHarian(Request $r)
    {
        $id = $r->id;
        $ssh = SenaraiSemakHarian::find($id);

        $s = json_decode($ssh->status_semakan);

        echo "$('#_id_ssh').val('$id');";
        echo "$('#btn_u_print').removeClass('hide');";
        echo "$('#SemakanDialog').modal('show');\n";

        if (count($s) > 0)
        {
            foreach ($s as $_val)
            {
                $sshid = $_val->id;

                if ($sshid == '1')
                {
                    echo "$('#_speedtest_a').val('".$_val->_speedtest_a."');";
                    echo "$('#_speedtest_b').val('".$_val->_speedtest_b."');";
                    echo "$('#_speedtest_c').val('".$_val->_speedtest_c."');";
                    echo "$('#_speedtest_d').val('".$_val->_speedtest_d."');";
                    echo "$('#_speedtest_e').val('".$_val->_speedtest_e."');";
                    echo "$('#_speedtest_a1').val('".$_val->_speedtest_a1."');";
                    echo "$('#_speedtest_b1').val('".$_val->_speedtest_b1."');";
                    echo "$('#_speedtest_c1').val('".$_val->_speedtest_c1."');";
                    echo "$('#_speedtest_d1').val('".$_val->_speedtest_d1."');";
                    echo "$('#_speedtest_e1').val('".$_val->_speedtest_e1."');";

                    $catatan = addslashes(html_entity_decode($_val->catatan,ENT_QUOTES));
                    $catatan = str_replace('<br />', '\n', nl2br($catatan));
                    $catatan = trim(preg_replace('/\s\s+/', '', $catatan));
                    echo "$('#_catatan_$sshid').val('".$catatan."');";
                }
                else
                {
                    if ($_val->status == '1') {
                        echo "$('input[name=\"_status_$sshid\"]').filter('[value=1]').prop('checked', true);";
                    } else {
                        echo "$('input[name=\"_status_$sshid\"]').filter('[value=0]').prop('checked', true);";
                    }

                    $catatan = addslashes(html_entity_decode($_val->catatan,ENT_QUOTES));
                    $catatan = str_replace('<br />', '\n', nl2br($catatan));
                    $catatan = trim(preg_replace('/\s\s+/', '', $catatan));
                    echo "$('#_catatan_$sshid').val('".$catatan."');";
                }
            }

            echo "$('#_tarikh_semakan').val('".$ssh->tarikh_semakan_formatted."');";
            echo "$('#_tarikh_semakan').prop('disabled','disabled');";

            if (strlen($ssh->masa_semakan) > 0) {
                $ms = explode(':', $ssh->masa_semakan);
                echo "$('#_masa_semakan').val('".$ms[0].':'.$ms[1]."');";
            }

            if ($ssh->ppd_semak == '1') {
                $tarikh_ppd_semak = $ssh->tarikh_ppd_semak_formatted;
                echo "$('#_semakan_ppd').addClass('block-content block-content-full block-content-mini text-white bg-success');";
                echo "$('#_semakan_ppd').html('<i class=\"fa fa-check-circle text-white push-5-r\"></i> Telah Disemak Oleh PPD ( $tarikh_ppd_semak )');";
            } else if ($ssh->ppd_semak == '0') {
                echo "$('#_semakan_ppd').addClass('block-content block-content-full block-content-mini text-white bg-warning');";
                echo "$('#_semakan_ppd').html('<i class=\"fa fa-exclamation-circle text-white push-5-r\"></i> PPD Belum Membuat Semakan');";
            } else {
                echo "$('#_semakan_ppd').removeClass('block-content block-content-full block-content-mini text-white bg-success bg-warning');";
                echo "$('#_semakan_ppd').html('');";
            }
        }        
    }
    public function CetakSenaraiSemakHarian($id)
    {
        $ssh = SenaraiSemakHarian::find($id);

        if ($ssh->user_id == Auth::user()->id || Auth::user()->hasRole('ppd') || Auth::user()->hasRole('jpn'))
        {
            $_tarikh_semakan = $ssh->tarikh_semakan;
            $dt = explode('-', $_tarikh_semakan);
            $tarikh_smkan = $dt[2].'-'.$dt[1].'-'.$dt[0];

            $masa_semakan = $ssh->masa_semakan;
            if (strlen($masa_semakan) > 0) {
                $ms = explode(':', $masa_semakan);
                $masa_semakan = $ms[0].':'.$ms[1];
            } else {
                $masa_semakan = '';
            }

            $s = json_decode($ssh->status_semakan);

            if (count($s) > 0)
            {
                $user_id = $ssh->user_id;
                $usr = User::find($user_id);
                $nama_sekolah = $usr->jabatan->nama_sekolah_detail_cetakan;
                $jawatan = $usr->greds->gred_title_cetakan;

                // Data
                $data = '';
                $i = 1;
                $ss_semua = SenaraiSemakan::where('user_id','0')->get();
                foreach ($ss_semua as $sss)
                {
                    if ($sss->id == '1')
                    {
                        foreach ($s as $_val)
                        {
                            $sshid = $_val->id;
                            if ($sshid == '1')
                            {
                                $data .= '<tr>
                                    <td width="20" align="center" valign="top" bgcolor="#FFFFFF">'.$i++.'.</td>
                                    <td valign="top" bgcolor="#FFFFFF">'.$sss->perkara.'</td>
                                    <td valign="top" bgcolor="#FFFFFF">'.$sss->cara_pengujian.'</td>
                                    <td align="center" valign="top" bgcolor="#FFFFFF" width="180">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>Down</td>
                                            <td>Up</td>
                                          </tr>
                                          <tr>
                                            <td>ZOOM-A</td>
                                            <td>'.$_val->_speedtest_a.'</td>
                                            <td>'.$_val->_speedtest_a1.'</td>
                                          </tr>
                                          <tr>
                                            <td>ZOOM-B</td>
                                            <td>'.$_val->_speedtest_b.'</td>
                                            <td>'.$_val->_speedtest_b1.'</td>
                                          </tr>
                                          <tr>
                                            <td>ZOOM-C</td>
                                            <td>'.$_val->_speedtest_c.'</td>
                                            <td>'.$_val->_speedtest_c1.'</td>
                                          </tr>
                                          <tr>
                                            <td>SUPER ZOOM (A)</td>
                                            <td>'.$_val->_speedtest_d.'</td>
                                            <td>'.$_val->_speedtest_d1.'</td>
                                          </tr>
                                          <tr>
                                            <td>SUPER ZOOM (B)</td>
                                            <td>'.$_val->_speedtest_e.'</td>
                                            <td>'.$_val->_speedtest_e1.'</td>
                                          </tr>
                                        </table>
                                    </td>
                                    <td valign="top" bgcolor="#FFFFFF">'.nl2br($_val->catatan).'</td>
                                </tr>';
                            }
                        }
                    }
                    else
                    {
                        foreach ($s as $_val)
                        {
                            $sshid = $_val->id;
                            if ($sshid == $sss->id)
                            {
                                $_status = ($_val->status=='1') ? "BERJAYA":"TIDAK BERJAYA";

                                $data .= '<tr>
                                    <td align="center" valign="top" bgcolor="#FFFFFF">'.$i++.'.</td>
                                    <td valign="top" bgcolor="#FFFFFF">'.$sss->perkara.'</td>
                                    <td valign="top" bgcolor="#FFFFFF">'.$sss->cara_pengujian.'</td>
                                    <td align="center" valign="top" bgcolor="#FFFFFF">'.$_status.'</td>
                                    <td valign="top" bgcolor="#FFFFFF">'.nl2br($_val->catatan).'</td>
                                </tr>';
                            }
                        }
                    }                
                }

                $ss_user = SenaraiSemakan::where('user_id',Auth::user()->id)->get();
                foreach ($ss_user as $ssu) {
                    foreach ($s as $_val)
                    {
                        $sshid = $_val->id;
                        if ($sshid == $ssu->id)
                        {
                            $_status = ($_val->status=='1') ? "BERJAYA":"TIDAK BERJAYA";

                            $data .= '<tr>
                                <td align="center" valign="top" bgcolor="#FFFFFF">'.$i++.'.</td>
                                <td valign="top" bgcolor="#FFFFFF">'.$ssu->perkara.'</td>
                                <td valign="top" bgcolor="#FFFFFF">'.$ssu->cara_pengujian.'</td>
                                <td align="center" valign="top" bgcolor="#FFFFFF">'.$_status.'</td>
                                <td valign="top" bgcolor="#FFFFFF">'.nl2br($_val->catatan).'</td>
                            </tr>';
                        }
                    }
                }

                if ($ssh->ppd_semak == '1')
                {
                    $id_penyemak = $ssh->id_penyemak;
                    $usr = User::find($id_penyemak);

                    $data_semak = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="10"></td>
                      </tr>
                      <tr>
                        <td>Telah Disemak Oleh :</td>
                      </tr>
                      <tr>
                        <td align="left" valign="bottom">
                            <strong>
                                '.strtoupper($usr->name).'<br />
                                '.$usr->greds->gred_title_cetakan.'<br />
                                '.$usr->nama_ppd.'<br /><br />
                            </strong>

                            Tarikh Semakan : <strong>'.$ssh->tarikh_ppd_semak_formatted.'</strong>
                        </td>
                      </tr>
                    </table>';
                }
                else
                {
                    $data_semak = '';
                }


                $ds = DIRECTORY_SEPARATOR;
                $t = new Template;
                $t->Load(public_path().$ds."cetakan".$ds."ssh.tpl");
                $t->Replace('NAMA_SEKOLAH', $nama_sekolah);
                $t->Replace('JAWATAN', $jawatan);
                $t->Replace('NAMA_JURUTEKNIK', strtoupper($usr->name));
                $t->Replace('TARIKH_SEMAKAN', $ssh->tarikh_semakan_formatted.' '.$masa_semakan.' ('.$this->replaceDay(date('l', strtotime($tarikh_smkan))).')');
                $t->Replace('DATA', $data);
                $t->Replace('DATA_SEMAK', $data_semak);
                $_output = $t->Evaluate();

                $options = new Options();
                $options->set('defaultFont', 'Century Gothic');
                $dpdf = new Dompdf($options);
                $dpdf->loadHtml($_output);
                $dpdf->setPaper('A4', 'landscape');
                $dpdf->render();
                $dpdf->add_info('Author',"Juruteknik Komputer Negeri Perak (JTKPK)");
                $dpdf->add_info('Title','Senarai Semak Harian - '.$tarikh_smkan);
                $dpdf->stream("Senarai-Semak-Harian-$tarikh_smkan",array('Attachment'=>0));
            }
            else
            {
                echo "- Rekod tiada dalam pangkalan data ! -";
            }
        }
        else
        {
            echo "<center><h1>Akses Disekat !</h1></center>";
        }
    }

    /**

        ADUAN KEROSAKAN

    */
    public function getLatestNoSiriAduan() {
        $akp = AKP::where('user_id',Auth::user()->id)->whereYear('tarikh_aduan',date('Y'))->orderBy('id','desc')->first();
        if (count($akp) > 0) {
            echo "$('#_no_siri_aduan').val('".($akp->no_siri_aduan + 1)."');";
        } else {
            echo "$('#_no_siri_aduan').val('1');";
        }
        echo "$('#_tarikh_aduan').val('".date('d/m/Y')."');";
    }
    public function AduanKerosakan($mon=null, $year=null)
    {
        $akp = AKP::where('user_id',Auth::user()->id)->get();

        return view('jtk.senarai-aduan-kerosakan',[
            'akp' => $akp,
            'mon' => $mon,
            'year' => $year,
            'error' => $this->req->error
        ]);
    }
    public function SaveAduanKerosakan(Request $r)    
    {
        $kk = KategoriKerosakan::where('parent_id','<>','0')->get();
        $kategori_kerosakan = array();
        foreach ($kk as $dbkk)
        {
            $kkid = $dbkk->id;
            eval("\$_kerosakan = \$r->_kerosakan_".$kkid.";");
            eval("\$_lain = \$r->_lainlain_".$kkid.";");

            if ($_lain != NULL) {
                $kategori_kerosakan[] = array(
                    '_id' => $kkid,
                    '_kerosakan' => $_kerosakan,
                    '_lain' => $_lain
                );
            } else {
                $kategori_kerosakan[] = array(
                    '_id' => $kkid,
                    '_kerosakan' => $_kerosakan
                );
            }
        }        
        $kategori_kerosakan = json_encode($kategori_kerosakan);

        if ($r->_id == '0') {
            $tarikh_aduan = $r->_tarikh_aduan;
            $db_tarikh_aduan = \Carbon\Carbon::createFromFormat('d/m/Y', $tarikh_aduan)->format('Y-m-d');
            $nosiriaduan = $r->_no_siri_aduan;
        } else {
            $dbta = DB::table('aduan_kerosakan')->where('id',$r->_id)->first();
            $db_tarikh_aduan = $dbta->tarikh_aduan;
            $nosiriaduan = $dbta->no_siri_aduan;
        }

        // Get year
        $dt = explode('-', $db_tarikh_aduan);
        if (strlen($r->_tarikh_pemeriksaan) != 0) {
            $tarikh_pemeriksaan = $r->_tarikh_pemeriksaan;
            $tarikh_pemeriksaan = \Carbon\Carbon::createFromFormat('d/m/Y', $tarikh_pemeriksaan)->format('Y-m-d');
        } else {
            $tarikh_pemeriksaan = null;
        }
        if (strlen($r->_tarikh_selesai) != 0) {
            $tarikh_selesai = $r->_tarikh_selesai;
            $tarikh_selesai = \Carbon\Carbon::createFromFormat('d/m/Y', $tarikh_selesai)->format('Y-m-d');
        } else {
            $tarikh_selesai = null;
        }

        // Search
        if (AKP::where('user_id',Auth::user()->id)->whereYear('tarikh_aduan',$dt[0])->where('no_siri_aduan',$nosiriaduan)->count() == 1)
        {
            if ($r->_id == '0')
            {
                return redirect('/aduan-kerosakan/?error=already_exists');
            }
            else
            {
                // Update
                $akp = AKP::find($r->_id);
                $akp->kod_ppd = Auth::user()->kod_ppd;
                $akp->kod_jpn = Auth::user()->kod_jpn;
                $akp->nama = $r->_nama;
                $akp->email = $r->_email;
                $akp->jawatan = $r->_jawatan;
                $akp->no_telefon = $r->_no_telefon;
                $akp->lokasi_peralatan = $r->_lokasi_peralatan;
                $akp->no_dhm = $r->_no_dhm;
                $akp->kategori_kerosakan = $kategori_kerosakan;
                $akp->kategori_aduan = $r->_kategori_aduan;
                $akp->keterangan_kerosakan = $r->_keterangan_kerosakan;
                $akp->laporan_tindakan = $r->_laporan_tindakan;
                $akp->tarikh_pemeriksaan = $tarikh_pemeriksaan;
                $akp->status_aduan = $r->_status_aduan;
                $akp->tarikh_selesai = $tarikh_selesai;
                $akp->hakmilik_peralatan = $r->_hakmilik;
                $akp->save();
            }
        }
        else
        {
            // Insert
            $akp = new AKP;
            $akp->user_id = Auth::user()->id;
            $akp->kod_ppd = Auth::user()->kod_ppd;
            $akp->kod_jpn = Auth::user()->kod_jpn;
            $akp->no_siri_aduan = $r->_no_siri_aduan;
            $akp->tarikh_aduan = $db_tarikh_aduan;
            $akp->nama = $r->_nama;
            $akp->email = $r->_email;
            $akp->jawatan = $r->_jawatan;
            $akp->no_telefon = $r->_no_telefon;
            $akp->lokasi_peralatan = $r->_lokasi_peralatan;
            $akp->no_dhm = $r->_no_dhm;
            $akp->kategori_kerosakan = $kategori_kerosakan;
            $akp->kategori_aduan = $r->_kategori_aduan;
            $akp->keterangan_kerosakan = $r->_keterangan_kerosakan;
            $akp->laporan_tindakan = $r->_laporan_tindakan;
            $akp->tarikh_pemeriksaan = $tarikh_pemeriksaan;
            $akp->status_aduan = $r->_status_aduan;
            $akp->tarikh_selesai = $tarikh_selesai;
            $akp->hakmilik_peralatan = $r->_hakmilik;
            $akp->created_at = Carbon::now();
            $akp->save();
        }

        return redirect('/aduan-kerosakan');
    }
    public function PadamAduanKerosakan(Request $r)
    {
        $akp = AKP::destroy($r->id);
        echo "SweetAlert('success','Berjaya Dipadam !','Rekod aduan kerosakan telah berjaya dipadam !',\"window.location.href='/aduan-kerosakan';\");";
    }
    public function EditAduanKerosakan(Request $r)
    {
        $id = $r->id;
        $akp = AKP::find($id);

        $keterangan_kerosakan = addslashes(html_entity_decode($akp->keterangan_kerosakan,ENT_QUOTES));
        $keterangan_kerosakan = str_replace('<br />', '\n', nl2br($keterangan_kerosakan));
        $keterangan_kerosakan = trim(preg_replace('/\s\s+/', '', $keterangan_kerosakan));

        $laporan_tindakan = addslashes(html_entity_decode($akp->laporan_tindakan,ENT_QUOTES));
        $laporan_tindakan = str_replace('<br />', '\n', nl2br($laporan_tindakan));
        $laporan_tindakan = trim(preg_replace('/\s\s+/', '', $laporan_tindakan));

        $kk = json_decode($akp->kategori_kerosakan);

        echo "$('#_id').val('$id');";
        echo "$('#btn_print').removeClass('hide');";
        echo "$('#AKPDialog').modal('show');\n";

        echo "$('#_tarikh_aduan').val('".$akp->tarikh_aduan_formatted."');";
        echo "$('#_tarikh_aduan').prop('disabled','disabled');";
        echo "$('#_no_siri_aduan').val('".$akp->no_siri_aduan."');";
        echo "$('#_no_siri_aduan').prop('disabled','disabled');";
        echo "$('#_nama').val('".$akp->nama."');";
        echo "$('#_email').val('".$akp->email."');";
        echo "$('#_jawatan').val('".$akp->jawatan."');";
        echo "$('#_no_telefon').val('".$akp->no_telefon."');";
        echo "$('#_lokasi_peralatan').val('".$akp->lokasi_peralatan."');";
        echo "$('#_no_dhm').val('".$akp->no_dhm."');";
        echo "$('#_keterangan_kerosakan').val('".$keterangan_kerosakan."');";
        echo "$('#_laporan_tindakan').val('".$laporan_tindakan."');";
        echo "$('#_tarikh_pemeriksaan').val('".$akp->tarikh_pemeriksaan_formatted."');";
        echo "$('#_tarikh_selesai').val('".$akp->tarikh_selesai_formatted."');";
        
        echo "$('input[name=\"_hakmilik\"]').filter('[value=\"".$akp->hakmilik_peralatan."\"]').prop('checked', true);";
        echo "$('input[name=\"_status_aduan\"]').filter('[value=\"".$akp->status_aduan."\"]').prop('checked', true);";
        echo "$('input[name=\"_kategori_aduan\"]').filter('[value=\"".$akp->kategori_aduan."\"]').prop('checked', true);";
        
        if (count($kk) > 0)
        {
            foreach ($kk as $vl)
            {
                $kkid = $vl->_id;

                if ($vl->_kerosakan != null) {
                    echo "$('input[name=\"_kerosakan_$kkid\"]').filter('[value=\"$kkid\"]').prop('checked', true);";
                } else {
                    echo "$('input[name=\"_kerosakan_$kkid\"]').filter('[value=\"$kkid\"]').prop('checked', false);";
                }

                if (!empty($vl->_lain)) {
                    echo "$('input[name=\"_lainlain_$kkid\"]').val('".$vl->_lain."');";
                }
            }
        }
    }
    public function CetakAduanKerosakan($id)
    {
        $akp = AKP::find($id);

        if ($akp->user_id == Auth::user()->id || Auth::user()->hasRole('ppd') || Auth::user()->hasRole('jpn'))
        {
            $usr = User::find($akp->user_id);
            $nama_sekolah = $usr->jabatan->nama_sekolah_detail_cetakan;
            $jawatan = $usr->greds->gred_title_cetakan;
            $kk = json_decode($akp->kategori_kerosakan);

            $datakk = '';
            foreach (KategoriKerosakan::where('parent_id','0')->get() as $_kk)
            {
                $datakk .= '<table width="95%" border="0" align="center" cellpadding="3" cellspacing="0">
                    <tr>
                      <td colspan="4"><b><u>'.$_kk->kategori.'</u></b></td>
                    </tr>';
                $i = 1;
                $lain = '';
                foreach (KategoriKerosakan::where('parent_id',$_kk->id)->get() as $_skk)
                {

                    foreach ($kk as $vl)
                    {
                        $kkid = $vl->_id;

                        if ($kkid == $_skk->id)
                        {
                            if ($vl->_kerosakan != null) {
                                $bgc = "#666666";
                            } else {
                                $bgc = "#F1F1F1";                                
                            }

                            if (!empty($vl->_lain)) {
                                $lain = "(<i>".$vl->_lain."</i>)";
                            }

                            break;
                        }                        
                    }


                    if ($i == 1) {
                        $datakk .= '<tr>';
                    }

                    if (strlen($lain) > 0) {
                        $datakk .= '<td width="150">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="10" bgcolor="'.$bgc.'">&nbsp;</td>
                                <td width="8">&nbsp;</td>
                                <td>'.$_skk->kategori.' '.$lain.'</td>
                              </tr>
                            </table>
                        </td>';
                    } else {
                        $datakk .= '<td width="150">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="10" bgcolor="'.$bgc.'">&nbsp;</td>
                                <td width="8">&nbsp;</td>
                                <td>'.$_skk->kategori.'</td>
                              </tr>
                            </table>
                        </td>';
                    }

                    if ($i == 3) {
                        $datakk .= '</tr>';
                        $i = 0;
                    }

                    $i++;            
                }                

                $datakk .= '<tr>
                      <td colspan="4" height="2"></td>
                    </tr>
                </table>';
            }

            if (strtoupper($akp->hakmilik_peralatan) == 'KERAJAAN') {
                $bgck = '#666666';
                $bgcp = '#F1F1F1';
            } else if (strtoupper($akp->hakmilik_peralatan) == 'PERSENDIRIAN') {
                $bgck = '#F1F1F1';
                $bgcp = '#666666';
            } else {
                $bgck = '#F1F1F1';
                $bgcp = '#F1F1F1';
            }

            if (strtoupper($akp->kategori_aduan) == 'BIASA') {
                $bgckab = '#666666';
                $bgckas = '#F1F1F1';
            } else if (strtoupper($akp->kategori_aduan) == 'SEGERA') {
                $bgckab = '#F1F1F1';
                $bgckas = '#666666';
            } else {
                $bgckab = '#F1F1F1';
                $bgckas = '#F1F1F1';
            }

            if (strtoupper($akp->status_aduan) == 'SELESAI') {
                $bgc_sa_s = '#666666';
                $bgc_sa_ts = '#F1F1F1';
                $bgc_sa_hkp = '#F1F1F1';
            } else if (strtoupper($akp->status_aduan) == 'TIDAK SELESAI') {
                $bgc_sa_s = '#F1F1F1';
                $bgc_sa_ts = '#666666';
                $bgc_sa_hkp = '#F1F1F1';
            } else if (strtoupper($akp->status_aduan) == 'HANTAR KE PEMBEKAL') {
                $bgc_sa_s = '#F1F1F1';
                $bgc_sa_ts = '#F1F1F1';
                $bgc_sa_hkp = '#666666';
            } else {
                $bgc_sa_s = '#F1F1F1';
                $bgc_sa_ts = '#F1F1F1';
                $bgc_sa_hkp = '#F1F1F1';
            }

            $ds = DIRECTORY_SEPARATOR;
            $t = new Template;
            $t->Load(public_path().$ds."cetakan".$ds."aduan-kerosakan.tpl");
            $t->Replace('PUBLIC_PATH', public_path().$ds."img".$ds);
            $t->Replace('NAMA_SEKOLAH', $nama_sekolah);
            $t->Replace('JAWATAN', $jawatan);
            $t->Replace('NAMA_JURUTEKNIK', strtoupper($usr->name));
            $t->Replace('PPD', $usr->nama_ppd);

            // Data
            $t->Replace('NO_AKP', strtoupper($akp->no_siri_akp));
            $t->Replace('NAMA', strtoupper($akp->nama));
            $t->Replace('EMAIL', $akp->email);
            $t->Replace('DJAWATAN', strtoupper($akp->jawatan));
            $t->Replace('NO_TELEFON', strtoupper($akp->no_telefon));
            $t->Replace('LOKASI_PERALATAN', strtoupper($akp->lokasi_peralatan));
            $t->Replace('NO_DHM', strtoupper($akp->no_dhm));
            $t->Replace('DATA_KK', $datakk);
            $t->Replace('KETERANGAN_KEROSAKAN', nl2br($akp->keterangan_kerosakan));
            $t->Replace('bgc_hakmilik_k', $bgck);
            $t->Replace('bgc_hakmilik_p', $bgcp);
            $t->Replace('bgc_ka_b', $bgckab);
            $t->Replace('bgc_ka_s', $bgckas);
            $t->Replace('LAPORAN_TINDAKAN', nl2br($akp->laporan_tindakan));
            $t->Replace('bgc_sa_s', $bgc_sa_s);
            $t->Replace('bgc_sa_ts', $bgc_sa_ts);
            $t->Replace('bgc_sa_hkp', $bgc_sa_hkp);
            $t->Replace('TARIKH_PEMERIKSAAN', $akp->tarikh_pemeriksaan_formatted);
            $t->Replace('TARIKH_SELESAI', $akp->tarikh_selesai_formatted);

            $_output = $t->Evaluate();

            $options = new Options();
            $options->set('defaultFont', 'Century Gothic');
            $dpdf = new Dompdf($options);
            $dpdf->loadHtml($_output);
            $dpdf->setPaper('A4', 'portrait');
            $dpdf->render();
            $dpdf->add_info('Author',"Juruteknik Komputer Negeri Perak (JTKPK)");
            $dpdf->add_info('Title','Aduan Kerosakan - '.str_replace('/', '-', $akp->no_siri_akp));
            $dpdf->stream("Aduan-Kerosakan_".str_replace('/', '-', $akp->no_siri_akp),array('Attachment'=>0));
        }
        else
        {
            echo "<center><h1>Akses Disekat !</h1></center>";
        }
    }
    
}
