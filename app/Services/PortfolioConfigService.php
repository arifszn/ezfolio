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
                $result = $this->model->updateOrCreate([
                    'setting_key' => $data['setting_key'],
                ], [
                    'setting_value' => isset($data['setting_value']) ? $data['setting_value'] : '',
                    'default_value' => isset($data['default_value']) ? $data['default_value'] : ''
                ]);
            } else {
                $result = $this->model->updateOrCreate([
                    'setting_key' => $data['setting_key'],
                ], [
                    'setting_value' => $data['setting_value']
                ]);
            }

            if ($result) {
                return [
                    'message' => 'Data is successfully updated',
                    'payload' => $result,
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
                $result = $this->getConfigByKey(PortfolioConfig::TEMPLATE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['template'] = $result['payload']->setting_value;
                } else {
                    $data['template'] = 'procyon';
                }
            }

            if ($accentColor) {
                $result = $this->getConfigByKey(PortfolioConfig::ACCENT_COLOR, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['accentColor'] = $result['payload']->setting_value;
                } else {
                    $data['accentColor'] = '#0168fa';
                }
            }

            if ($googleAnalyticsId) {
                $result = $this->getConfigByKey(PortfolioConfig::GOOGLE_ANALYTICS_ID, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['googleAnalyticsId'] = $result['payload']->setting_value;
                } else {
                    $data['googleAnalyticsId'] = '';
                }
            }

            if ($maintenanceMode) {
                $result = $this->getConfigByKey(PortfolioConfig::MAINTENANCE_MODE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['maintenanceMode'] = $result['payload']->setting_value;
                } else {
                    $data['maintenanceMode'] = Constants::FALSE;
                }
            }

            if ($menu) {
                $result = $this->getConfigByKey(PortfolioConfig::MENU_ABOUT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['about'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['about'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_SKILL, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['skills'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['skills'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_EDUCATION, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['education'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['education'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_EXPERIENCE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['experiences'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['experiences'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_PROJECT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['projects'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['projects'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_SERVICE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['services'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['services'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_CONTACT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['contact'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['contact'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::MENU_FOOTER, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['menu']['footer'] = $result['payload']->setting_value;
                } else {
                    $data['menu']['footer'] = Constants::TRUE;
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

            $result = $this->insertOrUpdate($newData);
            
            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                return [
                    'message' => 'Config is successfully updated',
                    'payload' => $result['payload'],
                    'status' => Constants::STATUS_CODE_SUCCESS
                ]; 
            } else {
                return $result;
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
