<?php

namespace App\Http\Controllers\Admin\Api;

use CoreConstants;
use App\Http\Controllers\Controller;
use App\Services\Contracts\AdminInterface;
use App\Services\Contracts\SettingInterface;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @var AdminInterface
     */
    private $admin;

    /**
     * @var SettingInterface
     */
    private $setting;

    /**
     * Create a new instance
     *
     * @param AdminInterface $admin
     * @param SettingInterface $setting
     * @return void
     */
    public function __construct(AdminInterface $admin, SettingInterface $setting)
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
        } elseif ($request->isMethod('post')) {
            $result = $this->setting->setSettingData($request->all());
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
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
        } elseif ($request->isMethod('delete')) {
            $result = $this->setting->processDeleteLogoRequest($request->file);
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Favicon resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function favicon(Request $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->setting->processUpdateFaviconRequest($request->all());
        } elseif ($request->isMethod('delete')) {
            $result = $this->setting->processDeleteFaviconRequest($request->file);
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    public function storeMailSettings(Request $request)
    {
        $result = $this->setting->storeMailSettings($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }
}
