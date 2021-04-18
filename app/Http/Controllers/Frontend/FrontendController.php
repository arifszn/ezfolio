<?php

namespace App\Http\Controllers\Frontend;

use App\Events\FrontendVisited;
use App\Http\Controllers\Controller;
use App\Services\Contracts\FrontendInterface;
use Config;
use CoreConstants;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * @var FrontendInterface
     */
    private $frontend;

    /**
     * Create a new instance
     *
     * @param FrontendInterface $frontend
     * @return void
     */
    public function __construct(FrontendInterface $frontend)
    {
        $this->frontend = $frontend;
    }

    /**
     * Handle request
     *
     * @param Request $request
     * @return View|Factory
     */
    public function index(Request $request)
    {
        $data = $this->frontend->getAllData();

        if ($data['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
            return view('errors.404');
        } else {
            $data = $data['payload'];
        }

        $data['demoMode'] = Config::get('custom.demo_mode');
        
        if (empty($data['about'])) {
            return view('errors.custom', ['message' => 'Database has not propagated properly. Try running migration with seed.']);
        }

        if ((int)$data['portfolioConfig']['maintenanceMode'] === CoreConstants::TRUE) {
            return view('frontend.maintenance', $data);
        }

        $template = $data['portfolioConfig']['template'];

        return view('frontend.theme.'.$template, $data);
    }

    /**
     * Handle pixel tracker
     *
     * @param Request $request
     * @return void
     */
    public function pixelTracker(Request $request)
    {
        if (!empty($request->event) && $request->event == 'page_visit') {
            FrontendVisited::dispatch($request->all());
        }
        
        header('Content-type: image/gif');
        echo base64_decode('R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
    }
}
