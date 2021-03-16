<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\SettingContract;
use Constants;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @var SettingContract
     */
    private $setting;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingContract $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Display admin frontend
     * 
     * @return View|Factory 
     */
    public function app()
    {
        $result = $this->setting->getSettingsData();

        if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
            $settings = $result['payload'];
        } else {
            return view('errors.custom')->with([
                'message' => $result['message'],
                'status'   => $result['status'],
            ]);
        }

        return view('admin.app')->with([
            'settings' => $settings,
        ]);
    }
}
