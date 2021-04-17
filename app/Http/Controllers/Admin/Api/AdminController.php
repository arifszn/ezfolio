<?php

namespace App\Http\Controllers\Admin\Api;

use CoreConstants;
use App\Http\Controllers\Controller;
use App\Services\Contracts\AdminInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @var AdminContract
     */
    private $admin;

    /**
     * Create a new instance
     *
     * @param AdminInterface $admin
     * @return void
     */
    public function __construct(AdminInterface $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Handle login
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function login(Request $request)
    {
        $result = $this->admin->handleLogin($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Handle forget password
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function forgetPassword(Request $request)
    {
        $result = $this->admin->handleForgetPassword($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Handle reset password
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function resetPassword(Request $request)
    {
        $result = $this->admin->handleResetPassword($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Get the authenticated admin
     *
     * @return JsonResponse
     */
    public function me()
    {
        $result = $this->admin->me();

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Refresh a token
     *
     * @return JsonResponse
     */
    public function refreshToken()
    {
        $result = $this->admin->refreshToken();

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Login credentials resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loginCredentials(Request $request)
    {
        if ($request->isMethod('get')) {
            $result = $this->admin->me();
        } elseif ($request->isMethod('post')) {
            $result = $this->admin->changeCredential($request->all());
        }

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Get stats for dashboard
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function stats(Request $request)
    {
        $result = $this->admin->getStats(
            $request->todayStartDate,
            $request->todayEndDate,
            $request->thisWeekStartDate,
            $request->thisWeekEndDate,
            $request->thisMonthStartDate,
            $request->thisMonthEndDate
        );

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }
}
