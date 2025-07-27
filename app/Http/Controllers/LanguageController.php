<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switchLang(Request $request, $lang)
    {
        // Only allow supported locales
        if (in_array($lang, ['en', 'id'])) {
            Session::put('locale', $lang);
            App::setLocale($lang); // Optional: useful if used immediately
        }

        return redirect()->back();
    }
}