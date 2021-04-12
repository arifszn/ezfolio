<?php

namespace App\Http\Controllers\Admin;

use Constants;
use App\Http\Controllers\Controller;
use App\Services\Contracts\SettingInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class AdminController extends Controller
{
    /**
     * @var SettingInterface
     */
    private $setting;

    /**
     * Create a new controller instance.
     *
     * @param SettingInterface $setting
     * @return void
     */
    public function __construct(SettingInterface $setting)
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
                'status' => $result['status'],
            ]);
        }

        return view('admin.app')->with([
            'settings' => $settings,
        ]);
    }
}
