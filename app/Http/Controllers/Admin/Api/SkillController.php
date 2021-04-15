<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\SkillInterface;
use CoreConstants;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    /**
     * @var SkillInterface
     */
    private $skill;

    /**
     * Create a new instance
     *
     * @param SkillInterface $skill
     * @return void
     */
    public function __construct(SkillInterface $skill)
    {
        $this->skill = $skill;
    }

    /**
     * Get all items
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $result = $this->skill->getAllWithPaginate($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Store a new item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $result = $this->skill->store($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Show the given new item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $result = $this->skill->getById($id);

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
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
        $result = $this->skill->store($request->all());

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }

    /**
     * Destroy the given items
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->skill->deleteByIds($request->ids);

        return response()->json($result, !empty($result['status']) ? $result['status'] : CoreConstants::STATUS_CODE_SUCCESS);
    }
}
