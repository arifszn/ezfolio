<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\EducationContract;
use Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * @var EducationContract
     */
    private $education;

    /**
     * Create a new instance
     * 
     * @param EducationContract $education
     * @return void 
     */
    public function __construct(EducationContract $education)
    {
        $this->education = $education;
    }

    /**
     * Get all items
     * 
     * @param Request $request 
     * @return JsonResponse 
     */
    public function index(Request $request)
    {
        $result = $this->education->getAllFieldsWithPaginate($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Store a new item
     * 
     * @param Request $request 
     * @return JsonResponse 
     */
    public function store(Request $request)
    {
        $result = $this->education->store($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Show the given new item
     * 
     * @param int $id 
     * @return JsonResponse 
     */
    public function show(int $id)
    {
        $result = $this->education->getById($id);

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Update the given item
     * 
     * @param Request $request 
     * @param int $id 
     * @return JsonResponse 
     */
    public function update(Request $request, int $id)
    {
        $result = $this->education->store($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }

    /**
     * Destroy the given items
     * 
     * @param Request $request 
     * @return JsonResponse 
     */
    public function destroy(Request $request)
    {
        $result = $this->education->deleteByIds($request->ids);

        return response()->json($result, !empty($result['status']) ? $result['status'] : Constants::STATUS_CODE_SUCCESS);
    }
}
