<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin', ['only' => ['Users']]);
    }

    public function Index()
    {
    	return 'Admin Section';
    }

    public function Users()
    {
    	return 'Super Admin Section';
    }
}
