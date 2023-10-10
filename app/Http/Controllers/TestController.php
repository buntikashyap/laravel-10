<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $testhelper = Helper::testhelper();
        dd($testhelper);
    }
}
