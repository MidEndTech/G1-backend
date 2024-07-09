<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function changeLocale(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);

            return response()->json(['message' => 'Locale changed successfully.' . $locale]);
        }

        return response()->json(['message' => 'Locale not supported.'], 400);
    }
}
