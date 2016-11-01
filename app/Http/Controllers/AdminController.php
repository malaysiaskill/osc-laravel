<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Packages;
use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin', ['only' => ['Packages']]);
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
    public function DeleteUser($id)
    {
        $package = User::destroy($id);
        return redirect('/admin/users');
    }
}
