<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\AdminContract;
use App\Services\Contracts\SettingContract;
use Constants;
use Illuminate\Http\Request;
use Log;

class SettingController extends Controller
{
    /**
     * @var AdminContract
     */
    private $admin;

    /**
     * 
     * @var SettingContract
     */
    private $setting;

    /**
     * Create a new instance.
     * 
     * @param AdminContract $admin 
     * @param SettingContract $setting 
     * @return void 
     */
    public function __construct(AdminContract $admin, SettingContract $setting)
    {
        $this->admin = $admin;
        $this->setting = $setting;
    }
    
    /**
     * Settings resource
     * 
     * @param Request $request 
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $result = $this->setting->getSettingsData();

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        } elseif ($request->isMethod('post')) {
            $result = $this->setting->setSettingData($request->all());

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        }
    }

    /**
     * Logo resource
     * 
     * @param Request $request 
     * @return JsonResponse
     */
    public function logo(Request $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->setting->processUpdateLogoRequest($request->all());

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        } elseif ($request->isMethod('delete')) {
            Log::info($request->all());
            Log::info(request()->getContent());
            $result = $this->setting->processDeleteLogoRequest($request->file);

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        }
    }
}
