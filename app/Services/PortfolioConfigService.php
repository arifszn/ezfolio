<?php

namespace App\Services;

use App;
use App\Constant;
use App\Models\PortfolioConfig;
use App\Services\Contracts\PortfolioConfigContract;
use Constants;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Validator;

class PortfolioConfigService implements PortfolioConfigContract
{
    /**
     * Eloquent instance
     * 
     * @var PortfolioConfig
     */
    private $model;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(PortfolioConfig $portfolioConfig)
    {
        $this->model = $portfolioConfig;
    }

    /**
     * If config exist, update it. Otherwise insert new
     * 
     * @param array $data 
     * @return array 
     */
    public function insertOrUpdate(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'setting_key' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Validation Error',
                    'payload' => $validate->errors(),
                    'status'  => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            if (isset($data['default_value'])) {
                $response = $this->model->updateOrCreate([
                    'setting_key' => $data['setting_key'],
                ], [
                    'setting_value' => isset($data['setting_value']) ? $data['setting_value'] : '',
                    'default_value' => isset($data['default_value']) ? $data['default_value'] : ''
                ]);
            } else {
                $response = $this->model->updateOrCreate([
                    'setting_key' => $data['setting_key'],
                ], [
                    'setting_value' => $data['setting_value']
                ]);
            }

            if ($response) {
                return [
                    'message' => 'Data is successfully updated',
                    'payload' => $response,
                    'status'  => Constants::STATUS_CODE_SUCCESS
                ]; 
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status'  => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get single config by key
     * 
     * @param int $key
     * @param array $select 
     * @return array 
     */
    public function getConfigByKey(int $key, array $select = ['*'])
    {
        try {
            $result = $this->model
                        ->select($select)
                        ->where('setting_key', $key)
                        ->first();
            if ($result) {
                return [
                    'message' => 'Config is fetched successfully',
                    'payload' => $result,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];  
            } else {
                return [
                    'message' => 'Config is not found',
                    'payload' => null,
                    'status'  => Constants::STATUS_CODE_NOT_FOUND
                ]; 
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get all related Configs
     *
     * @param boolean $accentColor
     * @param boolean $googleAnalyticsId
     * @param boolean $maintenanceMode
     * @param boolean $template
     * @param boolean $skillPercent
     * @param boolean $seo
     * @param boolean $menu
     * @param boolean $script
     * @return array
     */
    public function getAllConfigData(
        bool $accentColor = true,
        bool $googleAnalyticsId = true,
        bool $maintenanceMode = true,
        bool $template = true,
        bool $skillPercent = true,
        bool $seo = true,
        bool $menu = true,
        bool $script = true
    )
    {
        try {
            $data = [];

            if ($template) {
                $response = $this->getConfigByKey(PortfolioConfig::TEMPLATE, ['setting_value']);
                if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['template'] = $response['payload']->setting_value;
                } else {
                    $data['template'] = 'procyon';
                }
            }

            if ($accentColor) {
                $response = $this->getConfigByKey(PortfolioConfig::ACCENT_COLOR, ['setting_value']);
                if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['accentColor'] = $response['payload']->setting_value;
                } else {
                    $data['accentColor'] = '#0168fa';
                }
            }

            if ($googleAnalyticsId) {
                $response = $this->getConfigByKey(PortfolioConfig::GOOGLE_ANALYTICS_ID, ['setting_value']);
                if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['googleAnalyticsId'] = $response['payload']->setting_value;
                } else {
                    $data['googleAnalyticsId'] = '';
                }
            }

            if ($maintenanceMode) {
                $response = $this->getConfigByKey(PortfolioConfig::MAINTENANCE_MODE, ['setting_value']);
                if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['maintenanceMode'] = $response['payload']->setting_value;
                } else {
                    $data['maintenanceMode'] = Constants::FALSE;
                }
            }

            return [
                'message' => 'Configs are fetched successfully',
                'payload' => $data,
                'status' => Constants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Set single Config
     * 
     * @param array $data 
     * @return array 
     */
    public function setConfigData(array $data)
    {
        try {

            $validate = Validator::make($data, [
                'setting_key' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Validation Error',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['setting_key']   = $data['setting_key'];
            $newData['setting_value'] = isset($data['setting_value']) ? $data['setting_value'] : '';

            $response = $this->insertOrUpdate($newData);
            
            if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                return [
                    'message' => 'Config is successfully updated',
                    'payload' => $response['payload'],
                    'status' => Constants::STATUS_CODE_SUCCESS
                ]; 
            } else {
                return $response;
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => Constants::STATUS_CODE_ERROR
            ];
        }
    }
}
