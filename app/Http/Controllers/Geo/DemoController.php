<?php

namespace App\Http\Controllers\Geo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{

    public function index(Request $request){
        return view('Geo.index');
    }
}
