<?php

namespace App\Http\Controllers\Admin\Api;

use CoreConstants;
use App\Http\Controllers\Controller;
use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\PortfolioConfigInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * @var PortfolioConfigInterface
     */
    private $portfolioConfig;

    /**
     * @var AboutInterface
     */
    private $about;

    /**
     * Create a new instance
     *
     * @param PortfolioConfigInterface $portfolioConfig
     * @param AboutInterface $about
     * @return void
     */
    public function __construct(PortfolioConfigInterface $portfolioConfig, AboutInterface $about)
    {
        $this->portfolioConfig = $portfolioConfig;
        $this->about = $about;
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
        
        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
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

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Handle about request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function about(Request $request)
    {
        if ($request->isMethod('get')) {
            $result = $this->about->getAllFields();
        } elseif ($request->isMethod('post')) {
            $result = $this->about->store($request->all());
        }
        
        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Avatar resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function avatar(Request $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->about->processUpdateAvatarRequest($request->all());
        } elseif ($request->isMethod('delete')) {
            $result = $this->about->processDeleteAvatarRequest($request->file);
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * CV resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cv(Request $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->about->processUpdateCVRequest($request->all());
        } elseif ($request->isMethod('delete')) {
            $result = $this->about->processDeleteCVRequest($request->file);
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Cover resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cover(Request $request)
    {
        if ($request->isMethod('post')) {
            $result = $this->about->processUpdateCoverRequest($request->all());
        } elseif ($request->isMethod('delete')) {
            $result = $this->about->processDeleteCoverRequest($request->file);
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }
}
