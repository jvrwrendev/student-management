<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Redirect the authenticated user to their role home.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(Auth::user()->homeRoute());
    }
}
