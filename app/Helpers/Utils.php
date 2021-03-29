<?php

use App\Models\Setting;
use App\Services\Contracts\SettingContract;

class Utils {
    public static function isShortMenu()
    {
        try {
            $settingService = resolve(SettingContract::class);
            $result = $settingService->getSettingByKey(Setting::SHORT_MENU, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
                if ($result['payload']->setting_value === true || $result['payload']->setting_value === 'true' || $result['payload']->setting_value === 1 || $result['payload']->setting_value === '1') {
                    return true;
                };
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getFavicon()
    {
        try {
            $settingService = resolve(SettingContract::class);
            $result = $settingService->getSettingByKey(Setting::FAVICON, ['setting_value']);

            if ($result['status'] === Constants::STATUS_CODE_SUCCESS) {
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