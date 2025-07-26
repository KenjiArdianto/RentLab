<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{

    public function __invoke(Request $request)
    {
        Session::put('lang', $request->lang);

        // App::setLocale($request->lang);

        return redirect()->back();
    }
}