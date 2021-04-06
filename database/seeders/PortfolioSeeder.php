<?php

namespace Database\Seeders;

use App\Models\PortfolioConfig;
use App\Services\Contracts\PortfolioConfigContract;
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
            $portfolioConfig  = resolve(PortfolioConfigContract::class);

            //portfolio config table seed

            //template
            $data = [
                'key'           => PortfolioConfig::TEMPLATE,
                'value'         => 'procyon',
                'default_value' => 'procyon',
            ];
            $portfolioConfig->insertOrUpdate($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
