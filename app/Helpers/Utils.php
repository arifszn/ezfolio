<?php

namespace App\Helpers;

use CoreConstants;
use App\Models\Setting;
use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\SettingInterface;

class Utils
{
    /**
     * Get the favicon path
     *
     * @return string
     */
    public static function getFavicon()
    {
        try {
            $settingService = resolve(SettingInterface::class);
            $result = $settingService->getSettingByKey(CoreConstants::SETTING__FAVICON, ['setting_value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                return asset($result['payload']->setting_value);
            } else {
                return asset('assets/common/img/favicon/default.png');
            }
        } catch (\Throwable $th) {
            return asset('assets/common/img/favicon/default.png');
        }
    }
    
    /**
     * Return rgb value of a hex color value
     *
     * @param string $hex
     * @return string //r, g, b
     */
    public static function getRgbValue(string $hex)
    {
        $split = str_split(str_replace("#", "", $hex), 2);
        $r = hexdec($split[0]);
        $g = hexdec($split[1]);
        $b = hexdec($split[2]);
        $accentColorRGB = $r . ", " . $g . ", " . $b; //e.g. 255, 255, 255
        
        return $accentColorRGB;
    }

    /**
     * Get the logo path
     *
     * @return string
     */
    public static function getLogo()
    {
        try {
            $setting = resolve(SettingInterface::class);
            $result = $setting->getSettingByKey(CoreConstants::SETTING__LOGO, ['value']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                return asset($result['payload']->value);
            } else {
                return asset('assets/common/img/logo/default.png');
            }
        } catch (\Throwable $th) {
            return asset('assets/common/img/logo/default.png');
        }
    }

    /**
     * Get the avatar path
     *
     * @return string
     */
    public static function getAvatar()
    {
        try {
            $about = resolve(AboutInterface::class);
            $result = $about->getAll(['avatar']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                return asset($result['payload']->avatar);
            }

            return asset('assets/common/img/avatar/default.png');
        } catch (\Throwable $th) {
            return asset('assets/common/img/avatar/default.png');
        }
    }

    /**
     * Get the cover path
     *
     * @return string
     */
    public static function getCover()
    {
        try {
            $about = resolve(AboutContract::class);
            $result = $about->getAll(['cover']);

            if ($result['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                return asset($result['payload']->cover);
            }

            return asset('assets/common/img/cover/default.png');
        } catch (\Throwable $th) {
            return asset('assets/common/img/cover/default.png');
        }
    }
}
