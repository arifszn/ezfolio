<?php

namespace App\Http\Controllers\Admin;

use CoreConstants;
use App\Http\Controllers\Controller;
use App\Services\Contracts\SettingInterface;
use Artisan;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Symfony\Component\Console\Exception\CommandNotFoundException;

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

        if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
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

    /**
     * Optimize app
     *
     * @param Request $request
     * @return Response|ResponseFactory|Redirector|RedirectResponse
     */
    public function optimize(Request $request)
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        
        if ($request->ajax() || $request->wantsJson()) {
            return response([
                'message' => 'App is optimized successfully',
                'payload' => null,
                'status'  => CoreConstants::STATUS_CODE_SUCCESS
            ]);
        } else {
            return redirect(route('admin.app'));
        }
    }
}
