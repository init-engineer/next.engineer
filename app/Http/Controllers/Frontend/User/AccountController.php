<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AccountController extends Controller
{
    public function index(): View|Factory
    {
        return view('frontend.user.account');
    }
}
