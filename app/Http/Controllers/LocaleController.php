<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function change(string $locale): RedirectResponse
    {
        app()->setLocale($locale);

        session()->put('locale', $locale);

        return redirect()->back();
    }
}
