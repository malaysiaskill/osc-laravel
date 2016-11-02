<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Packages;
use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin',[
            'only' => ['Packages', 'ActivatePackages', 'DeactivatePackages', 'DeletePackages']
        ]);
    }

    /**

    PAKEJ SISTEM

    */
    public function Packages()
    {
    	return view('admin.packages', ['packages' => Packages::all()]);
    }
    public function ActivatePackages($id)
    {
        $package = Packages::find($id);
        $package->package_status = 1;
        $package->save();

        return redirect('/admin/packages');
    }
    public function DeactivatePackages($id)
    {
        $package = Packages::find($id);
        $package->package_status = 0;
        $package->save();

        return redirect('/admin/packages');   
    }
    public function DeletePackages($id)
    {
        $package = Packages::destroy($id);
        return redirect('/admin/packages');
    }

    /**

    SENARAI PENGGUNA

    */
    public function Users()
    {
    	return view('admin.users', ['users' => User::all()]);
    }
    public function SaveUser(Request $r)
    {
        if ($r->_uid != 0 || $r->_uid != '0')
        {
            # Update User Data
            $user = User::find($r->_uid);
            $user->name = $r->_name;
            $user->email = $r->_email;
            if (strlen($r->_pwd) != 0) {
                $user->password = bcrypt($r->_pwd);
            }
            $user->role = $r->_usergroups;
            $user->gred = $r->_gred;

            if (strtolower($r->_usergroups) == 'jpn') {
                $user->kod_jpn = $r->_kodjpn;
            } else if (strtolower($r->_usergroups) == 'ppd') {
                $user->kod_jpn = $r->_kodjpn;
                $user->kod_ppd = $r->_kodppd;
            } else if (strtolower($r->_usergroups) == 'leader' || strtolower($r->_usergroups) == 'user') {
                $user->kod_jpn = $r->_kodjpn;
                $user->kod_ppd = $r->_kodppd;
                $user->kod_jabatan = $r->_kodsek;
            } else {
                // nothing
            }
            $user->save();
        }
        else
        {
            # Create New User
            $user = new User;
            $user->name = $r->_name;
            $user->email = $r->_email;
            $user->password = bcrypt($r->_pwd);
            $user->role = $r->_usergroups;
            $user->gred = $r->_gred;
            if (strtolower($r->_usergroups) == 'jpn') {
                $user->kod_jpn = $r->_kodjpn;
            } else if (strtolower($r->_usergroups) == 'ppd') {
                $user->kod_jpn = $r->_kodjpn;
                $user->kod_ppd = $r->_kodppd;
            } else if (strtolower($r->_usergroups) == 'leader' || strtolower($r->_usergroups) == 'user') {
                $user->kod_jpn = $r->_kodjpn;
                $user->kod_ppd = $r->_kodppd;
                $user->kod_jabatan = $r->_kodsek;
            } else {
                // nothing
            }
            $user->save();
        }

        return redirect('/admin/users');
    }
    public function GetUser(Request $r, $id)
    {
        $user = User::find($id);
        $name = $user->name;
        $email = $user->email;
        $role = $user->role;
        $gred = $user->gred;

        $kodjpn = $user->kod_jpn;
        $kodppd = $user->kod_ppd;
        $kodjab = $user->kod_jabatan;
        
        echo "$('#_uid').val('$id');";
        echo "$('#UserDialog').modal('show');";

        echo "$('#_name').val('$name');";
        echo "$('#_email').val('$email');";
        echo "$('#_usergroups').val(\"$role\").trigger(\"change\");";
        echo "$('#_gred').val(\"$gred\").trigger(\"change\");";
        if (strtolower($role) == 'jpn') {
            echo "$('#_kodjpn').val(\"$kodjpn\").trigger(\"change\");";
        } else if (strtolower($role) == 'ppd') {
            echo "$('#_kodjpn').val(\"$kodjpn\").trigger(\"change\");";
            echo "$('#_kodppd').val(\"$kodppd\").trigger(\"change\");";
        } else if (strtolower($role) == 'user' || strtolower($role) == 'leader') {
            echo "$('#_kodjpn').val(\"$kodjpn\").trigger(\"change\");";
            echo "$('#_kodppd').val(\"$kodppd\").trigger(\"change\");";
            echo "$('#_kodsek').val(\"$kodjab\").trigger(\"change\");";
        } else {}
    }
    public function DeleteUser($id)
    {
        $package = User::destroy($id);
        return redirect('/admin/users');
    }
}
