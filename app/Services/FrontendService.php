<?php

namespace App\Services;

use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\EducationInterface;
use App\Services\Contracts\ExperienceInterface;
use App\Services\Contracts\FrontendInterface;
use App\Services\Contracts\PortfolioConfigInterface;
use App\Services\Contracts\ProjectInterface;
use App\Services\Contracts\ServiceInterface;
use App\Services\Contracts\SkillInterface;
use CoreConstants;
use Log;
use Validator;

class FrontendService implements FrontendInterface
{
    /**
     * Create a new service instance
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all data for frontend
     *
     * @return array
     */
    public function getAllData()
    {
        try {
            $data = [];

            //portfolio config
            $result = resolve(PortfolioConfigInterface::class)->getAllConfigData();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['portfolioConfig'] = $result['payload'];
            }

            //about
            $result = resolve(AboutInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['about'] = $result['payload'];
            }

            //skill
            $result = resolve(SkillInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['skills'] = $result['payload'];
            }

            //education
            $result = resolve(EducationInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['education'] = $result['payload'];
            }

            //experiences
            $result = resolve(ExperienceInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['experiences'] = $result['payload'];
            }

            //projects
            $result = resolve(ProjectInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['projects'] = $result['payload'];
            }

            //services
            $result = resolve(ServiceInterface::class)->getAll();
            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['services'] = $result['payload'];
            }
            
            return [
                'message' => 'Data is fetched successfully',
                'payload' => $data,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get all projects
     *
     * @return array
     */
    public function getAllProjects()
    {
        try {
            $result = resolve(ProjectInterface::class)->getAll();

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                return [
                    'message' => 'Projects are fetched successfully',
                    'payload' => $result['payload'],
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return $result;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }
}
