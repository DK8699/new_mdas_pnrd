<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    //
    public function index()
    {
        return view('public/index');
    }

    public function assamMap(){
        return view('assam-map');
    }
}
