<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface PortfolioConfigContract
{
    /**
     * If config exist, update it. Otherwise insert new
     * 
     * @param array $data 
     * @return array 
     */
    public function insertOrUpdate(array $data);

    /**
     * Get single config by key
     * 
     * @param int $key
     * @param array $select 
     * @return array 
     */
    public function getConfigByKey(int $key, array $select = ['*']);

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
    );

    /**
     * Set single config
     * 
     * @param array $data 
     * @return array 
     */
    public function setConfigData(array $data);
}
