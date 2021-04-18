<?php

namespace Database\Seeders;

use CoreConstants;
use App\Models\Setting;
use App\Services\Contracts\AdminInterface;
use App\Services\Contracts\SettingInterface;
use DotenvEditor;
use Exception;
use Illuminate\Database\Seeder;
use Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $adminService= resolve(AdminInterface::class);

            $result = $adminService->handleSignup([
                'email' => 'admin@admin.com',
                'password' => '12345',
                'password_confirmation' => '12345',
            ]);

            if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                throw new Exception($result['message']);
            } else {
                $admin = $result['payload']['admin'];

                if (!empty($admin)) {
                    //setting table seed
                    try {
                        $settingService = resolve(SettingInterface::class);
                        //site name
                        $file = DotenvEditor::setKey('APP_NAME', 'Ezfolio');
                        $file = DotenvEditor::save();

                        //accent color
                        $data = [
                            'setting_key' => CoreConstants::SETTING__ACCENT_COLOR,
                            'setting_value' => '#1890ff',
                            'default_value' => '#1890ff',
                        ];
                        $settingService->insertOrUpdate($data);

                        //short menu
                        $data = [
                            'setting_key' => CoreConstants::SETTING__SHORT_MENU,
                            'setting_value' => CoreConstants::FALSE,
                            'default_value' => CoreConstants::FALSE,
                        ];
                        $settingService->insertOrUpdate($data);

                        //menu layout
                        $data = [
                            'setting_key' => CoreConstants::SETTING__MENU_LAYOUT,
                            'setting_value' => 'mix',
                            'default_value' => 'mix',
                        ];
                        $settingService->insertOrUpdate($data);

                        //menu color
                        $data = [
                            'setting_key' => CoreConstants::SETTING__MENU_COLOR,
                            'setting_value' => 'light',
                            'default_value' => 'light',
                        ];
                        $settingService->insertOrUpdate($data);

                        //menu color
                        $data = [
                            'setting_key' => CoreConstants::SETTING__NAV_COLOR,
                            'setting_value' => 'light',
                            'default_value' => 'light',
                        ];
                        $settingService->insertOrUpdate($data);

                        //logo
                        try {
                            if (is_dir('public/assets/common/img/logo')) {
                                $dir = 'public/assets/common/img/logo';
                            } else {
                                $dir = 'assets/common/img/logo';
                            }
                            $leave_files = array('.gitkeep');
                            
                            foreach (glob("$dir/*") as $file) {
                                if (!in_array(basename($file), $leave_files)) {
                                    unlink($file);
                                }
                            }

                            if (is_dir('public/assets/common/img/logo')) {
                                copy('public/assets/common/default/logo/default.png', $dir.'/default.png');
                            } else {
                                copy('assets/common/default/logo/default.png', $dir.'/default.png');
                            }
                        } catch (\Throwable $th) {
                            Log::error($th->getMessage());
                        }

                        $data = [
                            'setting_key' => CoreConstants::SETTING__LOGO,
                            'setting_value' => 'assets/common/img/logo/default.png',
                            'default_value' => 'assets/common/img/logo/default.png',
                        ];
                        $settingService->insertOrUpdate($data);

                        //favicon
                        try {
                            if (is_dir('public/assets/common/img/favicon')) {
                                $dir = 'public/assets/common/img/favicon';
                            } else {
                                $dir = 'assets/common/img/favicon';
                            }
                            $leave_files = array('.gitkeep');
                            
                            foreach (glob("$dir/*") as $file) {
                                if (!in_array(basename($file), $leave_files)) {
                                    unlink($file);
                                }
                            }

                            if (is_dir('public/assets/common/img/favicon')) {
                                copy('public/assets/common/default/favicon/default.png', $dir.'/default.png');
                            } else {
                                copy('assets/common/default/favicon/default.png', $dir.'/default.png');
                            }
                        } catch (\Throwable $th) {
                            Log::error($th->getMessage());
                        }
                        
                        $data = [
                            'setting_key' => CoreConstants::SETTING__FAVICON,
                            'setting_value' => 'assets/common/img/favicon/default.png',
                            'default_value' => 'assets/common/img/favicon/default.png',
                        ];
                        $settingService->insertOrUpdate($data);
                    } catch (\Throwable $th) {
                        Log::error($th->getMessage());
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
