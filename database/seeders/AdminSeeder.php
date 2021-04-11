<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\Contracts\AdminContract;
use App\Services\Contracts\SettingContract;
use Constants;
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
            $adminContract = resolve(AdminContract::class);

            $result = $adminContract->handleSignup([
                'email' => 'swazan.arif@gmail.com',
                'password' => '12345',
                'password_confirmation' => '12345',
            ]);

            if ($result['status'] !== Constants::STATUS_CODE_SUCCESS) {
                throw new Exception($result['message']);
            } else {
                $admin = $result['payload']['admin'];

                if (!empty($admin)) {

                    //setting table seed
                    try {
                        $settingContract = resolve(SettingContract::class);
                        //site name
                        $file = DotenvEditor::setKey('APP_NAME', 'Ezfolio');
                        $file = DotenvEditor::save();

                        //accent color
                        $data = [
                            'setting_key' => Setting::ACCENT_COLOR,
                            'setting_value' => '#00bfa5',
                            'default_value' => '#00bfa5',
                        ];
                        $settingContract->insertOrUpdate($data);

                        //short menu
                        $data = [
                            'setting_key' => Setting::SHORT_MENU,
                            'setting_value' => Constants::FALSE,
                            'default_value' => Constants::FALSE,
                        ];
                        $settingContract->insertOrUpdate($data);

                        //menu layout
                        $data = [
                            'setting_key' => Setting::MENU_LAYOUT,
                            'setting_value' => 'mix',
                            'default_value' => 'mix',
                        ];
                        $settingContract->insertOrUpdate($data);

                        //menu color
                        $data = [
                            'setting_key' => Setting::MENU_COLOR,
                            'setting_value' => 'light',
                            'default_value' => 'light',
                        ];
                        $settingContract->insertOrUpdate($data);

                        //menu color
                        $data = [
                            'setting_key' => Setting::NAV_COLOR,
                            'setting_value' => 'light',
                            'default_value' => 'light',
                        ];
                        $settingContract->insertOrUpdate($data);

                        //logo
                        try {
                            if (is_dir('public/assets/common/img/logo')) {
                                $dir = 'public/assets/common/img/logo';
                            } else {
                                $dir = 'assets/common/img/logo';
                            }
                            $leave_files = array('.gitkeep');
                            
                            foreach( glob("$dir/*") as $file ) {
                                if( !in_array(basename($file), $leave_files) ){
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
                            'setting_key' => Setting::LOGO,
                            'setting_value' => 'assets/common/img/logo/default.png',
                            'default_value' => 'assets/common/img/logo/default.png',
                        ];
                        $settingContract->insertOrUpdate($data);

                        //favicon
                        try {
                            if (is_dir('public/assets/common/img/favicon')) {
                                $dir = 'public/assets/common/img/favicon';
                            } else {
                                $dir = 'assets/common/img/favicon';
                            }
                            $leave_files = array('.gitkeep');
                            
                            foreach( glob("$dir/*") as $file ) {
                                if( !in_array(basename($file), $leave_files) ){
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
                            'setting_key' => Setting::FAVICON,
                            'setting_value' => 'assets/common/img/favicon/default.png',
                            'default_value' => 'assets/common/img/favicon/default.png',
                        ];
                        $settingContract->insertOrUpdate($data);
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
