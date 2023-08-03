<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TermsController extends Controller
{
    public function index(): View|Factory
    {
        return view('frontend.pages.terms');
    }
}
