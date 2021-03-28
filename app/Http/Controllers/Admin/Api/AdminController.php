<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\AdminContract;
use Constants;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @var AdminContract
     */
    private $admin;

    /**
     * Create a new instance.
     * 
     * @param AdminContract $admin 
     * @return void 
     */
    public function __construct(AdminContract $admin)
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

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
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

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
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

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Get the authenticated admin
     *
     * @return JsonResponse
     */
    public function me()
    {
        $result = $this->admin->me();

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Refresh a token
     *
     * @return JsonResponse
     */
    public function refreshToken()
    {
        $result = $this->admin->refreshToken();

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    public function loginCredentials(Request $request)
    {
        if ($request->isMethod('get')) {
            $result = $this->admin->me();

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        } elseif ($request->isMethod('post')) {
            $result = $this->admin->changeCredential($request->all());

            return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
        }
    }
}
