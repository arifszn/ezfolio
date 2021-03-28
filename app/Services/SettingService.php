<?php

namespace App\Services;

use App;
use App\Constant;
use App\Models\Setting;
use App\Services\Contracts\AboutContract;
use App\Services\Contracts\SettingContract;
use Config;
use Constants;
use Illuminate\Http\Request;
use Log;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Str;
use Validator;

class SettingService implements SettingContract
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
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
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
                    'status' => Constants::STATUS_CODE_SUCCESS
                ]; 
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];  
            } else {
                return [
                    'message' => 'Setting is not found',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
            $result = $this->getSettingByKey(Setting::ACCENT_COLOR, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['accentColor'] = $result['payload']->setting_value;
            } else {
                $data['accentColor'] = '#FF7F50';
            }

            //get navbar background
            $result = $this->getSettingByKey(Setting::NAV_BAR_BACKGROUND, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['navbarBG'] = $result['payload']->setting_value;
            } else {
                $data['navbarBG'] = '#ffffff';
            }

            //get navbar color
            $result = $this->getSettingByKey(Setting::NAV_BAR_COLOR, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['navbarColor'] = $result['payload']->setting_value;
            } else {
                $data['navbarColor'] = '#494a4c';
            }

            //get sidebar background
            $result = $this->getSettingByKey(Setting::SIDE_BAR_BACKGROUND, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['sidebarBG'] = $result['payload']->setting_value;
            } else {
                $data['sidebarBG'] = '#ffffff';
            }

            //get sidebar color
            $result = $this->getSettingByKey(Setting::SIDE_BAR_COLOR, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['sidebarColor'] = $result['payload']->setting_value;
            } else {
                $data['sidebarColor'] = '#3e4b5b';
            }

            //get short menu
            $result = $this->getSettingByKey(Setting::SHORT_MENU, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['shortMenu'] = ($result['payload']->setting_value === true || $result['payload']->setting_value === 'true' || $result['payload']->setting_value === 1 || $result['payload']->setting_value === '1' ? true : false);
            } else {
                $data['shortMenu'] = false;
            }

            //get site name
            $data['siteName'] = DotenvEditor::getValue('APP_NAME');

            //get logo
            $result = $this->getSettingByKey(Setting::LOGO, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['logo'] = $result['payload']->setting_value;
            } else {
                $data['logo'] = 'assets/common/img/logo/default.png';
            }

            //get favicon
            $result = $this->getSettingByKey(Setting::FAVICON, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['favicon'] = $result['payload']->setting_value;
            } else {
                $data['favicon'] = 'assets/common/img/favicon/default_favicon.png';
            }

            //get cover photo
            $about = resolve(AboutContract::class);

            $result     = $about->getAllFields(['cover', 'id']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['cover'] = $result['payload']->cover;
            } else {
                $data['cover'] = 'assets/common/img/cover/default_cover.png';
            }

            //get avatar
            $result     = $about->getAllFields(['avatar', 'id']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                $data['avatar'] = $result['payload']->avatar;
            } else {
                $data['avatar'] = 'assets/common/img/avatar/default_avatar.png';
            }

            //get mail setting
            $data['mailSetting']['MAIL_DRIVER'] = env('MAIL_DRIVER');
            $data['mailSetting']['MAIL_HOST'] = env('MAIL_HOST');
            $data['mailSetting']['MAIL_PORT'] = env('MAIL_PORT');
            $data['mailSetting']['MAIL_USERNAME'] = env('MAIL_USERNAME');
            $data['mailSetting']['MAIL_PASSWORD'] = env('MAIL_PASSWORD');
            $data['mailSetting']['MAIL_ENCRYPTION'] = env('MAIL_ENCRYPTION');
            $data['mailSetting']['MAIL_FROM_ADDRESS'] = env('MAIL_FROM_ADDRESS');
            $data['mailSetting']['MAIL_FROM_NAME'] = env('MAIL_FROM_NAME');

            //get demo mode
            $data['demoMode'] = Config::get('custom.demo_mode');
            
            return [
                'message' => 'Settings are fetched successfully',
                'payload' => $data,
                'status' => Constants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                'name' => 'required',
                'setting_value' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            
            if ($data['name'] === Setting::SITE_NAME) {
                $result = $this->updateSiteName($data['setting_value']);
            } else {
                $newData['setting_key'] = $data['name'];
                $newData['setting_value'] = $data['setting_value'];

                $result = $this->insertOrUpdate($newData);
            }
            
            return $result;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $fileName = Str::random(10). '_'. time() .'.png';
            $file = $data['file'];
            $pathName = 'assets/common/img/logo/';
            
            if (!file_exists($pathName)) {
                mkdir( $pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous logo
                try {
                    $oldLogoResponse = $this->getSettingByKey(Setting::LOGO);
                    if ($oldLogoResponse['status'] === Constants::STATUS_CODE_SUCCESS && $oldLogoResponse['payload']->setting_value !== 'assets/common/img/logo/default.png' && file_exists($oldLogoResponse['payload']->setting_value)) {
                        unlink($oldLogoResponse['payload']->setting_value);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result  = $this->setSettingData([
                    'name'  => Setting::LOGO,
                    'setting_value' => $pathName.$fileName,
                ]);

                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'File is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'File could not be saved',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
            if (unlink($file)) {
                $defaultLogo = 'assets/common/img/logo/default.png';

                $result  = $this->setSettingData([
                    'name' => Setting::LOGO,
                    'setting_value' => $defaultLogo,
                ]);

                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'Logo is deleted successfully',
                        'payload' => [
                            'file' => $defaultLogo
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'Logo could not be deleted',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Process the update favicon request
     * 
     * @param Request $request 
     * @return array
     */
    public function processUpdateFaviconRequest(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'filePond' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $fileName = Str::random(10). '_'. time() .'.png';
            $file = $request->file('filePond') ? $request->file('filePond') : $request->get('filePond');
            $pathName = 'assets/common/img/favicon/';
            
            if (!file_exists($pathName)) {
                mkdir( $pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {

                //delete previous favicon
                try {
                    $oldFaviconResponse = $this->getSettingByKey(Setting::FAVICON);
                    if ($oldFaviconResponse['status'] === Constants::STATUS_CODE_SUCCESS && $oldFaviconResponse['payload']->setting_value !== 'assets/common/img/favicon/default_favicon.png' && file_exists($oldFaviconResponse['payload']->setting_value)) {
                        unlink($oldFaviconResponse['payload']->setting_value);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result  = $this->setSettingData([
                    'name' => Setting::FAVICON,
                    'setting_value' => $pathName.$fileName,
                ]);

                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'Favicon is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'Favicon could not be saved',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
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
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
            if (unlink($file)) {
                $defaultFavicon = 'assets/common/img/favicon/default_favicon.png';
                $result  = $this->setSettingData([
                    'name' => Setting::FAVICON,
                    'setting_value' => $defaultFavicon,
                ]);

                if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                    return [
                        'message' => 'File is deleted successfully',
                        'payload' => [
                            'file' => $defaultFavicon
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return $result;
                }
            } else {
                return [
                    'message' => 'File could not be deleted',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * store mail setting
     * 
     * @param array $data 
     * @return array
     */
    public function storeMailSetting(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'MAIL_DRIVER' => 'required',
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
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }
            
            $file = DotenvEditor::setKey('MAIL_DRIVER', $data['MAIL_DRIVER']);
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
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }
}
