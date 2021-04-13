<?php

namespace App\Helpers;

use CoreConstants;
use App\Models\Setting;
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
}
