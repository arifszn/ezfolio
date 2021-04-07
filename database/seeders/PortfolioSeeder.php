<?php

namespace Database\Seeders;

use App\Models\PortfolioConfig;
use App\Services\Contracts\PortfolioConfigContract;
use Constants;
use Illuminate\Database\Seeder;

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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
