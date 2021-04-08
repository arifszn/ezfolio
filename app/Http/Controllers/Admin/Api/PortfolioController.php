<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\PortfolioConfigContract;
use Constants;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * @var PortfolioConfigContract
     */
    private $portfolioConfig;

    /**
     * Create a new instance
     * 
     * @param PortfolioConfigContract $portfolioConfig 
     * @return void 
     */
    public function __construct(PortfolioConfigContract $portfolioConfig)
    {
        $this->portfolioConfig = $portfolioConfig;
    }

    /**
     * Get configs
     * 
     * @param Request $request 
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $result = $this->portfolioConfig->getAllConfigData();
        } elseif ($request->isMethod('post')) {
            $result = $this->portfolioConfig->setConfigData($request->all());
        }
        
        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Handle seo request
     * 
     * @param Request $request 
     * @return JsonResponse 
     */
    public function seo(Request $request)
    {
        $result = $this->portfolioConfig->setMetaData($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }
}
