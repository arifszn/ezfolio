<?php

namespace App\Services\Contracts;

interface SettingInterface
{
    /**
     * If setting exist, update it. Otherwise insert new
     *
     * @param array $data
     * @return array
     */
    public function insertOrUpdate(array $data);

    /**
     * Get single setting by key
     *
     * @param int $key
     * @return array
     */
    public function getSettingByKey(int $key);

    /**
     * Get all related settings
     *
     * @return array
     */
    public function getSettingsData();

    /**
     * Set single setting
     *
     * @param array $data
     * @return array
     */
    public function setSettingData(array $data);

    /**
     * Update Site name related env variables
     *
     * @param string $newName
     * @return array
     */
    public function updateSiteName(string $newName);

    /**
     * Process the update logo request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateLogoRequest(array $data);

    /**
     * Process the delete logo request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteLogoRequest(string $file);

    /**
     * Process the update favicon request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateFaviconRequest(array $data);

    /**
     * Process the delete favicon request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteFaviconRequest(string $file);

    /**
     * store mail settings
     *
     * @param array $data
     * @return array
     */
    public function storeMailSettings(array $data);
}
