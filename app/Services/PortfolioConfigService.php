<?php

namespace App\Services;

use App\Helpers\Constants;
use App\Models\PortfolioConfig;
use App\Services\Contracts\PortfolioConfigInterface;
use Log;
use Str;
use Validator;

class PortfolioConfigService implements PortfolioConfigInterface
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
     * @param boolean $visibility
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
        bool $visibility = true,
        bool $script = true
    ) {
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

            if ($visibility) {
                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_ABOUT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['about'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['about'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_SKILL, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['skills'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['skills'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_EDUCATION, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['education'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['education'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_EXPERIENCE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['experiences'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['experiences'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_PROJECT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['projects'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['projects'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_SERVICE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['services'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['services'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_CONTACT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['contact'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['contact'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_FOOTER, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['footer'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['footer'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_CV, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['cv'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['cv'] = Constants::TRUE;
                }

                $result = $this->getConfigByKey(PortfolioConfig::VISIBILITY_SKILL_PERCENT, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['visibility']['skillPercent'] = $result['payload']->setting_value;
                } else {
                    $data['visibility']['skillPercent'] = Constants::TRUE;
                }
            }

            if ($script) {
                $result = $this->getConfigByKey(PortfolioConfig::SCRIPT_HEADER, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['script']['header'] = $result['payload']->setting_value;
                } else {
                    $data['script']['header'] = '';
                }

                $result = $this->getConfigByKey(PortfolioConfig::SCRIPT_FOOTER, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['script']['footer'] = $result['payload']->setting_value;
                } else {
                    $data['script']['footer'] = '';
                }
            }

            if ($seo) {
                $result = $this->getConfigByKey(PortfolioConfig::META_TITLE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['seo']['title'] = $result['payload']->setting_value;
                } else {
                    $data['seo']['title'] = '';
                }

                $result = $this->getConfigByKey(PortfolioConfig::META_AUTHOR, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['seo']['author'] = $result['payload']->setting_value;
                } else {
                    $data['seo']['author'] = '';
                }

                $result = $this->getConfigByKey(PortfolioConfig::META_DESCRIPTION, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['seo']['description'] = $result['payload']->setting_value;
                } else {
                    $data['seo']['description'] = '';
                }

                $result = $this->getConfigByKey(PortfolioConfig::META_IMAGE, ['setting_value']);
                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    $data['seo']['image'] = $result['payload']->setting_value;
                } else {
                    $data['seo']['image'] = '';
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

    /**
     * Store meta data
     *
     * @param array $data
     * @return array
     */
    public function setMetaData(array $data)
    {
        try {
            $count = 0;
            $inserted = [];

            foreach ($data as $key => $value) {
                if ($key === 'title') {
                    $newData = [
                        'setting_key' => PortfolioConfig::META_TITLE,
                        'setting_value' => isset($value) ? $value : '',
                    ];
                    $result = $this->insertOrUpdate($newData);

                    if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                        $count++;
                        $inserted['title'] = $result['payload']->setting_value;
                    } else {
                        Log::error($result['payload']);
                    }
                } elseif ($key === 'author') {
                    $newData = [
                        'setting_key' => PortfolioConfig::META_AUTHOR,
                        'setting_value' => isset($value) ? $value : '',
                    ];
                    $result = $this->insertOrUpdate($newData);

                    if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                        $count++;
                        $inserted['author'] = $result['payload']->setting_value;
                    } else {
                        Log::error($result['payload']);
                    }
                } elseif ($key === 'description') {
                    $newData = [
                        'setting_key' => PortfolioConfig::META_DESCRIPTION,
                        'setting_value' => isset($value) ? $value : '',
                    ];
                    $result = $this->insertOrUpdate($newData);

                    if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                        $count++;
                        $inserted['description'] = $result['payload']->setting_value;
                    } else {
                        Log::error($result['payload']);
                    }
                } elseif ($key === 'image') {
                    $file = $data['image'];
                    if ($file) {
                        $extension = $file->extension() ? $file->extension() : 'png';
                        $fileName = Str::random(10). '_'. time() .'.'. $extension;
                        $pathName = 'assets/common/img/meta-image/';
                        
                        if (!file_exists($pathName)) {
                            mkdir($pathName, 0777, true);
                        }

                        if ($file->move($pathName, $fileName)) {
                            //delete previous image
                            try {
                                $oldImageResponse = $this->getConfigByKey(PortfolioConfig::META_IMAGE);
                                if ($oldImageResponse['status'] === Constants::STATUS_CODE_SUCCESS && file_exists($oldImageResponse['payload']->setting_value)) {
                                    unlink($oldImageResponse['payload']->setting_value);
                                }
                            } catch (\Throwable $th) {
                                Log::error($th->getMessage());
                            }

                            $newData = [
                                'setting_key' => PortfolioConfig::META_IMAGE,
                                'setting_value' => $pathName.$fileName,
                            ];
                            $result = $this->insertOrUpdate($newData);

                            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                                $count++;
                                $inserted['image'] = $result['payload']->setting_value;
                            } else {
                                Log::error($result['payload']);
                            }
                        }
                    } else {
                        //delete previous image
                        try {
                            $oldImageResponse = $this->getConfigByKey(PortfolioConfig::META_IMAGE);
                            if ($oldImageResponse['status'] === Constants::STATUS_CODE_SUCCESS && file_exists($oldImageResponse['payload']->setting_value)) {
                                unlink($oldImageResponse['payload']->setting_value);
                            }
                        } catch (\Throwable $th) {
                            Log::error($th->getMessage());
                        }

                        $newData = [
                            'setting_key' => PortfolioConfig::META_IMAGE,
                            'setting_value' => '',
                        ];
                        $result = $this->insertOrUpdate($newData);
                    }
                }
            }
            if ($count) {
                return [
                    'message' => 'SEO info is successfully saved',
                    'payload' => [
                        'count' => $count,
                        'inserted' => $inserted
                    ],
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Nothing is updated',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
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
}
