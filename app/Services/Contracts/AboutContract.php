<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface AboutContract
{
    /**
     * Get all about fields
     * 
     * @param array $select
     * @return array 
     */
    public function getAllFields(array $select = ['*']);

    /**
     * Store/update data
     * 
     * @param array $data 
     * @return array 
     */
    public function store(array $data);

    /**
     * Process the update avatar request
     * 
     * @param Request $request 
     * @param int $adminId 
     * @return array
     */
    public function processUpdateAvatarRequest(Request $request, int $adminId);

    /**
     * Process the delete avatar request
     * 
     * @param string $file 
     * @param int $adminId 
     * @return array 
     */
    public function processDeleteAvatarRequest(string $file, int $adminId);

    /**
     * Process the update cover request
     * 
     * @param Request $request 
     * @param int $adminId 
     * @return array
     */
    public function processUpdateCoverRequest(Request $request, int $adminId);

    /**
     * Process the delete cover request
     * 
     * @param string $file 
     * @param int $adminId 
     * @return array 
     */
    public function processDeleteCoverRequest(string $file, int $adminId);

    /**
     * Process the update CV request
     * 
     * @param Request $request 
     * @param int $adminId 
     * @return array 
     */
    public function processUpdateCVRequest(Request $request, int $adminId);

    /**
     * Process the delete CV request
     * @param string $file 
     * @param int $adminId 
     * @return array 
     */
    public function processDeleteCVRequest(string $file, int $adminId);
}
