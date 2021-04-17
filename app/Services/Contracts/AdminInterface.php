<?php

namespace App\Services\Contracts;

interface AdminInterface
{
    /**
     * Get the corresponding model
     *
     * @return User
     */
    public function getModel();

    /**
     * Handle login
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleLogin(array $data);

    /**
     * Handle signup
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleSignup(array $data);

    /**
     * Fetch data by email
     *
     * @param string $email
     * @param array $select
     * @return array
     * @throws InvalidArgumentException
     */
    public function getByEmail(string $email, array $select = ['*']);

    /**
     * Handle forget password
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleForgetPassword(array $data);

    /**
     * Handle reset password
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleResetPassword(array $data);

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function me();

    /**
     * Refresh a token
     *
     * @return JsonResponse
     */
    public function refreshToken();

    /**
     * Change login credentials
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function changeCredential(array $data);

    /**
     * Fetch admin information by admin id
     *
     * @param int $adminId
     * @param array $select
     * @return array
     * @throws InvalidArgumentException
     */
    public function getAdminById(int $adminId, array $select = ['*']);

    /**
     * Get stats
     *
     * @param string $todayStartDate
     * @param string $todayEndDate
     * @param string $thisWeekStartDate
     * @param string $thisWeekEndDate
     * @param string $thisMonthStartDate
     * @param string $thisMonthEndDate
     * @param boolean $visitorData
     * @param boolean $messageData
     * @param boolean $skillData
     * @param boolean $educationData
     * @param boolean $experienceData
     * @param boolean $projectData
     * @param boolean $currentTemplate
     * @return array
     */
    public function getStats(
        string $todayStartDate = null,
        string $todayEndDate = null,
        string $thisWeekStartDate = null,
        string $thisWeekEndDate = null,
        string $thisMonthStartDate = null,
        string $thisMonthEndDate = null,
        bool $visitorData = true,
        bool $messageData = true,
        bool $skillData = true,
        bool $educationData = true,
        bool $experienceData = true,
        bool $projectData = true,
        bool $serviceData = true,
        bool $currentTemplate = true
    );
}
