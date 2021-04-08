<?php

namespace Database\Seeders;

use App\Models\PortfolioConfig;
use App\Services\Contracts\PortfolioConfigContract;
use Constants;
use Illuminate\Database\Seeder;
use Log;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $portfolioConfig = resolve(PortfolioConfigContract::class);

            //portfolio config table seed

            //template
            $data = [
                'setting_key' => PortfolioConfig::TEMPLATE,
                'setting_value' => 'procyon',
                'default_value' => 'procyon',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //accent color
            $data = [
                'setting_key' => PortfolioConfig::ACCENT_COLOR,
                'setting_value' => '#0168fa',
                'default_value' => '#0168fa',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //google analytics ID
            $data = [
                'setting_key' => PortfolioConfig::GOOGLE_ANALYTICS_ID,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //maintenance mode
            $data = [
                'setting_key' => PortfolioConfig::MAINTENANCE_MODE,
                'setting_value' => Constants::FALSE,
                'default_value' => Constants::FALSE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            //menus
            $data = [
                'setting_key' => PortfolioConfig::MENU_ABOUT,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_SKILL,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_EDUCATION,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_EXPERIENCE,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_PROJECT,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_SERVICE,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_CONTACT,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            $data = [
                'setting_key' => PortfolioConfig::MENU_FOOTER,
                'setting_value' => Constants::TRUE,
                'default_value' => Constants::TRUE,
            ];
            $portfolioConfig->insertOrUpdate($data);

            //header script
            $data = [
                'setting_key' => PortfolioConfig::SCRIPT_HEADER,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //footer script
            $data = [
                'setting_key' => PortfolioConfig::SCRIPT_FOOTER,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);


            //meta title
            $data = [
                'setting_key' => PortfolioConfig::META_TITLE,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //meta author
            $data = [
                'setting_key' => PortfolioConfig::META_AUTHOR,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //meta description
            $data = [
                'setting_key' => PortfolioConfig::META_DESCRIPTION,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);

            //meta image
            try {
                if (is_dir('public/assets/common/img/meta-image')) {
                    $dir = 'public/assets/common/img/meta-image';
                } else {
                    $dir = 'assets/common/img/meta-image';
                }
                $leave_files = array('.gitkeep');
                
                foreach( glob("$dir/*") as $file ) {
                    if( !in_array(basename($file), $leave_files) ){
                        unlink($file);
                    }
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
            $data = [
                'setting_key' => PortfolioConfig::META_IMAGE,
                'setting_value' => '',
                'default_value' => '',
            ];
            $portfolioConfig->insertOrUpdate($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
