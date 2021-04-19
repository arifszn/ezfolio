<?php

namespace App\Services;

use CoreConstants;
use App\Models\Setting;
use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\SettingInterface;
use Config;
use Log;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Str;
use Validator;

class SettingService implements SettingInterface
{
    /**
     * Eloquent instance
     *
     * @var Setting
     */
    private $model;

    /**
     * Create a new service instance.
     *
     * @param Setting $setting
     * @return void
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * If setting exist, update it. Otherwise insert new
     *
     * @param array $data
     * @return array
     */
    public function insertOrUpdate(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'setting_key' => 'required',
                'setting_value' => 'required',
                'default_value' => 'required|sometimes',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            if (isset($data['default_value'])) {
                $result = $this->model->updateOrCreate([
                    'setting_key' => $data['setting_key'],
                ], [
                    'setting_value' => $data['setting_value'],
                    'default_value' => $data['default_value']
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
                    'message' => 'Data is saved successfully',
                    'payload' => $result,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get single setting by key
     *
     * @param int $key
     * @param array $select
     * @return array
     */
    public function getSettingByKey(int $key, array $select = ['*'])
    {
        try {
            $result = $this->model
                        ->select($select)
                        ->where('setting_key', $key)
                        ->first();

            if ($result) {
                return [
                    'message' => 'Setting is fetched successfully',
                    'payload' => $result,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Setting is not found',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get all related settings
     *
     * @return array
     */
    public function getSettingsData()
    {
        try {
            //get accent color
            $result = $this->getSettingByKey(CoreConstants::SETTING__ACCENT_COLOR, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['accentColor'] = $result['payload']->setting_value;
            } else {
                $data['accentColor'] = '#1890ff';
            }

            //get short menu
            $result = $this->getSettingByKey(CoreConstants::SETTING__SHORT_MENU, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['shortMenu'] = ($result['payload']->setting_value === true || $result['payload']->setting_value === 'true' || $result['payload']->setting_value === 1 || $result['payload']->setting_value === '1' ? true : false);
            } else {
                $data['shortMenu'] = false;
            }

            //get menu layout
            $result = $this->getSettingByKey(CoreConstants::SETTING__MENU_LAYOUT, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['menuLayout'] = $result['payload']->setting_value;
            } else {
                $data['menuLayout'] = 'mix';
            }

            //get menu color
            $result = $this->getSettingByKey(CoreConstants::SETTING__MENU_COLOR, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['menuColor'] = $result['payload']->setting_value;
            } else {
                $data['menuColor'] = 'light';
            }

            //get nav color
            $result = $this->getSettingByKey(CoreConstants::SETTING__NAV_COLOR, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['navColor'] = $result['payload']->setting_value;
            } else {
                $data['navColor'] = 'light';
            }

            //get site name
            $data['siteName'] = DotenvEditor::getValue('APP_NAME');

            //get logo
            $result = $this->getSettingByKey(CoreConstants::SETTING__LOGO, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['logo'] = $result['payload']->setting_value;
            } else {
                $data['logo'] = 'assets/common/img/logo/default.png';
            }

            //get favicon
            $result = $this->getSettingByKey(CoreConstants::SETTING__FAVICON, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['favicon'] = $result['payload']->setting_value;
            } else {
                $data['favicon'] = 'assets/common/img/favicon/default.png';
            }

            //get cover photo
            $about = resolve(AboutInterface::class);

            $result = $about->getAll(['cover', 'id']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['cover'] = $result['payload']->cover;
            } else {
                $data['cover'] = 'assets/common/img/cover/default.png';
            }

            //get avatar
            $result = $about->getAll(['avatar', 'id']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $data['avatar'] = $result['payload']->avatar;
            } else {
                $data['avatar'] = 'assets/common/img/avatar/default.png';
            }

            //get mail setting
            $data['mailSettings']['MAIL_MAILER'] = env('MAIL_MAILER');
            $data['mailSettings']['MAIL_HOST'] = env('MAIL_HOST');
            $data['mailSettings']['MAIL_PORT'] = env('MAIL_PORT');
            $data['mailSettings']['MAIL_USERNAME'] = env('MAIL_USERNAME');
            $data['mailSettings']['MAIL_PASSWORD'] = env('MAIL_PASSWORD');
            $data['mailSettings']['MAIL_ENCRYPTION'] = env('MAIL_ENCRYPTION');
            $data['mailSettings']['MAIL_FROM_ADDRESS'] = env('MAIL_FROM_ADDRESS');
            $data['mailSettings']['MAIL_FROM_NAME'] = env('MAIL_FROM_NAME');

            //get demo mode
            $data['demoMode'] = Config::get('custom.demo_mode');
            
            return [
                'message' => 'Settings are fetched successfully',
                'payload' => $data,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Set single setting
     *
     * @param array $data
     * @return array
     */
    public function setSettingData(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'setting_key' => 'required',
                'setting_value' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }
            
            if ($data['setting_key'] === CoreConstants::SETTING__SITE_NAME) {
                $result = $this->updateSiteName($data['setting_value']);
            } else {
                $newData['setting_key'] = $data['setting_key'];
                $newData['setting_value'] = $data['setting_value'];

                $result = $this->insertOrUpdate($newData);
            }
            
            return $result;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Update Site name related env variables
     *
     * @param string $newName
     * @return array
     */
    public function updateSiteName(string $newName)
    {
        try {
            $file = DotenvEditor::setKey('APP_NAME', $newName);
            $file = DotenvEditor::save();
            if ($file) {
                return [
                    'message' => 'Site name is successfully updated',
                    'payload' => $newName,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Process the update logo request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateLogoRequest(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'file' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }
            $file = $data['file'];
            $extension = $file->extension() ? $file->extension() : 'png';
            $fileName = Str::random(10). '_'. time() .'.'. $extension;
            $pathName = 'assets/common/img/logo/';
            
            if (!file_exists($pathName)) {
                mkdir($pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous logo
                try {
                    $oldLogoResponse = $this->getSettingByKey(CoreConstants::SETTING__LOGO);
                    if ($oldLogoResponse['status'] === CoreConstants::STATUS_CODE_SUCCESS && $oldLogoResponse['payload']->setting_value !== 'assets/common/img/logo/default.png' && file_exists($oldLogoResponse['payload']->setting_value)) {
                        unlink($oldLogoResponse['payload']->setting_value);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result  = $this->setSettingData([
                    'setting_key'  => CoreConstants::SETTING__LOGO,
                    'setting_value' => $pathName.$fileName,
                ]);

                if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'File is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => CoreConstants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'File could not be saved',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Process the delete logo request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteLogoRequest(string $file)
    {
        try {
            if (!file_exists($file)) {
                return [
                    'message' => 'The file can\'t be found',
                    'payload' => $file,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
            if (unlink($file)) {
                $defaultLogo = 'assets/common/img/logo/default.png';

                $result  = $this->setSettingData([
                    'setting_key' => CoreConstants::SETTING__LOGO,
                    'setting_value' => $defaultLogo,
                ]);

                if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'Logo is deleted successfully',
                        'payload' => [
                            'file' => $defaultLogo
                        ],
                        'status' => CoreConstants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'Logo could not be deleted',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Process the update favicon request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateFaviconRequest(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'file' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $file = $data['file'];
            $extension = $file->extension() ? $file->extension() : 'png';
            $fileName = Str::random(10). '_'. time() .'.'. $extension;
            $pathName = 'assets/common/img/favicon/';
            
            if (!file_exists($pathName)) {
                mkdir($pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous favicon
                try {
                    $oldFaviconResponse = $this->getSettingByKey(CoreConstants::SETTING__FAVICON);
                    if ($oldFaviconResponse['status'] === CoreConstants::STATUS_CODE_SUCCESS && $oldFaviconResponse['payload']->setting_value !== 'assets/common/img/favicon/default.png' && file_exists($oldFaviconResponse['payload']->setting_value)) {
                        unlink($oldFaviconResponse['payload']->setting_value);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result  = $this->setSettingData([
                    'setting_key' => CoreConstants::SETTING__FAVICON,
                    'setting_value' => $pathName.$fileName,
                ]);

                if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'Favicon is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => CoreConstants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'Favicon could not be saved',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Process the delete favicon request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteFaviconRequest(string $file)
    {
        try {
            if (!file_exists($file)) {
                return [
                    'message' => 'The file can\'t be found',
                    'payload' => $file,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
            if (unlink($file)) {
                $defaultFavicon = 'assets/common/img/favicon/default.png';
                $result  = $this->setSettingData([
                    'setting_key' => CoreConstants::SETTING__FAVICON,
                    'setting_value' => $defaultFavicon,
                ]);

                if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'File is deleted successfully',
                        'payload' => [
                            'file' => $defaultFavicon
                        ],
                        'status' => CoreConstants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'File could not be deleted',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * store mail settings
     *
     * @param array $data
     * @return array
     */
    public function storeMailSettings(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'MAIL_MAILER' => 'required',
                'MAIL_HOST' => 'required',
                'MAIL_PORT' => 'required',
                'MAIL_USERNAME' => 'required',
                'MAIL_PASSWORD' => 'required',
                'MAIL_ENCRYPTION' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }
            
            $file = DotenvEditor::setKey('MAIL_MAILER', $data['MAIL_MAILER']);
            $file = DotenvEditor::setKey('MAIL_HOST', $data['MAIL_HOST']);
            $file = DotenvEditor::setKey('MAIL_PORT', $data['MAIL_PORT']);
            $file = DotenvEditor::setKey('MAIL_USERNAME', $data['MAIL_USERNAME']);
            $file = DotenvEditor::setKey('MAIL_PASSWORD', $data['MAIL_PASSWORD']);
            $file = DotenvEditor::setKey('MAIL_ENCRYPTION', $data['MAIL_ENCRYPTION']);
            $file = DotenvEditor::setKey('MAIL_FROM_ADDRESS', !empty($data['MAIL_FROM_ADDRESS']) ? $data['MAIL_FROM_ADDRESS'] : '');
            $file = DotenvEditor::setKey('MAIL_FROM_NAME', !empty($data['MAIL_FROM_NAME']) ? $data['MAIL_FROM_NAME'] : '');
            $file = DotenvEditor::save();

            if ($file) {
                return [
                    'message' => 'Mail setting is successfully updated',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }
}
