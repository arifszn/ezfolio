<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin frontend
     * 
     * @return View|Factory 
     */
    public function app()
    {
        return view('admin.app');
    }
}
