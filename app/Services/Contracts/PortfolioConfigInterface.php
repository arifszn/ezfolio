<?php

namespace App\Services\Contracts;

interface PortfolioConfigInterface
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
        bool $seo = true,
        bool $visibility = true,
        bool $script = true
    );

    /**
     * Set single config
     *
     * @param array $data
     * @return array
     */
    public function setConfigData(array $data);

    /**
     * Store meta data
     *
     * @param array $data
     * @return array
     */
    public function setMetaData(array $data);
}
